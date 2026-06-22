<?php

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

it('lets an admin search and filter products in the product index', function () {
    $admin = User::factory()->admin()->create();
    $gamingCategory = Category::factory()->create(['name' => 'Gaming Chairs']);
    $audioCategory = Category::factory()->create(['name' => 'Audio']);

    Product::factory()->create([
        'category_id' => $gamingCategory->id,
        'name' => 'Atlas Chair',
        'brand' => 'Atlas',
        'sku' => 'ATLAS-CHAIR-01',
    ]);

    Product::factory()->create([
        'category_id' => $audioCategory->id,
        'name' => 'Nova Headset',
        'brand' => 'Nova',
        'sku' => 'NOVA-HEADSET-01',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.products.index', [
            'search' => 'Atlas',
            'category' => $gamingCategory->id,
        ]))
        ->assertOk()
        ->assertSee('Atlas Chair')
        ->assertDontSee('Nova Headset');
});

it('deactivates products with past orders instead of hard deleting them', function () {
    $admin = User::factory()->admin()->create();
    $buyer = User::factory()->create();
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'is_active' => true,
    ]);

    $order = $buyer->orders()->create([
        'order_number' => 'ORD-LOCK-001',
        'status' => OrderStatus::PAID,
        'subtotal' => 250000,
        'total' => 250000,
        'customer_name' => $buyer->name,
        'email' => $buyer->email,
        'phone' => '08123456789',
        'shipping_address' => 'Jl. GearTech 1',
        'payment_provider' => 'midtrans',
        'paid_at' => now(),
        'stock_deducted_at' => now(),
    ]);

    $order->items()->create([
        'product_id' => $product->id,
        'product_name' => $product->name,
        'sku' => $product->sku,
        'unit_price' => $product->price,
        'quantity' => 1,
        'line_total' => $product->price,
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index'));

    expect($product->fresh())
        ->not->toBeNull()
        ->and($product->fresh()->is_active)->toBeFalse();
});

it('prevents stock updates below zero', function () {
    $admin = User::factory()->admin()->create();
    $product = Product::factory()->create([
        'stock' => 10,
    ]);

    $this->actingAs($admin)
        ->from(route('admin.products.index'))
        ->patch(route('admin.products.stock.update', $product), [
            'stock' => -1,
        ])
        ->assertSessionHasErrors('stock');

    expect($product->fresh()->stock)->toBe(10);
});
