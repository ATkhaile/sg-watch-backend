<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ActivationCode extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'activation_codes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'plan_id',
        'product_id',
        'payment_trigger_id',
        'code',
        'stripe_session_id',
        'stripe_payment_link_id',
        'invoice_url',
        'customer_email',
        'purchase_type',
        'is_used',
        'expires_at',
        'activated_at'
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
        'activated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the plan associated with the activation code.
     */
    public function plan()
    {
        return $this->belongsTo(\App\Models\Plan::class, 'plan_id', 'plan_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }

    public function paymentTrigger()
    {
        return $this->belongsTo(\App\Models\PaymentTrigger::class, 'payment_trigger_id');
    }

    /**
     * Check if this is a product purchase activation code
     */
    public function isProductPurchase(): bool
    {
        return $this->purchase_type === 'product';
    }

    /**
     * Check if this is a plan purchase activation code
     */
    public function isPlanPurchase(): bool
    {
        return $this->purchase_type === 'plan';
    }

    public static function generateCode(): string
    {
        do {
            $code = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 16));
            $exists = self::where('code', $code)->exists();
        } while ($exists);

        return $code;
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return !$this->is_used && !$this->isExpired();
    }
}
