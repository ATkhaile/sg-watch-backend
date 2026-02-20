<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class WorkManagementStatus extends Enum
{
    public const DRAFT = 'draft';
    public const VIEWING = 'viewing';
    public const PROCESSED = 'processed';
    public const ARCHIVED = 'archived';

    public static function getDescriptionJp($value): string
    {
        switch ($value) {
            case self::DRAFT:
                return 'ドラフト';
            case self::VIEWING:
                return '表示中';
            case self::PROCESSED:
                return '処理済み';
            case self::ARCHIVED:
                return 'アーカイブ済み';
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
            ];
        }
        return $arr;
    }

    public static function getStringValues(): array
    {
        return ['draft', 'viewing', 'processed', 'archived'];
    }

    public static function fromString(string $status): ?int
    {
        $map = array_combine(self::getStringValues(), self::getValues());
        return $map[$status] ?? null;
    }
}
