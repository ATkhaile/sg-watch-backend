<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class DetectSqlInjection extends Enum
{
    // Data Manipulation Language (DML) - Can modify data
    public const INSERT_INTO = 'insert into';
    public const UPDATE = 'update';
    public const DELETE_FROM = 'delete from';
    public const TRUNCATE = 'truncate';
    public const DROP = 'drop';
    public const ALTER = 'alter';
    public const CREATE = 'create';
    public const RENAME = 'rename';

    // MySQL specific dangerous commands
    public const LOAD_FILE = 'load_file';
    public const INTO_OUTFILE = 'into outfile';
    public const INTO_DUMPFILE = 'into dumpfile';

    // Common injection patterns
    public const OR_1_EQUALS_1 = 'or 1=1';
    public const OR_1_EQUALS_1_HASH = 'or 1=1#';
    public const OR_1_EQUALS_1_DASH = 'or 1=1--';
    public const OR_TRUE = 'or true';
    public const OR_FALSE = 'or false';

    // Comment patterns
    public const DOUBLE_DASH = '--';
    public const HASH = '#';
    public const SLASH_STAR = '/*';
    public const STAR_SLASH = '*/';

    // Stacked queries
    public const SEMICOLON = ';';
    public const SEMICOLON_INSERT = ';insert';
    public const SEMICOLON_UPDATE = ';update';
    public const SEMICOLON_DELETE = ';delete';
    public const SEMICOLON_DROP = ';drop';
    public const SEMICOLON_CREATE = ';create';
}
