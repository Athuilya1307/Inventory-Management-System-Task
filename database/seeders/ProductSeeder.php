<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::insert([
            [
                'name' => 'Product 1',
                'code' => 'P001',
                'price' => 10.00,
                'tax_percentage' => 5.00,
                'stock' => 100,
            ],
            [
                'name' => 'Product 2',
                'code' => 'P002',
                'price' => 20.00,
                'tax_percentage' => 10.00,
                'stock' => 50,
            ],
            [
                'name' => 'Product 3',
                'code' => 'P003',
                'price' => 15.00,
                'tax_percentage' => 8.00,
                'stock' => 75,
            ],
        ]);

    }
}
