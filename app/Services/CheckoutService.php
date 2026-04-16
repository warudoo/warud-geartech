<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function __construct(
        protected CartService $cartService,
    ) {
    }

    public function createPendingOrder(User $user, array $payload): Order
    {
        $items = $this->cartService->items($user);

        if ($items->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => 'Your cart is empty.',
            ]);
        }

        $this->validateCartAgainstStock($items);

        return DB::transaction(function () use ($user, $payload, $items) {
            $subtotal = $items->sum(fn ($item) => $item->lineTotal());

            $order = Order::query()->create([
                'user_id' => $user->id,
                'order_number' => $this->generateOrderNumber(),
                'status' => OrderStatus::PENDING_PAYMENT,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'customer_name' => $payload['customer_name'],
                'email' => $payload['email'],
                'phone' => $payload['phone'],
                'shipping_address' => $payload['shipping_address'],
                'notes' => $payload['notes'] ?? null,
                'payment_provider' => 'midtrans',
            ]);

            $items->each(function ($item) use ($order): void {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'sku' => $item->product->sku,
                    'unit_price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'line_total' => $item->lineTotal(),
                ]);
            });

            $this->cartService->clear($user);

            return $order->load('items.product', 'user');
        });
    }

    protected function validateCartAgainstStock(Collection $items): void
    {
        $errors = [];

        foreach ($items as $item) {
            if (! $item->product || ! $item->product->is_active) {
                $productName = $item->product?->name ?? 'A product';
                $errors['cart'][] = "{$productName} is no longer available.";
                continue;
            }

            if ($item->product->stock < $item->quantity) {
                $errors['cart'][] = "{$item->product->name} only has {$item->product->stock} unit(s) left.";
            }
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }

    protected function generateOrderNumber(): string
    {
        do {
            $candidate = 'ORD-'.now()->format('YmdHis').'-'.Str::upper(Str::random(6));
        } while (Order::query()->where('order_number', $candidate)->exists());

        return $candidate;
    }
}
