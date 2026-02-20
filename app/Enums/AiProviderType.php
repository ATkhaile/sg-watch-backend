<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AiProviderType extends Enum
{

    public const OPENAI = 'openai';
    public const DEEPSEEK = 'deepseek';
    public const GEMINI = 'gemini';
    
    public static function getAiProviderTypes()
    {
        return [
            [
                'key' => self::OPENAI,
                'label' => 'OpenAI',
                'description' => 'OpenAIが提供するAIモデルサービス',
                'icon' => '/images/ai-providers/openai.svg',
                'disabled' => false,
            ],
            [
                'key' => self::DEEPSEEK,
                'label' => 'DeepSeek',
                'description' => 'DeepSeekが提供するモデル（deepseek-chat、deepseek-coderなど）',
                'icon' => '/images/ai-providers/deepseek.svg',
                'disabled' => true,
            ],
            [
                'key' => self::GEMINI,
                'label' => 'Gemini',
                'description' => 'GoogleのGeminiモデル',
                'icon' => '/images/ai-providers/gemini.svg',
                'disabled' => true,
            ]
        ];
    }
}
