<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $productNames = [
            'Logitech G Pro X Superlight 2',
            'Razer DeathAdder V3 Wired',
            'SteelSeries Apex Pro TKL Wireless',
            'Fantech Aria XD7',
            'HyperX Cloud III Wireless',
            'Corsair MM300 Pro Extended',
            'ASUS ROG Strix XG249CM 24.5',
            'Redragon K616 Fizz Pro',
            'Warud Geartech Glide Control XXL',
        ];
        $name = fake()->randomElement($productNames).' '.fake()->unique()->numerify('##');
        $image = 'https://placehold.co/900x700/111827/E5E7EB?text='.rawurlencode($name);

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'brand' => fake()->randomElement([
                'Logitech G',
                'Razer',
                'SteelSeries',
                'Fantech',
                'HyperX',
                'Corsair',
                'ASUS ROG',
                'Redragon',
                'Warud Geartech',
            ]),
            'description' => fake()->sentence(18),
            'price' => fake()->numberBetween(199000, 4299000),
            'stock' => fake()->numberBetween(5, 40),
            'featured_image' => $image,
            'image_url' => $image,
            'is_active' => true,
            'featured' => fake()->boolean(35),
        ];
    }
}
