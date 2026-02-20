<?php

namespace App\Services\AiServices;

use App\Enums\AiContextType;
use App\Models\AiAppMessage;
use App\Models\AiMessageConversation;
use Illuminate\Support\Facades\Log;

class ConversationSummaryService
{
    public function __construct(
        private OpenAiService $openAiService,
        private DeepSeekService $deepSeekService,
        private GeminiService $geminiService,
    ) {}

    /**
     * Get context messages based on context type
     *
     * @param string $contextType One of AiContextType constants
     * @param int $conversationId Current conversation ID
     * @param int $appId Current application ID
     * @param array $providerConfig Provider configuration for summarization
     * @return array Context messages to include in the prompt
     */
    public function getContextMessages(
        string $contextType,
        int $conversationId,
        int $appId,
        array $providerConfig
    ): array {
        return match ($contextType) {
            AiContextType::CONVERSATION => $this->getConversationContext($conversationId),
            AiContextType::APPLICATION => $this->getApplicationContext($conversationId, $appId, $providerConfig),
            AiContextType::ALL => $this->getAllContext($conversationId, $providerConfig),
            default => $this->getConversationContext($conversationId),
        };
    }

    /**
     * Get messages from current conversation only (default behavior)
     */
    private function getConversationContext(int $conversationId): array
    {
        $messages = AiAppMessage::with('attachments')
            ->where('conversation_id', $conversationId)
            ->where('status', 'success')
            ->orderBy('created_at', 'asc')
            ->get();

        return $this->formatMessagesForContext($messages);
    }

    /**
     * Get context from all conversations in this application
     */
    private function getApplicationContext(int $currentConversationId, int $appId, array $providerConfig): array
    {
        $context = [];

        // Get summaries from other conversations in the same app
        $otherConversations = AiMessageConversation::where('app_id', $appId)
            ->where('id', '!=', $currentConversationId)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($otherConversations as $conversation) {
            $summary = $this->getOrCreateSummary($conversation, $providerConfig);
            if ($summary) {
                $context[] = [
                    'type' => 'conversation_summary',
                    'conversation_id' => $conversation->id,
                    'conversation_name' => $conversation->name,
                    'summary' => $summary,
                ];
            }
        }

        // Get full messages from current conversation
        $currentMessages = $this->getConversationContext($currentConversationId);

        return [
            'summaries' => $context,
            'current_messages' => $currentMessages,
        ];
    }

    /**
     * Get context from all conversations across all apps
     */
    private function getAllContext(int $currentConversationId, array $providerConfig): array
    {
        $context = [];

        // Get summaries from all other conversations
        $otherConversations = AiMessageConversation::where('id', '!=', $currentConversationId)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($otherConversations as $conversation) {
            $summary = $this->getOrCreateSummary($conversation, $providerConfig);
            if ($summary) {
                $context[] = [
                    'type' => 'conversation_summary',
                    'conversation_id' => $conversation->id,
                    'conversation_name' => $conversation->name,
                    'app_id' => $conversation->app_id,
                    'summary' => $summary,
                ];
            }
        }

        // Get full messages from current conversation
        $currentMessages = $this->getConversationContext($currentConversationId);

        return [
            'summaries' => $context,
            'current_messages' => $currentMessages,
        ];
    }

    /**
     * Get or create summary for a conversation
     */
    private function getOrCreateSummary(AiMessageConversation $conversation, array $providerConfig): ?string
    {
        $messageCount = $conversation->messages()->where('status', 'success')->count();

        // No messages, no summary needed
        if ($messageCount === 0) {
            return null;
        }

        // Check if summary is up to date
        if (
            $conversation->summary !== null &&
            $conversation->summary_messages_count !== null &&
            $conversation->summary_messages_count >= $messageCount
        ) {
            return $conversation->summary;
        }

        // Need to create or update summary
        return $this->createSummary($conversation, $providerConfig);
    }

