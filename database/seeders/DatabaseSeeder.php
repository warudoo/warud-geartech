<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin GearTech',
            'email' => 'warud@geartech.com',
            'password' => 'warud123',
            'role' => UserRole::ADMIN,
        ]);

        User::factory()->create([
            'name' => 'Demo Buyer',
            'email' => 'user@geartech.test',
        ]);

        $categories = Category::factory()->count(4)->create();

        $categories->each(function (Category $category, int $index): void {
            Product::factory()
                ->count(4)
                ->create([
                    'category_id' => $category->id,
                    'featured' => $index < 2,
                ]);
        });
    }
}
