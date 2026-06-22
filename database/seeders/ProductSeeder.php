<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use RuntimeException;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::query()->get()->keyBy('name');

        foreach ($this->products() as $product) {
            $category = $categories->get($product['category']);

            if (! $category) {
                throw new RuntimeException("Category [{$product['category']}] is not available for product seeding.");
            }

            $image = $this->placeholderImage($product['name']);

            Product::factory()->create([
                'category_id' => $category->id,
                'name' => $product['name'],
                'brand' => $product['brand'],
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'featured_image' => $image,
                'image_url' => $image,
                'is_active' => true,
                'featured' => $product['featured'],
            ]);
        }
    }

    protected function placeholderImage(string $name): string
    {
        return 'https://placehold.co/900x700/111827/E5E7EB?text='.rawurlencode($name);
    }

    protected function products(): array
    {
        return [
            [
                'category' => 'Keyboard',
                'name' => 'Logitech G Pro X TKL Rapid',
                'brand' => 'Logitech G',
                'description' => 'Keyboard TKL untuk esports dengan actuation cepat, stabilizer rapat, dan layout ringkas untuk meja kompetitif.',
                'price' => 2499000,
                'stock' => 12,
                'featured' => true,
            ],
            [
                'category' => 'Keyboard',
                'name' => 'Razer BlackWidow V4 75%',
                'brand' => 'Razer',
                'description' => 'Mechanical keyboard 75 persen dengan gasket mount, hot-swap, dan RGB penuh untuk setup premium.',
                'price' => 2999000,
                'stock' => 8,
                'featured' => true,
            ],
            [
                'category' => 'Keyboard',
                'name' => 'SteelSeries Apex Pro TKL Wireless Gen 3',
                'brand' => 'SteelSeries',
                'description' => 'Keyboard wireless premium dengan adjustable actuation dan mode dual wireless untuk gaming serius.',
                'price' => 4299000,
                'stock' => 6,
                'featured' => true,
            ],
            [
                'category' => 'Keyboard',
                'name' => 'Fantech Maxfit81 Frost Wireless',
                'brand' => 'Fantech',
                'description' => 'Keyboard 81-key triple mode dengan karakter suara lebih padat dan cocok untuk gaming plus kerja harian.',
                'price' => 1199000,
                'stock' => 18,
                'featured' => false,
            ],
            [
                'category' => 'Keyboard',
                'name' => 'Redragon K616 Fizz Pro',
                'brand' => 'Redragon',
                'description' => 'Keyboard 60 persen entry-level dengan koneksi wireless dan feel ringan untuk gamer yang baru upgrade setup.',
                'price' => 649000,
                'stock' => 24,
                'featured' => false,
            ],
            [
                'category' => 'Mouse',
                'name' => 'Logitech G Pro X Superlight 2',
                'brand' => 'Logitech G',
                'description' => 'Mouse wireless ultra ringan dengan sensor flagship dan shape aman untuk FPS kompetitif.',
                'price' => 2399000,
                'stock' => 10,
                'featured' => true,
            ],
            [
                'category' => 'Mouse',
                'name' => 'Razer DeathAdder V3 Wired',
                'brand' => 'Razer',
                'description' => 'Mouse wired ergonomis dengan sensor presisi tinggi untuk pemain yang butuh grip nyaman dan tracking konsisten.',
                'price' => 899000,
                'stock' => 20,
                'featured' => false,
            ],
            [
                'category' => 'Mouse',
                'name' => 'SteelSeries Rival 3 Wireless',
                'brand' => 'SteelSeries',
                'description' => 'Mouse dual wireless hemat daya dengan bobot seimbang untuk setup kerja dan gaming kasual.',
                'price' => 799000,
                'stock' => 22,
                'featured' => false,
            ],
            [
                'category' => 'Mouse',
                'name' => 'Fantech Aria XD7',
                'brand' => 'Fantech',
                'description' => 'Mouse wireless ringan dengan shape simetris yang populer untuk claw grip dan flick cepat.',
                'price' => 799000,
                'stock' => 16,
                'featured' => true,
            ],
            [
                'category' => 'Mouse',
                'name' => 'Corsair M75 Air Wireless',
                'brand' => 'Corsair',
                'description' => 'Mouse wireless ambidextrous dengan polling tinggi dan bobot ringan untuk aim responsif.',
                'price' => 1899000,
                'stock' => 11,
                'featured' => false,
            ],
            [
                'category' => 'Mouse',
                'name' => 'ASUS ROG Keris II Ace',
                'brand' => 'ASUS ROG',
                'description' => 'Mouse esports premium dengan latency rendah dan feet halus untuk tracking cepat.',
                'price' => 2199000,
                'stock' => 9,
                'featured' => true,
            ],
            [
                'category' => 'Headset',
                'name' => 'HyperX Cloud III Wireless',
                'brand' => 'HyperX',
                'description' => 'Headset wireless dengan tuning aman, baterai panjang, dan kenyamanan tinggi untuk main semalaman.',
                'price' => 2199000,
                'stock' => 14,
                'featured' => true,
            ],
            [
                'category' => 'Headset',
                'name' => 'Razer BlackShark V2 X',
                'brand' => 'Razer',
                'description' => 'Headset wired value-for-money dengan staging audio jelas untuk FPS dan voice chat.',
                'price' => 699000,
                'stock' => 28,
                'featured' => false,
            ],
            [
                'category' => 'Headset',
                'name' => 'Logitech G733 Lightspeed',
                'brand' => 'Logitech G',
                'description' => 'Headset wireless ringan dengan karakter suara fun dan desain nyaman untuk streaming.',
                'price' => 1799000,
                'stock' => 15,
                'featured' => true,
            ],
            [
                'category' => 'Headset',
                'name' => 'SteelSeries Arctis Nova 7',
                'brand' => 'SteelSeries',
                'description' => 'Headset wireless serbaguna dengan dual connectivity dan mic retractable untuk setup hybrid.',
                'price' => 2699000,
                'stock' => 9,
                'featured' => true,
            ],
            [
                'category' => 'Headset',
                'name' => 'Corsair HS80 Max Wireless',
                'brand' => 'Corsair',
                'description' => 'Headset wireless premium dengan earcup nyaman dan microphone broadcast-style yang solid.',
                'price' => 2399000,
                'stock' => 8,
                'featured' => false,
            ],
            [
                'category' => 'IEM',
                'name' => 'ASUS ROG Cetra True Wireless SpeedNova',
                'brand' => 'ASUS ROG',
                'description' => 'TWS gaming low-latency dengan dongle 2.4 GHz untuk mobile dan handheld gaming.',
                'price' => 2199000,
                'stock' => 10,
                'featured' => true,
            ],
            [
                'category' => 'IEM',
                'name' => 'Razer Hammerhead HyperSpeed PlayStation Edition',
                'brand' => 'Razer',
                'description' => 'Earbuds gaming wireless dengan latency rendah dan profil audio yang fokus ke detail langkah.',
                'price' => 2099000,
                'stock' => 9,
                'featured' => false,
            ],
            [
                'category' => 'IEM',
                'name' => 'Logitech G FITS True Wireless',
                'brand' => 'Logitech G',
                'description' => 'TWS premium dengan eartips yang membentuk ke telinga untuk kenyamanan dan seal lebih personal.',
                'price' => 2699000,
                'stock' => 7,
                'featured' => true,
            ],
            [
                'category' => 'IEM',
                'name' => 'HyperX Cloud Earbuds II',
                'brand' => 'HyperX',
                'description' => 'IEM wired simpel untuk mobile gaming dengan mic inline dan bobot ringan.',
                'price' => 549000,
                'stock' => 26,
                'featured' => false,
            ],
            [
                'category' => 'IEM',
                'name' => 'Warud Geartech Arena IEM One',
                'brand' => 'Warud Geartech',
                'description' => 'IEM gaming entry-level racikan Warud Geartech dengan tuning hangat dan kabel detachable.',
                'price' => 349000,
                'stock' => 30,
                'featured' => false,
            ],
            [
                'category' => 'Mousepad',
                'name' => 'Razer Gigantus V2 Medium',
                'brand' => 'Razer',
                'description' => 'Mousepad speed-medium dengan permukaan stabil untuk tracking cepat tanpa terasa liar.',
                'price' => 249000,
                'stock' => 35,
                'featured' => false,
            ],
            [
                'category' => 'Mousepad',
                'name' => 'Logitech G640 Cloth',
                'brand' => 'Logitech G',
                'description' => 'Mousepad cloth control yang familiar dipakai pemain esports untuk swipe besar.',
                'price' => 549000,
                'stock' => 18,
                'featured' => true,
            ],
            [
                'category' => 'Mousepad',
                'name' => 'SteelSeries QcK Heavy Large',
                'brand' => 'SteelSeries',
                'description' => 'Mousepad control tebal dengan glide konsisten dan base yang tidak gampang bergeser.',
                'price' => 499000,
                'stock' => 20,
                'featured' => true,
            ],
            [
                'category' => 'Mousepad',
                'name' => 'Fantech Strikepad Pro MPC450',
                'brand' => 'Fantech',
                'description' => 'Mousepad speed entry-level yang ringan dirawat dan cocok untuk pemain FPS agresif.',
                'price' => 199000,
                'stock' => 32,
                'featured' => false,
            ],
            [
                'category' => 'Mousepad',
                'name' => 'Corsair MM300 Pro Extended',
                'brand' => 'Corsair',
                'description' => 'Mousepad extended dengan coating tahan cipratan dan area gerak luas untuk keyboard plus mouse.',
                'price' => 459000,
                'stock' => 17,
                'featured' => false,
            ],
            [
                'category' => 'Mousepad',
                'name' => 'Warud Geartech Glide Control XXL',
                'brand' => 'Warud Geartech',
                'description' => 'Mousepad control XXL untuk setup full desk dengan glide halus dan stopping power seimbang.',
                'price' => 229000,
                'stock' => 27,
                'featured' => false,
            ],
            [
                'category' => 'Monitor',
                'name' => 'ASUS ROG Strix XG249CM 24.5',
                'brand' => 'ASUS ROG',
                'description' => 'Monitor 24.5 inci 270 Hz untuk pemain kompetitif yang mengejar motion clarity tinggi.',
                'price' => 4299000,
                'stock' => 7,
                'featured' => true,
            ],
            [
                'category' => 'Monitor',
                'name' => 'ASUS ROG XG27ACS 27',
                'brand' => 'ASUS ROG',
                'description' => 'Monitor 27 inci QHD 180 Hz dengan warna rapi untuk gaming dan content workflow.',
                'price' => 5199000,
                'stock' => 6,
                'featured' => true,
            ],
            [
                'category' => 'Monitor',
                'name' => 'Corsair Xeneon 27QHD240 OLED',
                'brand' => 'Corsair',
                'description' => 'Monitor OLED premium 27 inci dengan response time sangat cepat dan visual kontras tinggi.',
                'price' => 12999000,
                'stock' => 5,
                'featured' => true,
            ],
            [
                'category' => 'Monitor',
                'name' => 'Redragon Ruby GM24X5IPS 24',
                'brand' => 'Redragon',
                'description' => 'Monitor 24 inci 180 Hz yang efisien untuk upgrade dari panel office ke gaming.',
                'price' => 2499000,
                'stock' => 13,
                'featured' => false,
            ],
            [
                'category' => 'Monitor',
                'name' => 'Warud Geartech Vision 27Q 180Hz',
                'brand' => 'Warud Geartech',
                'description' => 'Monitor QHD 27 inci private label Warud Geartech untuk gamer yang mencari value tinggi di kelas menengah.',
                'price' => 3399000,
                'stock' => 12,
                'featured' => false,
            ],
            [
                'category' => 'Bundles',
                'name' => 'Logitech G Pro TKL + G304 Wireless Bundle',
                'brand' => 'Logitech G',
                'description' => 'Paket keyboard dan mouse wireless untuk pemain yang ingin setup ringkas dan rapi tanpa bongkar pilih item satu per satu.',
                'price' => 1899000,
                'stock' => 10,
                'featured' => true,
            ],
            [
                'category' => 'Bundles',
                'name' => 'Razer Cobra + Gigantus Speed Bundle',
                'brand' => 'Razer',
                'description' => 'Bundle mouse dan mousepad speed untuk gamer FPS yang ingin upgrade cepat dengan budget menengah.',
                'price' => 1199000,
                'stock' => 14,
                'featured' => false,
            ],
            [
                'category' => 'Bundles',
                'name' => 'HyperX Cloud Stinger 2 + Mousepad Bundle',
                'brand' => 'HyperX',
                'description' => 'Bundle headset dan mousepad untuk setup warnet rumahan, sekolah, atau gamer baru yang butuh paket aman.',
                'price' => 899000,
                'stock' => 16,
                'featured' => false,
            ],
            [
                'category' => 'Bundles',
                'name' => 'SteelSeries Apex 3 + Rival 3 Bundle',
                'brand' => 'SteelSeries',
                'description' => 'Bundle keyboard dan mouse gaming dengan feel familiar dan build yang cocok untuk upgrade workstation gaming.',
                'price' => 1699000,
                'stock' => 11,
                'featured' => false,
            ],
            [
                'category' => 'Bundles',
                'name' => 'Warud Geartech Starter Stream Bundle',
                'brand' => 'Warud Geartech',
                'description' => 'Bundle house-brand untuk setup streaming pemula dengan kombinasi periferal yang aman dan mudah dipakai.',
                'price' => 1499000,
                'stock' => 15,
                'featured' => true,
            ],
        ];
    }
}
