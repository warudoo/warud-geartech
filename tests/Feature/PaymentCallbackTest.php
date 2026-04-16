<?php

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;

it('updates an order to paid and deducts stock only once for idempotent callbacks', function () {
    config()->set('services.midtrans.server_key', 'server-key');

    $user = User::factory()->create();
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'stock' => 5,
        'price' => 100000,
    ]);

    $order = Order::query()->create([
        'user_id' => $user->id,
        'order_number' => 'ORD-TEST-0001',
        'status' => OrderStatus::PENDING_PAYMENT,
        'subtotal' => 200000,
        'total' => 200000,
        'customer_name' => $user->name,
        'email' => $user->email,
        'phone' => '08123456789',
        'shipping_address' => 'Sector 7',
        'payment_provider' => 'midtrans',
    ]);

    $order->items()->create([
        'product_id' => $product->id,
        'product_name' => $product->name,
        'sku' => $product->sku,
        'unit_price' => 100000,
        'quantity' => 2,
        'line_total' => 200000,
    ]);

    Payment::query()->create([
        'order_id' => $order->id,
        'provider' => 'midtrans',
        'provider_order_id' => $order->order_number,
        'transaction_status' => 'pending',
    ]);

    $payload = [
        'order_id' => $order->order_number,
        'status_code' => '200',
        'gross_amount' => '200000.00',
        'transaction_status' => 'settlement',
        'transaction_id' => 'tx-123',
        'payment_type' => 'bank_transfer',
    ];
    $payload['signature_key'] = hash('sha512', $payload['order_id'].$payload['status_code'].$payload['gross_amount'].'server-key');

    $this->postJson(route('payments.midtrans.callback'), $payload)
        ->assertOk()
        ->assertJson(['received' => true]);

    $order->refresh();

    expect($order->status)->toBe(OrderStatus::PAID)
        ->and($order->paid_at)->not->toBeNull()
        ->and($order->stock_deducted_at)->not->toBeNull()
        ->and($product->refresh()->stock)->toBe(3);

    $this->postJson(route('payments.midtrans.callback'), $payload)
        ->assertOk();

    expect($product->refresh()->stock)->toBe(3);
});
