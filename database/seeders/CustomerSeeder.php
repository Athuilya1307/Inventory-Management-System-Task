<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        Customer::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);

        Customer::create([
            'name' => 'Robert Kumar',
            'email' => 'robert@example.com',
        ]);

        Customer::create([
            'name' => 'Priya Sharma',
            'email' => 'priya@example.com',
        ]);

        Customer::create([
            'name' => 'David Wilson',
            'email' => 'david@example.com',
        ]);
    }
}