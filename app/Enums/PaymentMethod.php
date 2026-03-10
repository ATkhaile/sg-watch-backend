<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PaymentMethod extends Enum
{
    public const BANK_TRANSFER = 'bank_transfer';
    public const COD = 'cod';
    public const CASH = 'cash';
    public const DEPOSIT_TRANSFER = 'deposit_transfer';
    public const STRIPE = 'stripe';
}
