<?php

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Http;

it('creates a pending order and keeps stock unchanged until payment confirmation', function () {
    config()->set('services.midtrans.server_key', 'server-key');
    config()->set('services.midtrans.snap_url', 'https://midtrans.test/snap/v1');

    Http::fake([
        'https://midtrans.test/*' => Http::response([
            'token' => 'snap-token',
            'redirect_url' => 'https://midtrans.test/pay/order-1',
        ]),
    ]);

    $user = User::factory()->create();
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'stock' => 5,
        'price' => 150000,
    ]);

    $user->cartItems()->create([
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $this->actingAs($user)
        ->post(route('checkout.store'), [
            'customer_name' => $user->name,
            'email' => $user->email,
            'phone' => '08123456789',
            'shipping_address' => 'Neo Tokyo District 9',
        ])
        ->assertRedirect('https://midtrans.test/pay/order-1');

    $order = $user->orders()->with('payment')->first();

    expect($order)
        ->not->toBeNull()
        ->and($order->status)->toBe(OrderStatus::PENDING_PAYMENT)
        ->and((float) $order->total)->toBe(300000.0)
        ->and($order->payment)->toHaveCount(1)
        ->and($product->refresh()->stock)->toBe(5)
        ->and($user->cartItems()->count())->toBe(0);
});
