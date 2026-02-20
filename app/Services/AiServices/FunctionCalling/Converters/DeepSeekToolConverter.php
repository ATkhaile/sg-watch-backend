<?php

namespace App\Services\AiServices\FunctionCalling\Converters;

/**
 * DeepSeek uses OpenAI-compatible format for function calling
 */
class DeepSeekToolConverter extends OpenAiToolConverter
{
    // DeepSeek is fully OpenAI-compatible for function calling
    // No need to override any methods currently

    // If DeepSeek introduces specific differences in the future,
    // override the relevant methods here
}
