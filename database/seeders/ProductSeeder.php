<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Toyota Camry',
                'code' => 'TC001',
                'description' => 'Toyota Camry 2024 - Sedan',
                'price' => 500000000,
            ],
            [
                'name' => 'Honda Civic',
                'code' => 'HC001',
                'description' => 'Honda Civic 2024 - Sedan',
                'price' => 450000000,
            ],
            [
                'name' => 'Suzuki Ertiga',
                'code' => 'SE001',
                'description' => 'Suzuki Ertiga 2024 - MPV',
                'price' => 300000000,
            ],
            [
                'name' => 'Daihatsu Xenia',
                'code' => 'DX001',
                'description' => 'Daihatsu Xenia 2024 - MPV',
                'price' => 280000000,
            ],
            [
                'name' => 'Mitsubishi Xpander',
                'code' => 'MX001',
                'description' => 'Mitsubishi Xpander 2024 - MPV',
                'price' => 350000000,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
} 