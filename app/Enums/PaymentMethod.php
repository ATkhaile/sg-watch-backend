<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PaymentMethod extends Enum
{
    public const COD = 'cod';
    public const BANK_TRANSFER = 'bank_transfer';
    public const CREDIT_CARD = 'credit_card';
    public const STRIPE = 'stripe';
}
