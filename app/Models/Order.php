<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'order_number',
    'status',
    'subtotal',
    'total',
    'customer_name',
    'email',
    'phone',
    'shipping_address',
    'notes',
    'payment_provider',
    'paid_at',
    'expired_at',
    'shipped_at',
    'completed_at',
    'cancelled_at',
    'stock_deducted_at',
    'payment_payload',
])]
class Order extends Model
{
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_at' => 'datetime',
            'expired_at' => 'datetime',
            'shipped_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'stock_deducted_at' => 'datetime',
            'payment_payload' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function canBePaid(): bool
    {
        return $this->status === OrderStatus::PENDING_PAYMENT;
    }
}
