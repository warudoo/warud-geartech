<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin',
            'phone' => '0812-9000-0001',
            'address' => 'Warud Geartech HQ, Jl. Pahlawan No. 18, Surabaya, Jawa Timur',
        ]);

        foreach ($this->users() as $user) {
            User::factory()->create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => 'password',
                'phone' => $user['phone'],
                'address' => $user['address'],
            ]);
        }
    }

    protected function users(): array
    {
        return [
            [
                'name' => 'Aditya Pratama',
                'email' => 'aditya.pratama@warudgeartech.test',
                'phone' => '0812-1414-2201',
                'address' => 'Jl. Cendana Raya No. 14, Sukolilo, Surabaya',
            ],
            [
                'name' => 'Nabila Putri Ramadhani',
                'email' => 'nabila.putri@warudgeartech.test',
                'phone' => '0813-2233-4412',
                'address' => 'Perumahan Taman Puspa 2 Blok B3, Sidoarjo',
            ],
            [
                'name' => 'Rizky Saputra',
                'email' => 'rizky.saputra@warudgeartech.test',
                'phone' => '0821-8833-1160',
                'address' => 'Jl. Mawar Merah No. 21, Lowokwaru, Malang',
            ],
            [
                'name' => 'Vania Maharani',
                'email' => 'vania.maharani@warudgeartech.test',
                'phone' => '0819-6111-7405',
                'address' => 'Apartemen East Coast Tower 2 Unit 12B, Mulyorejo, Surabaya',
            ],
            [
                'name' => 'Fadhil Hakim',
                'email' => 'fadhil.hakim@warudgeartech.test',
                'phone' => '0813-7088-5250',
                'address' => 'Jl. Anggrek Timur No. 7, Denpasar Selatan, Bali',
            ],
            [
                'name' => 'Keisha Anggraini',
                'email' => 'keisha.anggraini@warudgeartech.test',
                'phone' => '0822-4512-7743',
                'address' => 'Jl. KH Ahmad Dahlan No. 55, Sleman, Yogyakarta',
            ],
            [
                'name' => 'Muhammad Rayhan',
                'email' => 'muhammad.rayhan@warudgeartech.test',
                'phone' => '0812-9981-1204',
                'address' => 'Cluster Maple Residence Blok F8, Bekasi Barat',
            ],
            [
                'name' => 'Salsa Anindita',
                'email' => 'salsa.anindita@warudgeartech.test',
                'phone' => '0817-5633-2408',
                'address' => 'Jl. Tlogo Biru No. 3, Semarang Barat, Semarang',
            ],
            [
                'name' => 'Galang Prakoso',
                'email' => 'galang.prakoso@warudgeartech.test',
                'phone' => '0851-1200-7329',
                'address' => 'Jl. Veteran No. 118, Samarinda Ulu, Samarinda',
            ],
            [
                'name' => 'Tiara Kusuma Dewi',
                'email' => 'tiara.kusuma@warudgeartech.test',
                'phone' => '0813-7007-8885',
                'address' => 'Jl. Teratai Putih No. 9, Panakkukang, Makassar',
            ],
        ];
    }
}
