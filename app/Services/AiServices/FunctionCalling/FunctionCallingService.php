<?php

namespace App\Services\AiServices\FunctionCalling;

use App\Enums\AiProviderType;
use App\Models\AiTool;
use App\Services\AiServices\FunctionCalling\Tools\AbstractTool;
use Illuminate\Support\Facades\Log;

class FunctionCallingService
{
    private const MAX_TOOL_ITERATIONS = 5;

    /** @var array<string, bool>|null Cache of active tool codes */
    private ?array $activeToolsCache = null;

    public function __construct(
        private ToolRegistry $registry,
        private ToolConverterFactory $converterFactory,
    ) {}

    /**
     * Get list of active tool codes from database
     *
     * @return array<string, bool> Map of tool code => is_active
     */
    private function getActiveToolCodes(): array
    {
        if ($this->activeToolsCache === null) {
            $this->activeToolsCache = AiTool::where('is_active', true)
                ->pluck('code')
                ->flip()
                ->map(fn () => true)
                ->toArray();
        }

        return $this->activeToolsCache;
    }

    /**
     * Check if a tool is active in database
     *
     * @param string $toolCode The tool code to check
     * @return bool
     */
    private function isToolActive(string $toolCode): bool
    {
        $activeTools = $this->getActiveToolCodes();
        return isset($activeTools[$toolCode]);
    }

    /**
     * Filter allowed tools to only include active ones
     *
     * @param array $allowedTools Tools configured in application
     * @return array Only active tools
     */
    private function filterActiveTools(array $allowedTools): array
    {
        return array_filter($allowedTools, fn ($toolCode) => $this->isToolActive($toolCode));
    }

    /**
     * Get tools configuration for API request
     *
     * @param string $providerType The AI provider type (openai, gemini, deepseek)
     * @param ToolExecutionContext $context The execution context
     * @return array Provider-formatted tools configuration
     */
    public function getToolsConfig(string $providerType, ToolExecutionContext $context): array
    {
        $availableTools = $this->registry->getAvailable($context);
        $tools = [];

        if (!$availableTools->isEmpty()) {
            $converter = $this->converterFactory->make($providerType);
            $tools = $converter->convertToolDefinitions($availableTools);
        }

        // Add OpenAI built-in tools for agent-type applications
        if ($providerType === AiProviderType::OPENAI && $context->application->type === 'agent') {
            $allowedTools = $context->application->tools ?? [];

            $activeAllowedTools = $this->filterActiveTools($allowedTools);
            $extraParams = $context->application->extra_params ?? [];

            $webSearchEnabled = $extraParams['web_search_enabled'] ?? true;
            if ($webSearchEnabled && in_array('web_search', $activeAllowedTools)) {
                $tools[] = [
                    'type' => 'web_search',
                ];
            }

            // Add image_generation if it's in the allowed tools list
            // This is a built-in OpenAI tool that uses Responses API
            if (in_array('generate_image', $activeAllowedTools)) {
                $imageGenerationTool = ['type' => 'image_generation'];

                // Add optional parameters from extra_params
                if (!empty($extraParams['image_size'])) {
                    $imageGenerationTool['size'] = $extraParams['image_size'];
                }
                if (!empty($extraParams['image_quality'])) {
                    $imageGenerationTool['quality'] = $extraParams['image_quality'];
                }
                if (!empty($extraParams['image_background'])) {
                    $imageGenerationTool['background'] = $extraParams['image_background'];
                }

                $tools[] = $imageGenerationTool;
            }

            if (in_array('code_interpreter', $activeAllowedTools)) {
                $codeInterpreterTool = [
                    'type' => 'code_interpreter',
                    'container' => ['type' => 'auto'],
                ];

                $tools[] = $codeInterpreterTool;
            }
            
            if ($context->application->enable_mcp_tools ?? false) {
                $mcpServerUrl = rtrim(config('app.url'), '/') . '/api/v1/ai/mcp';    
                $tools[] = [
                    'type' => 'mcp',
                    'server_label' =>  'elemental_cloud_mcp',
                    'server_description' => 
                        'MCP server for domain-specific actions like creating news, managing categories, and other CRUD operations',
                    'server_url' => $mcpServerUrl,
                    'require_approval' => 'never',
                ];
            }
        }

        return $tools;
    }

