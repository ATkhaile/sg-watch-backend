<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AiContextType extends Enum
{

    public const CONVERSATION = 'conversation';
    public const APPLICATION = 'application';
    public const ALL = 'all';

    public static function getAiContextTypes()
    {
        return [
            [
                'key' => self::CONVERSATION,
                'label' => 'このチャット',
                'description' => 'AIアプリは、この会話内のメッセージのみを文脈として参照します。',
            ],
            [
                'key' => self::APPLICATION,
                'label' => '本アプリに属するすべての会話',
                'description' => 'AIアプリは、このアプリに属する会話のすべての情報を取得します。',
            ],

            [
                'key' => self::ALL,
                'label' => '全アプリに属する全会話',
                'description' => 'アプリは、複数のアプリに属するすべての会話から情報を参照します。',
            ],

        ];
    }
}
