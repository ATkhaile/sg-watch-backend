<?php

namespace App\Services\AiServices\FunctionCalling\Tools;

use App\Services\AiServices\FunctionCalling\ToolExecutionContext;
use App\Services\AiServices\FunctionCalling\ToolResult;
use App\Services\AiServices\RagService;

class SearchKnowledgeTool extends AbstractTool
{
    public function __construct(
        private RagService $ragService,
    ) {}

    public function getName(): string
    {
        return 'search_knowledge';
    }

    public function getDescription(): string
    {
        return 'Search the knowledge base for relevant information. Use this tool when you need to ' .
               'find specific facts, data, or information from the uploaded documents and knowledge base. ' .
               'ナレッジベースから関連情報を検索します。';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'query' => [
                    'type' => 'string',
                    'description' => 'The search query to find relevant information in the knowledge base. Must be 2-1000 characters. When get_all_content is true, use a descriptive query like "all documents" or "全てのドキュメント" instead of wildcards. (検索クエリ、2〜1000文字)',
                ],
                'get_all_content' => [
                    'type' => 'boolean',
                    'description' => 'Set to true to retrieve ALL content from knowledge base without semantic filtering. When true, the query parameter is still required but will be ignored for filtering - use any descriptive text like "retrieve all content". Useful for comprehensive data retrieval, website generation, or when user asks for all/complete content. (全コンテンツを取得する場合はtrue、クエリはフィルタリングに使用されません)',
                ],
            ],
            'required' => ['query'],
        ];
    }

    public function execute(array $arguments, ToolExecutionContext $context): ToolResult
    {
        $query = $arguments['query'];
        $getAllContent = $arguments['get_all_content'] ?? false;

        // Ensure query meets minimum length requirement (2 characters)
        // If query is too short (like "*" or single char), use a default query
        if (strlen($query) < 2) {
            $query = $getAllContent ? 'retrieve all content' : 'search all documents';
        }

        $application = $context->application;
        $application->loadMissing('knowledge');

        $embeddedKnowledgeIds = $application->knowledge
            ->where('is_embedded', true)
            ->pluck('id')
            ->toArray();

        if (empty($embeddedKnowledgeIds)) {
            return $this->error('No knowledge base documents available for this application. このアプリケーションにはナレッジベースドキュメントがありません。');
        }

        try {
            $ragContext = $this->ragService->search(
                $embeddedKnowledgeIds,
                $query,
                [
                    'provider' => $context->provider->provider,
                    'api_key' => $context->provider->api_key,
                ],
                $getAllContent
            );

            if (empty($ragContext)) {
                return $this->success(
                    data: null,
                    message: 'No relevant information found in the knowledge base for this query. 関連情報が見つかりませんでした。'
                );
            }

            return $this->success(
                data: $ragContext,
                message: 'Found relevant information from the knowledge base. ナレッジベースから関連情報を取得しました。'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to search knowledge base: ' . $e->getMessage());
        }
    }

    public function isAvailable(ToolExecutionContext $context): bool
    {
        // Only available for agent-type applications with knowledge base
        if ($context->application->type !== 'agent') {
            return false;
        }

        $context->application->loadMissing('knowledge');
        return $context->application->knowledge
            ->where('is_embedded', true)
            ->isNotEmpty();
    }
}
