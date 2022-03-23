<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name' => 'MSI Geforce RTX 3090 24GB',
            'image' => 'https://res.cloudinary.com/dg5wrkfe7/image/upload/v1638970171/1_MSI-GeForce-RTX-3090-SUPRIM-24G-Videokaart_unfm7i.jpg',
            'price' => 1499.99,
        ]);

        Product::create([
            'name' => 'Gigabyte RTX 3070 8GB',
            'image' => 'https://res.cloudinary.com/dg5wrkfe7/image/upload/v1638970171/2_Gigabyte-GeForce-RTX-3080-GAMING-OC-WATERFORCE-WB-10G-2-0-Videokaart_dujlid.jpg',
            'price' => 999.99,
        ]);

        Product::create([
            'name' => 'Asus Geforce RTX 3080 10GB',
            'image' => 'https://res.cloudinary.com/dg5wrkfe7/image/upload/v1638970171/1_Asus-Geforce-RTX-3070-ROG-STRIX-RTX3070-O8G-V2-GAMING-Videokaart_bzmfuy.jpg',
            'price' => 1199.99,
        ]);
    }
}