    /**
     * Check if there are any tools available for this context
     */
    public function hasAvailableTools(ToolExecutionContext $context): bool
    {
        // Check custom tools (ToolRegistry already filters by application.tools)
        if ($this->registry->getAvailable($context)->isNotEmpty()) {
            return true;
        }

        // Check built-in tools (OpenAI only)
        if ($context->provider->provider === AiProviderType::OPENAI && $context->application->type === 'agent') {
            $allowedTools = $context->application->tools ?? [];
            // Filter to only include active tools from DB
            $activeAllowedTools = $this->filterActiveTools($allowedTools);

            // Check for built-in tools that are active
            $builtInTools = ['web_search', 'generate_image', 'code_interpreter'];
            if (count(array_intersect($builtInTools, $activeAllowedTools)) > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Process tool calls from API response
     *
     * @param array $response Raw API response
     * @param string $providerType The AI provider type
     * @param ToolExecutionContext $context The execution context
     * @return FunctionCallingResult
     */
    public function processToolCalls(
        array $response,
        string $providerType,
        ToolExecutionContext $context
    ): FunctionCallingResult {
        $converter = $this->converterFactory->make($providerType);

        if (!$converter->hasToolCalls($response)) {
            return new FunctionCallingResult(
                hasToolCalls: false,
                isComplete: true,
                toolCalls: [],
                results: [],
                followUpMessages: []
            );
        }

        $toolCalls = $converter->parseToolCalls($response);
        $results = [];

        // Get allowed tools from application configuration and filter by active status
        $allowedTools = $context->application->tools ?? [];
        $activeAllowedTools = $this->filterActiveTools($allowedTools);

        foreach ($toolCalls as $toolCall) {
            // Check if tool is in the allowed list
            if (!in_array($toolCall->name, $allowedTools)) {
                Log::warning('Tool not allowed for this application', [
                    'tool_name' => $toolCall->name,
                    'allowed_tools' => $allowedTools,
                ]);
                $results[] = new ToolResult(
                    success: false,
                    message: "Tool '{$toolCall->name}' is not allowed for this application"
                );
                continue;
            }

            // Check if tool is active in DB
            if (!in_array($toolCall->name, $activeAllowedTools)) {
                Log::warning('Tool is disabled in database', [
                    'tool_name' => $toolCall->name,
                ]);
                $results[] = new ToolResult(
                    success: false,
                    message: "Tool '{$toolCall->name}' is currently disabled"
                );
                continue;
            }

            $tool = $this->registry->get($toolCall->name);

            if ($tool === null) {
                Log::warning('Tool not found', ['tool_name' => $toolCall->name]);
                $results[] = new ToolResult(
                    success: false,
                    message: "Tool '{$toolCall->name}' not found"
                );
                continue;
            }

            try {
                Log::info('Executing tool', [
                    'tool_name' => $toolCall->name,
                    'arguments' => $toolCall->arguments,
                ]);

                $results[] = $tool->execute($toolCall->arguments, $context);
            } catch (\Exception $e) {
                Log::error('Tool execution failed', [
                    'tool_name' => $toolCall->name,
                    'error' => $e->getMessage(),
                ]);

                $results[] = new ToolResult(
                    success: false,
                    message: "Tool execution failed: " . $e->getMessage()
                );
            }
        }

        $followUpMessages = $converter->buildToolResultsMessage($toolCalls, $results, $response);

        return new FunctionCallingResult(
            hasToolCalls: true,
            isComplete: false,
            toolCalls: $toolCalls,
            results: $results,
            followUpMessages: $followUpMessages
        );
    }

    /**
     * Check if we should continue the tool loop
     *
     * @param int $iteration Current iteration count
     * @param array $response API response
     * @param string $providerType The AI provider type
     * @param int|null $maxIterations Maximum iterations (uses default if null)
     * @return bool
     */
    public function shouldContinue(int $iteration, array $response, string $providerType, ?int $maxIterations = null): bool
    {
        $limit = $maxIterations ?? self::MAX_TOOL_ITERATIONS;

        if ($iteration >= $limit) {
            Log::warning('Max tool iterations reached', ['iteration' => $iteration, 'limit' => $limit]);
            return false;
        }

        $converter = $this->converterFactory->make($providerType);
        return $converter->hasToolCalls($response) && !$converter->isComplete($response);
    }

    /**
     * Extract final text answer from response
     */
    public function extractAnswer(array $response, string $providerType): string
    {
        $converter = $this->converterFactory->make($providerType);
        return $converter->extractTextContent($response);
    }

    /**
     * Aggregate generated files from all tool results
     *
     * @param array $results Array of ToolResult objects
     * @return array
     */
    public function aggregateGeneratedFiles(array $results): array
    {
        $files = [];
        foreach ($results as $result) {
            if ($result instanceof ToolResult && !empty($result->generatedFiles)) {
                $files = array_merge($files, $result->generatedFiles);
            }
        }
        return $files;
    }

    /**
     * Get the maximum number of tool iterations
     */
    public function getMaxIterations(): int
    {
        return self::MAX_TOOL_ITERATIONS;
    }

    /**
     * Get all registered tools (for debugging/info)
     */
    public function getRegisteredTools(): array
    {
        return $this->registry->all()->map(function (AbstractTool $tool) {
            return [
                'name' => $tool->getName(),
                'description' => $tool->getDescription(),
            ];
        })->values()->toArray();
    }

    /**
     * Extract generated images from API response (for built-in image_generation tool)
     *
     * @param array $response Raw API response
     * @param string $providerType The AI provider type
     * @return array Array of generated image data
     */
    public function extractGeneratedImages(array $response, string $providerType): array
    {
        $converter = $this->converterFactory->make($providerType);
        return $converter->extractGeneratedImages($response);
    }

    /**
     * Check if response contains image generation results
     *
     * @param array $response Raw API response
     * @param string $providerType The AI provider type
     * @return bool
     */
    public function hasImageGenerationResults(array $response, string $providerType): bool
    {
        $converter = $this->converterFactory->make($providerType);
        return $converter->hasImageGenerationResults($response);
    }

    /**
     * Extract code interpreter generated files from API response
     *
     * @param array $response Raw API response
     * @param string $providerType The AI provider type
     * @return array Array of generated file data
     */
    public function extractCodeInterpreterFiles(array $response, string $providerType): array
    {
        $converter = $this->converterFactory->make($providerType);
        return $converter->extractCodeInterpreterFiles($response);
    }

    /**
     * Check if response contains code interpreter results
     *
     * @param array $response Raw API response
     * @param string $providerType The AI provider type
     * @return bool
     */
    public function hasCodeInterpreterResults(array $response, string $providerType): bool
    {
        $converter = $this->converterFactory->make($providerType);
        return $converter->hasCodeInterpreterResults($response);
    }

    /**
     * Extract all tool calls from response including built-in tools
     * This includes function calls, code_interpreter, image_generation, web_search, etc.
     * Used for displaying in reasoning steps UI
     *
     * @param array $response Raw API response
     * @param string $providerType The AI provider type
     * @return array Array of tool call info with type and details
     */
    public function extractAllToolCalls(array $response, string $providerType): array
    {
        $converter = $this->converterFactory->make($providerType);
        return $converter->extractAllToolCalls($response);
    }
}
