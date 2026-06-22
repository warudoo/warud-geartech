<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        protected StockService $stockService,
    ) {
    }

    public function transitionToStatus(Order $order, OrderStatus $target, string $source = 'system', array $metadata = []): Order
    {
        return DB::transaction(function () use ($order, $target, $source, $metadata) {
            $lockedOrder = Order::query()->with('items.product', 'user')->lockForUpdate()->findOrFail($order->id);

            if ($lockedOrder->status === $target) {
                if ($target === OrderStatus::PAID && ! $lockedOrder->stock_deducted_at) {
                    $this->stockService->deductForPaidOrder($lockedOrder);
                }

                return $lockedOrder->refresh()->load('items.product', 'user', 'payment');
            }

            if (! $this->canTransition($lockedOrder->status, $target, $source)) {
                throw ValidationException::withMessages([
                    'status' => 'Invalid order status transition.',
                ]);
            }

            $attributes = [
                'status' => $target,
                'payment_payload' => array_merge($lockedOrder->payment_payload ?? [], $metadata),
            ];

            match ($target) {
                OrderStatus::PAID => $attributes['paid_at'] = $lockedOrder->paid_at ?? now(),
                OrderStatus::SHIPPED => $attributes['shipped_at'] = $lockedOrder->shipped_at ?? now(),
                OrderStatus::COMPLETED => $attributes['completed_at'] = $lockedOrder->completed_at ?? now(),
                OrderStatus::CANCELLED => $attributes['cancelled_at'] = $lockedOrder->cancelled_at ?? now(),
                OrderStatus::EXPIRED => $attributes['expired_at'] = $lockedOrder->expired_at ?? now(),
                default => null,
            };

            $lockedOrder->forceFill($attributes)->save();

            if ($target === OrderStatus::PAID) {
                $this->stockService->deductForPaidOrder($lockedOrder);
            }

            return $lockedOrder->refresh()->load('items.product', 'user', 'payment');
        });
    }

    protected function canTransition(OrderStatus $from, OrderStatus $to, string $source): bool
    {
        return match ($source) {
            'payment_callback' => match ($from) {
                OrderStatus::PENDING_PAYMENT => in_array($to, [
                    OrderStatus::PENDING_PAYMENT,
                    OrderStatus::PAID,
                    OrderStatus::CANCELLED,
                    OrderStatus::EXPIRED,
                ], true),
                OrderStatus::PAID => $to === OrderStatus::PAID,
                OrderStatus::CANCELLED => $to === OrderStatus::CANCELLED,
                OrderStatus::EXPIRED => $to === OrderStatus::EXPIRED,
                default => false,
            },
            'admin' => match ($from) {
                OrderStatus::PENDING_PAYMENT => $to === OrderStatus::CANCELLED,
                OrderStatus::PAID => in_array($to, [OrderStatus::PROCESSING, OrderStatus::CANCELLED], true),
                OrderStatus::PROCESSING => in_array($to, [OrderStatus::SHIPPED, OrderStatus::CANCELLED], true),
                OrderStatus::SHIPPED => $to === OrderStatus::COMPLETED,
                default => false,
            },
            default => $from === $to,
        };
    }
}