    /**
     * Create summary for a conversation using AI
     */
    private function createSummary(AiMessageConversation $conversation, array $providerConfig): ?string
    {
        $messages = AiAppMessage::where('conversation_id', $conversation->id)
            ->where('status', 'success')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($messages->isEmpty()) {
            return null;
        }

        $messageCount = $messages->count();

        // Build conversation text for summarization
        $conversationText = '';
        foreach ($messages as $msg) {
            $conversationText .= "User: {$msg->message}\n";
            if ($msg->answer) {
                $conversationText .= "Assistant: {$msg->answer}\n";
            }
            $conversationText .= "\n";
        }

        // Limit text length to avoid token issues
        $maxLength = 10000;
        if (mb_strlen($conversationText) > $maxLength) {
            $conversationText = mb_substr($conversationText, 0, $maxLength) . '...';
        }

        $summaryPrompt = [
            [
                'role' => 'system',
                'content' => 'You are a helpful assistant that summarizes conversations. Create a concise summary that captures the main topics, key decisions, and important information discussed. The summary should be useful as context for future conversations. Write the summary in the same language as the conversation. Keep it under 500 words.',
            ],
            [
                'role' => 'user',
                'content' => "Please summarize the following conversation:\n\n{$conversationText}",
            ],
        ];

        try {
            $config = [
                'apiKey' => $providerConfig['api_key'],
                'baseUrl' => $providerConfig['base_url'] ?? null,
                'model' => $providerConfig['model'] ?? 'gpt-4o-mini',
                'temperature' => 0.3,
                'maxTokens' => 1000,
            ];

            $response = match ($providerConfig['provider']) {
                'openai' => $this->openAiService->chat($summaryPrompt, $config),
                'deepseek' => $this->deepSeekService->chat($summaryPrompt, $config),
                'gemini' => $this->geminiService->chat($summaryPrompt, $config),
                default => $this->openAiService->chat($summaryPrompt, $config),
            };

            $summary = $response['answer'] ?? null;

            if ($summary) {
                $conversation->summary = $summary;
                $conversation->summary_messages_count = $messageCount;
                $conversation->save();

                Log::info('Conversation summary created', [
                    'conversation_id' => $conversation->id,
                    'message_count' => $messageCount,
                ]);
            }

            return $summary;
        } catch (\Exception $e) {
            Log::error('Failed to create conversation summary', [
                'conversation_id' => $conversation->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Format messages for context array
     */
    private function formatMessagesForContext($messages): array
    {
        $formatted = [];

        foreach ($messages as $msg) {
            $previousFiles = null;
            if ($msg->attachments && $msg->attachments->count() > 0) {
                $previousFiles = $msg->attachments->map(function ($attachment) {
                    return [
                        'name' => $attachment->original_name,
                        'mime_type' => $attachment->mime_type,
                        'url' => $attachment->file_url,
                        'storage_path' => $attachment->file_path,
                    ];
                })->toArray();
            }

            $formatted[] = [
                'role' => 'user',
                'content' => $msg->message,
                'files' => $previousFiles,
            ];

            if ($msg->answer) {
                $formatted[] = [
                    'role' => 'assistant',
                    'content' => $msg->answer,
                ];
            }
        }

        return $formatted;
    }

    /**
     * Build system prompt with context summaries
     */
    public function buildSystemPromptWithSummaries(string $basePrompt, array $summaries): string
    {
        if (empty($summaries)) {
            return $basePrompt;
        }

        $summaryText = "=== PREVIOUS CONVERSATION SUMMARIES ===\n\n";

        foreach ($summaries as $summary) {
            $conversationName = $summary['conversation_name'] ?? 'Unnamed conversation';
            $summaryText .= "--- {$conversationName} ---\n";
            $summaryText .= $summary['summary'] . "\n\n";
        }

        $summaryText .= "=== END OF SUMMARIES ===\n\n";

        return $summaryText . $basePrompt;
    }
}
