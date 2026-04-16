<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

it('generates slug and sku automatically when an admin creates a product', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create([
        'name' => 'Keyboard',
        'slug' => 'keyboard-test',
    ]);

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Logitech G Pro X TKL Rapid',
            'brand' => 'Logitech G',
            'description' => 'Keyboard kompetitif untuk scrim dan ranked.',
            'price' => 2499000,
            'stock' => 12,
            'featured_image' => 'https://placehold.co/900x700/111827/E5E7EB?text=Keyboard',
            'is_active' => 1,
            'featured' => 1,
        ])
        ->assertRedirect(route('admin.products.index'));

    $product = Product::query()->firstOrFail();

    expect($product->slug)->toBe('logitech-g-pro-x-tkl-rapid')
        ->and($product->sku)->toBe('WGT-KEY-0001');
});

it('adds a numeric suffix to duplicate slugs and keeps sku stable on update', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create([
        'name' => 'Mouse',
        'slug' => 'mouse-test',
    ]);

    Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Fantech Helios XD5',
        'brand' => 'Fantech',
    ]);

    $product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Fantech Helios XD5',
        'brand' => 'Fantech',
    ]);

    $originalSku = $product->sku;

    expect($product->slug)->toBe('fantech-helios-xd5-2')
        ->and($originalSku)->toBe('WGT-MOU-0002');

    $this->actingAs($admin)
        ->put(route('admin.products.update', $product), [
            'category_id' => $category->id,
            'name' => 'Fantech Helios XD5 Pro',
            'brand' => 'Fantech',
            'description' => 'Mouse wireless ringan untuk claw grip.',
            'price' => 899000,
            'stock' => 10,
            'featured_image' => 'https://placehold.co/900x700/111827/E5E7EB?text=Mouse',
            'is_active' => 1,
            'featured' => 0,
        ])
        ->assertRedirect(route('admin.products.index'));

    expect($product->fresh()->slug)->toBe('fantech-helios-xd5-pro')
        ->and($product->fresh()->sku)->toBe($originalSku);
});
