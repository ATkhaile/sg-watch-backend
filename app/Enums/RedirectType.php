<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class RedirectType extends Enum
{
    public const APP_PAGE = 'app_page';
    public const VIDEO = 'video';
    public const IMAGE = 'image';
    public const WEBVIEW = 'webview';
    
    public static function getStringValues(): array
    {
        return self::getValues();
    }
}
