<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WareHouse;

class WareHouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'name' => 'Main Warehouse',
                'email' => 'main@warehouse.com',
                'phone' => '+1234567890',
                'city' => 'New York'
            ],
            [
                'name' => 'Secondary Warehouse',
                'email' => 'secondary@warehouse.com',
                'phone' => '+1234567891',
                'city' => 'Los Angeles'
            ],
            [
                'name' => 'East Warehouse',
                'email' => 'east@warehouse.com',
                'phone' => '+1234567892',
                'city' => 'Boston'
            ],
            [
                'name' => 'West Warehouse',
                'email' => 'west@warehouse.com',
                'phone' => '+1234567893',
                'city' => 'Seattle'
            ],
            [
                'name' => 'Central Warehouse',
                'email' => 'central@warehouse.com',
                'phone' => '+1234567894',
                'city' => 'Chicago'
            ],
        ];

        foreach ($warehouses as $warehouse) {
            WareHouse::create($warehouse);
        }
    }
}
