<?php

namespace App\Services\AiServices\FunctionCalling\Tools;

use App\Services\AiServices\FunctionCalling\ToolExecutionContext;
use App\Services\AiServices\FunctionCalling\ToolResult;
use App\Services\AiServices\RagService;
use App\Services\AiServices\WebsitePromptBuilder;

class GenerateWebsiteTool extends AbstractTool
{
    public function __construct(
        private WebsitePromptBuilder $promptBuilder,
        private RagService $ragService,
    ) {}

    public function getName(): string
    {
        return 'generate_website';
    }

    public function getDescription(): string
    {
        return 'Generate a complete, professional website with HTML, CSS, and JavaScript. ' .
               'Use this tool when the user wants to create a website, landing page, homepage, or web page. ' .
               'The tool will create production-ready code with modern design using Tailwind CSS. ' .
               'ウェブサイト生成ツール。ランディングページ、ホームページ、ウェブページを作成する場合に使用します。';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'website_name' => [
                    'type' => 'string',
                    'description' => 'Name for the website (e.g., "My Portfolio", "Coffee Shop Landing Page", "企業サイト")',
                ],
                'website_type' => [
                    'type' => 'string',
                    'enum' => ['landing_page', 'portfolio', 'business', 'blog', 'ecommerce', 'other'],
                    'description' => 'Type of website to generate (ウェブサイトの種類)',
                ],
                'description' => [
                    'type' => 'string',
                    'description' => 'Detailed description of what the website should contain, its purpose, target audience, and any specific features or sections required (ウェブサイトの詳細な説明)',
                ],
                'color_scheme' => [
                    'type' => 'string',
                    'description' => 'Preferred color scheme (e.g., "blue and white", "dark mode", "vibrant colors", "モダンな青系")',
                ],
                'include_sections' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                    'description' => 'Specific sections to include (e.g., ["hero", "features", "testimonials", "contact", "footer"])',
                ],
            ],
            'required' => ['description'],
        ];
    }

    public function execute(array $arguments, ToolExecutionContext $context): ToolResult
    {
        $websiteName = $arguments['website_name'] ?? null;
        $websiteType = $arguments['website_type'] ?? 'landing_page';
        $description = $arguments['description'];
        $colorScheme = $arguments['color_scheme'] ?? null;
        $includeSections = $arguments['include_sections'] ?? [];

        // Build the generation context
        $generationContext = $this->buildGenerationContext($arguments, $context);

        // Return the generation prompt for the AI to use in generating the website
        return $this->success(
            data: [
                'action' => 'generate_website',
                'website_name' => $websiteName,
                'website_type' => $websiteType,
                'generation_prompt' => $generationContext['prompt'],
                'knowledge_base_content' => $generationContext['knowledge_content'],
                'instructions' => 'Now generate the complete website code. ' .
                    'Output all files in the format: ```language:filename\ncode\n``` ' .
                    '(e.g., ```html:index.html, ```css:styles.css, ```javascript:script.js). ' .
                    'Create a professional, responsive website using Tailwind CSS. ' .
                    'Use ALL content from the knowledge base if provided. ' .
                    'Make sure the website language matches the knowledge base content language.',
            ],
            message: "Website generation initiated for: {$websiteName} (Type: {$websiteType}). " .
                     "Please generate the complete website code following the provided instructions. " .
                     "ウェブサイト生成を開始します。指示に従ってコードを生成してください。"
        );
    }

    private function buildGenerationContext(array $arguments, ToolExecutionContext $context): array
    {
        $description = $arguments['description'];
        $colorScheme = $arguments['color_scheme'] ?? null;
        $includeSections = $arguments['include_sections'] ?? [];
        $websiteType = $arguments['website_type'] ?? 'landing_page';
        $websiteName = $arguments['website_name'] ?? 'Website';

        // Start building the prompt
        $userRequest = "Create a {$websiteType} website";
        if ($websiteName) {
            $userRequest .= " called '{$websiteName}'";
        }
        $userRequest .= ".\n\n";
        $userRequest .= "Description: {$description}\n\n";

        if ($colorScheme) {
            $userRequest .= "Color Scheme: {$colorScheme}\n\n";
        }

        if (!empty($includeSections)) {
            $userRequest .= "Required Sections: " . implode(', ', $includeSections) . "\n\n";
        }

        // Get RAG context if available
        $knowledgeContent = null;
        $application = $context->application;

        if ($application->type === 'agent') {
            $application->loadMissing('knowledge');
            $embeddedKnowledgeIds = $application->knowledge
                ->where('is_embedded', true)
                ->pluck('id')
                ->toArray();

            if (!empty($embeddedKnowledgeIds)) {
                $knowledgeContent = $this->ragService->search(
                    $embeddedKnowledgeIds,
                    $description,
                    [
                        'provider' => $context->provider->provider,
                        'api_key' => $context->provider->api_key,
                    ],
                    true // Skip search, get all content for website generation
                );
            }
        }

        // Build the system prompt with website instructions
        $systemPromptContent = '';
        if ($knowledgeContent) {
            $systemPromptContent = "=== KNOWLEDGE BASE CONTENT (USE THIS DATA FOR WEBSITE) ===\n\n" .
                                  $knowledgeContent .
                                  "\n\n=== END OF KNOWLEDGE BASE CONTENT ===\n\n";
        }
        $systemPromptContent .= $userRequest;

        $fullPrompt = $this->promptBuilder->buildSystemPrompt($systemPromptContent);

        return [
            'prompt' => $fullPrompt,
            'knowledge_content' => $knowledgeContent,
        ];
    }

    public function isAvailable(ToolExecutionContext $context): bool
    {
        // Only available for agent-type applications
        return $context->application->type === 'agent';
    }
}
