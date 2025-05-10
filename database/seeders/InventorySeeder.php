<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();

        foreach ($products as $product) {
            // Stock in
            Inventory::create([
                'product_id' => $product->id,
                'quantity' => 5,
                'type' => 'in',
                'reference_number' => 'PO-' . $product->code,
                'notes' => 'Initial stock',
            ]);

            // Stock out
            Inventory::create([
                'product_id' => $product->id,
                'quantity' => 2,
                'type' => 'out',
                'reference_number' => 'SO-' . $product->code,
                'notes' => 'First sale',
            ]);
        }
    }
} 