<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->categories() as $category) {
            Category::query()->create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => true,
            ]);
        }
    }

    protected function categories(): array
    {
        return [
            [
                'name' => 'Keyboard',
                'description' => 'Koleksi mechanical keyboard gaming untuk setup kompetitif, streaming, dan daily use.',
            ],
            [
                'name' => 'Mouse',
                'description' => 'Pilihan gaming mouse wired dan wireless dengan sensor presisi untuk FPS, MOBA, dan multitasking.',
            ],
            [
                'name' => 'Headset',
                'description' => 'Headset gaming dengan fokus pada positional audio, mic clarity, dan kenyamanan sesi panjang.',
            ],
            [
                'name' => 'IEM',
                'description' => 'IEM gaming dan earbuds low-latency untuk pemain yang ingin form factor ringkas tanpa mengorbankan detail audio.',
            ],
            [
                'name' => 'Mousepad',
                'description' => 'Mousepad speed dan control untuk mendukung tracking konsisten di berbagai gaya aim.',
            ],
            [
                'name' => 'Monitor',
                'description' => 'Gaming monitor 24 sampai 27 inci dengan refresh rate tinggi untuk visual yang responsif.',
            ],
            [
                'name' => 'Bundles',
                'description' => 'Paket bundling bernilai lebih untuk upgrade setup dengan kombinasi periferal yang sudah saling cocok.',
            ],
        ];
    }
}
