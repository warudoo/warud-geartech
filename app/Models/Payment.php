<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'order_id',
    'provider',
    'provider_order_id',
    'snap_token',
    'snap_redirect_url',
    'transaction_id',
    'transaction_status',
    'status_code',
    'payment_type',
    'fraud_status',
    'va_numbers',
    'raw_payload',
    'paid_at',
    'expires_at',
    'last_callback_at',
])]
class Payment extends Model
{
    protected function casts(): array
    {
        return [
            'va_numbers' => 'array',
            'raw_payload' => 'array',
            'paid_at' => 'datetime',
            'expires_at' => 'datetime',
            'last_callback_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
