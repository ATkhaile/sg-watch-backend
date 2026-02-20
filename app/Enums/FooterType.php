<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class FooterType extends Enum
{
    public const CHAT = 'chat';
    public const WORK = 'work';
    public const WEBVIEW = 'webview';

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::CHAT:
                return 'Chat';
            case self::WORK:
                return 'Works';
            case self::WEBVIEW:
                return 'Webview';
            default:
                return '';
        }
    }

    public static function getDescriptionJp($value): string
    {
        switch ($value) {
            case self::CHAT:
                return '①チャット';
            case self::WORK:
                return '②ワークス';
            case self::WEBVIEW:
                return '③Webview';
            default:
                return '';
        }
    }

    public static function parseArray(): array
    {
        $arr = [];
        foreach (self::getValues() as $value) {
            $arr[] = [
                'id' => $value,
                'label' => self::getDescription($value),
                'label_jp' => self::getDescriptionJp($value)
            ];
        }
        return $arr;
    }

    public static function getStringValues(): array
    {
        return ['chat', 'work', 'webview'];
    }

    public static function fromString(string $status): ?string
    {
        $map = array_combine(self::getStringValues(), self::getValues());
        return $map[$status] ?? null;
    }
}