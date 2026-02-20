<?php

namespace App\Enums\Aiship;

use BenSampo\Enum\Enum;

final class TaxCategory extends Enum
{
    public const TAXABLE = 'taxable';
    public const NON_TAXABLE = 'non_taxable';
    public const REDUCED = 'reduced';
}
