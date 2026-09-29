<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+1234560001',
                'address' => '100 Main Street, New York, NY'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '+1234560002',
                'address' => '200 Oak Avenue, Los Angeles, CA'
            ],
            [
                'name' => 'Robert Johnson',
                'email' => 'robert.j@example.com',
                'phone' => '+1234560003',
                'address' => '300 Pine Road, Chicago, IL'
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily.davis@example.com',
                'phone' => '+1234560004',
                'address' => '400 Maple Lane, Houston, TX'
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.b@example.com',
                'phone' => '+1234560005',
                'address' => '500 Cedar Court, Phoenix, AZ'
            ],
            [
                'name' => 'Sarah Wilson',
                'email' => 'sarah.wilson@example.com',
                'phone' => '+1234560006',
                'address' => '600 Elm Street, Philadelphia, PA'
            ],
            [
                'name' => 'David Martinez',
                'email' => 'david.martinez@example.com',
                'phone' => '+1234560007',
                'address' => '700 Birch Avenue, San Antonio, TX'
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@example.com',
                'phone' => '+1234560008',
                'address' => '800 Spruce Boulevard, San Diego, CA'
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
