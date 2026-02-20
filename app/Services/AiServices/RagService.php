<?php

namespace App\Services\AiServices;

use App\Services\AiServices\Traits\HttpClientTrait;
use Illuminate\Support\Facades\Log;

class RagService
{
    use HttpClientTrait;

    /**
     * Search RAG context for a query
     *
     * @param array $embeddedKnowledgeIds List of knowledge IDs to search
     * @param string $query The search query
     * @param array $providerConfig AI provider configuration
     * @param bool $skipSearch If true, returns all content without semantic search (useful for website generation)
     */
    public function search(array $embeddedKnowledgeIds, string $query, array $providerConfig, bool $skipSearch = false): ?string
    {
        if (empty($embeddedKnowledgeIds)) {
            return null;
        }

        try {
            $payload = [
                'query' => $query,
                'elc_document_ids' => $embeddedKnowledgeIds,
                'skip_search' => $skipSearch,
                'ai_provider' => [
                    'name' => $providerConfig['provider'],
                    'api_key' => $providerConfig['api_key'],
                ],
            ];

            $url = config('services.ai_rag.url', env('AI_RAG_URL')) . '/search';
            $response = $this->httpPost($url, $payload, [], 30);

            if ($response !== false) {
                $data = json_decode($response, true);
                $detail = $data['detail'] ?? null;

                // Handle case where detail is an array (convert to string)
                if (is_array($detail)) {
                    return json_encode($detail, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                }

                return $detail;
            }
        } catch (\Exception $e) {
            Log::error('Failed to get RAG context', [
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Ingest a document into RAG
     */
    public function ingest(string $fileUrl, string $title, int $documentId, array $providerConfig): bool
    {
        try {
            $payload = [
                'url' => $fileUrl,
                'title' => $title,
                'elc_document_id' => $documentId,
                'ai_provider' => [
                    'name' => $providerConfig['provider'],
                    'api_key' => $providerConfig['api_key'],
                ],
            ];
            $url = config('services.ai_rag.url', env('AI_RAG_URL')) . '/ingest';
            // Use httpPostWithValidation to properly check HTTP status codes
            $this->httpPostWithValidation($url, $payload, [], 300);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to ingest document to RAG', [
                'document_id' => $documentId,
                'file_url' => $fileUrl,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Build system prompt with RAG context
     */
    public function buildSystemPromptWithContext(string $originalPrompt, string $ragContext): string
    {
        $contextSection = "以下は関連する知識ベースからの情報です。この情報を参考にして回答してください：\n\n" . $ragContext . "\n\n---\n\n";

        if (empty($originalPrompt)) {
            return $contextSection . "上記の情報を参考にして、ユーザーの質問に正確に回答してください。";
        }

        return $contextSection . $originalPrompt;
    }
}
