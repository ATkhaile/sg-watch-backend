<?php

namespace App\Services\AiServices\FunctionCalling;

class FunctionCallingResult
{
    public function __construct(
        public readonly bool $hasToolCalls,
        public readonly bool $isComplete,
        public readonly array $toolCalls,
        public readonly array $results,
        public readonly array $followUpMessages,
    ) {}

    /**
     * Get all generated files from tool results
     */
    public function getGeneratedFiles(): array
    {
        $files = [];
        foreach ($this->results as $result) {
            if ($result instanceof ToolResult && !empty($result->generatedFiles)) {
                $files = array_merge($files, $result->generatedFiles);
            }
        }
        return $files;
    }
}
