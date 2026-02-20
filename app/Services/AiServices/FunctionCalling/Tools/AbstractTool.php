<?php

namespace App\Services\AiServices\FunctionCalling\Tools;

use App\Services\AiServices\FunctionCalling\ToolExecutionContext;
use App\Services\AiServices\FunctionCalling\ToolResult;

abstract class AbstractTool
{
    /**
     * Get the unique name of the tool
     */
    abstract public function getName(): string;

    /**
     * Get human-readable description for the AI
     */
    abstract public function getDescription(): string;

    /**
     * Get JSON Schema for tool parameters
     * @return array{type: string, properties: array, required?: array}
     */
    abstract public function getParametersSchema(): array;

    /**
     * Execute the tool with given arguments
     * @param array $arguments Validated arguments from AI
     * @param ToolExecutionContext $context Execution context (conversation, user, etc.)
     * @return ToolResult
     */
    abstract public function execute(array $arguments, ToolExecutionContext $context): ToolResult;

    /**
     * Default implementation - tool is always available
     * Override in subclasses for conditional availability
     */
    public function isAvailable(ToolExecutionContext $context): bool
    {
        return true;
    }

    /**
     * Create a success result
     */
    protected function success(mixed $data, ?string $message = null, ?array $generatedFiles = null, ?array $metadata = null): ToolResult
    {
        return new ToolResult(
            success: true,
            data: $data,
            message: $message,
            generatedFiles: $generatedFiles,
            metadata: $metadata
        );
    }

    /**
     * Create an error result
     */
    protected function error(string $message, ?array $details = null): ToolResult
    {
        return new ToolResult(
            success: false,
            data: $details,
            message: $message
        );
    }
}
