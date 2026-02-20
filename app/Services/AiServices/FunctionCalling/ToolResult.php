<?php

namespace App\Services\AiServices\FunctionCalling;

class ToolResult
{
    public function __construct(
        public readonly bool $success,
        public readonly mixed $data = null,
        public readonly ?string $message = null,
        public readonly ?array $generatedFiles = null,
        public readonly ?array $metadata = null,
    ) {}

    /**
     * Convert result to string for AI consumption
     */
    public function toAiResponse(): string
    {
        if (!$this->success) {
            return json_encode([
                'error' => true,
                'message' => $this->message ?? 'Tool execution failed',
            ], JSON_UNESCAPED_UNICODE);
        }

        return json_encode([
            'success' => true,
            'result' => $this->data,
            'message' => $this->message,
        ], JSON_UNESCAPED_UNICODE);
    }
}
