<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'name' => str($name)->title()->value(),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(10, 999),
            'sku' => 'GT-'.fake()->unique()->bothify('###??'),
            'brand' => fake()->randomElement(['Logitech', 'Razer', 'SteelSeries', 'Corsair', 'HyperX']),
            'description' => fake()->paragraphs(3, true),
            'price' => fake()->numberBetween(150000, 2500000),
            'stock' => fake()->numberBetween(5, 40),
            'featured_image' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=900&q=80',
            'image_url' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=900&q=80',
            'is_active' => true,
            'featured' => fake()->boolean(35),
        ];
    }
}
