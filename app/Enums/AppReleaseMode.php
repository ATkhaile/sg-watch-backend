<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AppReleaseMode extends Enum
{
    public const DEBUG   = 'debug';
    public const STAGE   = 'stage';
    public const SALES   = 'sales';
    public const PRODUCT = 'product';
    public const ADMIN   = 'admin';

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::DEBUG:
                return 'Debug';
            case self::STAGE:
                return 'Stage';
            case self::SALES:
                return 'Sales';
            case self::PRODUCT:
                return 'Product';
            case self::ADMIN:
                return 'Admin';
            default:
                return '';
        }
    }

    public static function getDescriptionJp($value): string
    {
        switch ($value) {
            case self::DEBUG:
                return 'デバッグ';
            case self::STAGE:
                return 'ステージ';
            case self::SALES:
                return 'セールス';
            case self::PRODUCT:
                return 'プロダクト';
            case self::ADMIN:
                return '管理';
            default:
                return '';
        }
    }

    public static function parseArray(): array
    {
        $arr = [];
        foreach (self::getValues() as $value) {
            $arr[] = [
                'id'    => $value,
                'label' => self::getDescription($value),
            ];
        }
        return $arr;
    }

    public static function getStringValues(): array
    {
        return [self::DEBUG, self::STAGE, self::SALES, self::PRODUCT, self::ADMIN];
    }

    public static function fromString(string $mode): ?int
    {
        $map = array_combine(self::getStringValues(), self::getValues());
        return $map[$mode] ?? null;
    }
}
