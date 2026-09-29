<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'Apple', 'image' => null],
            ['name' => 'Samsung', 'image' => null],
            ['name' => 'Sony', 'image' => null],
            ['name' => 'LG', 'image' => null],
            ['name' => 'Dell', 'image' => null],
            ['name' => 'HP', 'image' => null],
            ['name' => 'Lenovo', 'image' => null],
            ['name' => 'Asus', 'image' => null],
            ['name' => 'Microsoft', 'image' => null],
            ['name' => 'Xiaomi', 'image' => null],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
