<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ScheduleType extends Enum
{
    public const LESSON_INSTRUCTOR_NOT_ALLOWED = 0;
    public const LESSON_AVAILABLE = 1;
    public const INSTRUCTOR_AVAILABLE = 2;
    public const LESSON_INSTRUCTOR_AVAILABLE = 3;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::LESSON_INSTRUCTOR_NOT_ALLOWED:
                return '整・イ両方不可';
                break;
            case self::LESSON_AVAILABLE:
                return '通常予約・ゴルフ整体可能';
                break;
            case self::INSTRUCTOR_AVAILABLE:
                return 'インストラクター可能';
                break;
            case self::LESSON_INSTRUCTOR_AVAILABLE:
                return '整・イ両方可能';
                break;
            default:
                return '';
                break;
        }
    }
    public static function parseArray()
    {
        $arr = [];
        foreach (self::getValues() as $value) {
            $arr[] = [
                'id' => $value,
                'label' => self::getDescription($value)
            ];
        }
        return $arr;
    }

    public static function getStringValues(): array
    {
        return [
            self::LESSON_INSTRUCTOR_NOT_ALLOWED => 'lesson_instructor_not_allowed',
            self::LESSON_AVAILABLE => 'lesson_available',
            self::INSTRUCTOR_AVAILABLE => 'instructor_available',
            self::LESSON_INSTRUCTOR_AVAILABLE => 'lesson_instructor_available',
        ];
    }
}
