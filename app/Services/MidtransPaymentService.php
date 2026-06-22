<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MidtransPaymentService
{
    public function __construct(
        protected OrderService $orderService,
    ) {
    }

    public function createOrRefreshTransaction(Order $order): Payment
    {
        if (! $order->canBePaid()) {
            throw new RuntimeException('This order is no longer payable.');
        }

        $existing = Payment::query()
            ->where('order_id', $order->id)
            ->latest()
            ->first();

        if ($existing?->snap_redirect_url && $existing->transaction_status !== 'expire') {
            return $existing;
        }

        $serverKey = config('services.midtrans.server_key');

        if (! $serverKey) {
            throw new RuntimeException('Midtrans server key is not configured.');
        }

        $response = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->post(config('services.midtrans.snap_url').'/transactions', [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) round((float) $order->total),
                ],
                'customer_details' => [
                    'first_name' => $order->customer_name,
                    'email' => $order->email,
                    'phone' => $order->phone,
                    'billing_address' => [
                        'address' => $order->shipping_address,
                    ],
                    'shipping_address' => [
                        'address' => $order->shipping_address,
                    ],
                ],
                'item_details' => $order->items->map(fn ($item) => [
                    'id' => $item->product_id ?? $item->id,
                    'price' => (int) round((float) $item->unit_price),
                    'quantity' => $item->quantity,
                    'name' => str($item->product_name)->limit(50, '')->value(),
                ])->values()->all(),
                'callbacks' => [
                    'finish' => route('orders.show', $order->order_number),
                ],
            ]);

        if ($response->failed()) {
            throw new RequestException($response);
        }

        $payload = $response->json();

        return DB::transaction(function () use ($order, $payload) {
            return Payment::query()->updateOrCreate(
                ['provider_order_id' => $order->order_number],
                [
                    'order_id' => $order->id,
                    'provider' => 'midtrans',
                    'snap_token' => $payload['token'] ?? null,
                    'snap_redirect_url' => $payload['redirect_url'] ?? null,
                    'transaction_status' => 'pending',
                    'raw_payload' => $payload,
                ]
            );
        });
    }

    public function handleCallback(array $payload): Order
    {
        $this->ensureValidSignature($payload);

        return DB::transaction(function () use ($payload) {
            $order = Order::query()
                ->where('order_number', $payload['order_id'])
                ->firstOrFail();

            $payment = Payment::query()->lockForUpdate()->firstOrCreate(
                ['provider_order_id' => $payload['order_id']],
                [
                    'order_id' => $order->id,
                    'provider' => 'midtrans',
                ],
            );

            $payment->forceFill([
                'order_id' => $order->id,
                'transaction_id' => $payload['transaction_id'] ?? $payment->transaction_id,
                'transaction_status' => $payload['transaction_status'] ?? $payment->transaction_status,
                'status_code' => $payload['status_code'] ?? $payment->status_code,
                'payment_type' => $payload['payment_type'] ?? $payment->payment_type,
                'fraud_status' => $payload['fraud_status'] ?? $payment->fraud_status,
                'va_numbers' => $payload['va_numbers'] ?? $payment->va_numbers,
                'raw_payload' => $payload,
                'last_callback_at' => now(),
                'paid_at' => in_array($payload['transaction_status'] ?? null, ['settlement', 'capture'], true)
                    ? now()
                    : $payment->paid_at,
            ])->save();

            $targetStatus = $this->mapCallbackStatus($payload);

            if ($targetStatus) {
                $order = $this->orderService->transitionToStatus($order, $targetStatus, 'payment_callback', [
                    'midtrans_transaction_status' => $payload['transaction_status'] ?? null,
                    'midtrans_payment_type' => $payload['payment_type'] ?? null,
                ]);
            }

            return $order->refresh()->load('items.product', 'user', 'payment');
        });
    }

    protected function ensureValidSignature(array $payload): void
    {
        $serverKey = config('services.midtrans.server_key');

        if (! $serverKey) {
            throw new RuntimeException('Midtrans server key is not configured.');
        }

        $expected = hash('sha512', ($payload['order_id'] ?? '').($payload['status_code'] ?? '').($payload['gross_amount'] ?? '').$serverKey);

        if (($payload['signature_key'] ?? null) !== $expected) {
            throw new RuntimeException('Invalid Midtrans signature.');
        }
    }

    protected function mapCallbackStatus(array $payload): ?OrderStatus
    {
        return match ($payload['transaction_status'] ?? null) {
            'settlement' => OrderStatus::PAID,
            'capture' => ($payload['fraud_status'] ?? 'accept') === 'accept'
                ? OrderStatus::PAID
                : OrderStatus::PENDING_PAYMENT,
            'pending' => OrderStatus::PENDING_PAYMENT,
            'expire' => OrderStatus::EXPIRED,
            'cancel', 'deny', 'failure' => OrderStatus::CANCELLED,
            default => null,
        };
    }
}
