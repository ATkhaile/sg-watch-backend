<?php

namespace App\Services\AiServices\FunctionCalling;

use App\Enums\AiProviderType;
use App\Services\AiServices\FunctionCalling\Converters\AbstractToolConverter;
use App\Services\AiServices\FunctionCalling\Converters\OpenAiToolConverter;
use App\Services\AiServices\FunctionCalling\Converters\GeminiToolConverter;
use App\Services\AiServices\FunctionCalling\Converters\DeepSeekToolConverter;

class ToolConverterFactory
{
    /**
     * Create a converter for the specified provider type
     */
    public function make(string $providerType): AbstractToolConverter
    {
        return match ($providerType) {
            AiProviderType::OPENAI => new OpenAiToolConverter(),
            AiProviderType::GEMINI => new GeminiToolConverter(),
            AiProviderType::DEEPSEEK => new DeepSeekToolConverter(),
            default => throw new \InvalidArgumentException("Unsupported provider for function calling: {$providerType}"),
        };
    }
}
