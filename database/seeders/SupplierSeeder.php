<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Tech Supplies Inc',
                'email' => 'contact@techsupplies.com',
                'phone' => '+1234567800',
                'address' => '123 Tech Street, Silicon Valley, CA'
            ],
            [
                'name' => 'Global Electronics',
                'email' => 'sales@globalelectronics.com',
                'phone' => '+1234567801',
                'address' => '456 Innovation Ave, Austin, TX'
            ],
            [
                'name' => 'Prime Distributors',
                'email' => 'info@primedist.com',
                'phone' => '+1234567802',
                'address' => '789 Commerce Blvd, Miami, FL'
            ],
            [
                'name' => 'Alpha Trading Co',
                'email' => 'orders@alphatrading.com',
                'phone' => '+1234567803',
                'address' => '321 Business Park, Denver, CO'
            ],
            [
                'name' => 'Beta Wholesale',
                'email' => 'wholesale@betasupply.com',
                'phone' => '+1234567804',
                'address' => '654 Industrial Way, Portland, OR'
            ],
            [
                'name' => 'Mega Suppliers',
                'email' => 'contact@megasuppliers.com',
                'phone' => '+1234567805',
                'address' => '987 Distribution Center, Atlanta, GA'
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
