<?php

namespace App\Services\AiServices\FunctionCalling\Tools;

use App\Domain\Mcp\Entity\ToolCallEntity;
use App\Domain\Mcp\Repository\McpRepository;
use App\Services\AiServices\FunctionCalling\ToolExecutionContext;
use App\Services\AiServices\FunctionCalling\ToolResult;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ExecuteDomainActionTool extends AbstractTool
{
    public function __construct(
        private McpRepository $mcpRepository,
    ) {}

    public function getName(): string
    {
        return 'execute_domain_action';
    }

    public function getDescription(): string
    {
        // Load available MCP tools
        $mcpTools = $this->loadAvailableMcpTools();

        if ($mcpTools->isEmpty()) {
            return 'Execute domain-specific actions using MCP tools. (No tools currently available)';
        }

        $toolsList = $mcpTools->map(fn($tool) =>
            "- {$tool['name']}: {$tool['description']}"
        )->join("\n");

        return "Execute domain-specific actions using MCP tools. " .
               "Available actions:\n{$toolsList}\n\n" .
               "Use this tool when you need to perform CRUD operations or " .
               "domain-specific tasks like creating news, managing categories, etc. " .
               "このツールは、ニュースの作成やカテゴリの管理など、ドメイン固有のアクションを実行する場合に使用します。";
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'tool_name' => [
                    'type' => 'string',
                    'description' => 'Name of the MCP tool to execute (e.g., "news_create"). This must match one of the available tools listed in the description. 実行するMCPツールの名前（例："news_create"）',
                ],
                'arguments' => [
                    'type' => 'object',
                    'description' => 'Arguments for the tool. The specific fields required depend on the tool being called. Check the tool description for required parameters. ツールに渡す引数',
                ],
            ],
            'required' => ['tool_name', 'arguments'],
        ];
    }

    public function execute(array $arguments, ToolExecutionContext $context): ToolResult
    {
        $toolName = $arguments['tool_name'] ?? null;
        $toolArguments = $arguments['arguments'] ?? [];

        if (!$toolName) {
            return $this->error('Missing required parameter: tool_name. ツール名が指定されていません。');
        }

        // Validate tool exists and is active
        if (!$this->isToolAvailable($toolName)) {
            return $this->error("Tool '{$toolName}' is not available or not active. ツール '{$toolName}' は利用できません。");
        }

        try {
            // Create ToolCallEntity
            $entity = new ToolCallEntity(
                name: $toolName,
                arguments: $toolArguments
            );

            Log::info('ExecuteDomainActionTool: Executing MCP tool', [
                'tool_name' => $toolName,
                'arguments' => $toolArguments,
                'conversation_id' => $context->conversationId,
            ]);

            // Execute via MCP infrastructure
            $result = $this->mcpRepository->callTool($entity);

            if ($result->getStatusCode() !== 200) {
                Log::warning('ExecuteDomainActionTool: MCP tool execution failed', [
                    'tool_name' => $toolName,
                    'status_code' => $result->getStatusCode(),
                    'message' => $result->getMessage(),
                ]);

                return $this->error(
                    $result->getMessage() ?? 'MCP tool execution failed. MCP tool execution failed.',
                    ['status_code' => $result->getStatusCode()]
                );
            }

            Log::info('ExecuteDomainActionTool: MCP tool executed successfully', [
                'tool_name' => $toolName,
                'has_result' => $result->getResult() !== null,
            ]);

            return $this->success(
                data: $result->getResult(),
                message: $result->getMessage() ?? "Successfully executed {$toolName}. {$toolName}を正常に実行しました。"
            );
        } catch (\Exception $e) {
            Log::error('ExecuteDomainActionTool: Exception during MCP tool execution', [
                'tool_name' => $toolName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->error(
                "Failed to execute tool '{$toolName}': {$e->getMessage()}",
                ['exception' => get_class($e)]
            );
        }
    }

    public function isAvailable(ToolExecutionContext $context): bool
    {
        // Only available if application has MCP tools enabled
        return $context->application->enable_mcp_tools ?? false;
    }

    /**
     * Load all available and active MCP tools from the system
     *
     * @return Collection
     */
    private function loadAvailableMcpTools(): Collection
    {
        try {
            // Load from app/Mcp/Tools.php
            $toolsPath = app_path('Http/Actions/Mcp/Tools.php');
            
            if (!file_exists($toolsPath)) {
                Log::warning('ExecuteDomainActionTool: Tools.php file not found', [
                    'path' => $toolsPath,
                ]);
                return collect([]);
            }

            $allTools = require $toolsPath;

            if (!is_array($allTools)) {
                Log::warning('ExecuteDomainActionTool: Tools.php did not return an array');
                return collect([]);
            }

            // Filter by is_active status from database
            return collect($allTools)->filter(function ($tool) {
                $dbTool = \App\Models\McpTool::where('name', $tool['name'])
                    ->where('domain', $tool['domain'])
                    ->first();

                return $dbTool && $dbTool->is_active;
            })->values();
        } catch (\Exception $e) {
            Log::error('ExecuteDomainActionTool: Error loading MCP tools', [
                'error' => $e->getMessage(),
            ]);
            return collect([]);
        }
    }

    /**
     * Check if a specific MCP tool is available
     *
     * @param string $toolName
     * @return bool
     */
    private function isToolAvailable(string $toolName): bool
    {
        $availableTools = $this->loadAvailableMcpTools();
        return $availableTools->contains('name', $toolName);
    }
}
