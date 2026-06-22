<?php

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

it('redirects admins away from buyer routes to the admin dashboard', function () {
    $admin = User::factory()->admin()->create();
    $buyer = User::factory()->create();
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'stock' => 10,
    ]);

    $order = Order::query()->create([
        'user_id' => $buyer->id,
        'order_number' => 'ORD-ADMIN-BLOCK-001',
        'status' => OrderStatus::PENDING_PAYMENT,
        'subtotal' => 100000,
        'total' => 100000,
        'customer_name' => $buyer->name,
        'email' => $buyer->email,
        'phone' => '08123456789',
        'shipping_address' => 'Jl. Operasional No. 1',
        'payment_provider' => 'midtrans',
    ]);

    $this->actingAs($admin)
        ->get(route('cart.index'))
        ->assertRedirect(route('admin.dashboard'));

    $this->actingAs($admin)
        ->get(route('checkout.show'))
        ->assertRedirect(route('admin.dashboard'));

    $this->actingAs($admin)
        ->get(route('profile.edit'))
        ->assertRedirect(route('admin.dashboard'));

    $this->actingAs($admin)
        ->get(route('orders.index'))
        ->assertRedirect(route('admin.dashboard'));

    $this->actingAs($admin)
        ->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ])
        ->assertRedirect(route('admin.dashboard'));

    $this->actingAs($admin)
        ->post(route('orders.payment.store', $order->order_number))
        ->assertRedirect(route('admin.dashboard'));
});

it('shows admin navigation and hides buyer purchase actions for admin accounts', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'stock' => 10,
    ]);

    $this->actingAs($admin)
        ->get(route('home'))
        ->assertOk()
        ->assertSee('Admin Only')
        ->assertSee('Reports')
        ->assertDontSee('Profile');

    $this->actingAs($admin)
        ->get(route('products.show', $product->slug))
        ->assertOk()
        ->assertSee('Akun admin tidak menggunakan flow cart atau checkout customer.')
        ->assertDontSee('Add To Cart');
});
