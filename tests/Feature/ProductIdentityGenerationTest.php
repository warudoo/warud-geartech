<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('generates slug and sku automatically when an admin creates a product', function () {
    Storage::fake('public');

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
            'featured_image' => UploadedFile::fake()->image('keyboard.jpg'),
            'is_active' => 1,
            'featured' => 1,
        ])
        ->assertRedirect(route('admin.products.index'));

    $product = Product::query()->firstOrFail();

    expect($product->slug)->toBe('logitech-g-pro-x-tkl-rapid')
        ->and($product->sku)->toBe('WGT-KEY-0001');
});

it('adds a numeric suffix to duplicate slugs and keeps sku stable on update', function () {
    Storage::fake('public');

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
            'featured_image' => UploadedFile::fake()->image('mouse.jpg'),
            'is_active' => 1,
            'featured' => 0,
        ])
        ->assertRedirect(route('admin.products.index'));

    expect($product->fresh()->slug)->toBe('fantech-helios-xd5-pro')
        ->and($product->fresh()->sku)->toBe($originalSku);
});

it('replaces the old stored image when an admin uploads a new product image', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create([
        'name' => 'Headset',
        'slug' => 'headset-test',
    ]);

    $product = Product::factory()->create([
        'category_id' => $category->id,
        'featured_image' => UploadedFile::fake()->image('old-headset.jpg')->store('products', 'public'),
        'image_url' => null,
    ]);

    $oldImagePath = $product->featured_image;

    Storage::disk('public')->assertExists($oldImagePath);

    $this->actingAs($admin)
        ->put(route('admin.products.update', $product), [
            'category_id' => $category->id,
            'name' => 'HyperX Cloud Alpha Wireless',
            'brand' => 'HyperX',
            'description' => 'Headset wireless dengan baterai panjang untuk sesi ranked malam.',
            'price' => 1799000,
            'stock' => 8,
            'featured_image' => UploadedFile::fake()->image('new-headset.webp'),
            'is_active' => 1,
            'featured' => 0,
        ])
        ->assertRedirect(route('admin.products.index'));

    $product->refresh();

    expect($product->featured_image)->not->toBe($oldImagePath)
        ->and($product->image_url)->toBe($product->featured_image);

    Storage::disk('public')->assertMissing($oldImagePath);
    Storage::disk('public')->assertExists($product->featured_image);
});
