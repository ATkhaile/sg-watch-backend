<?php

namespace Database\Seeders\Shop;

use App\Models\Shop\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShopBrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Orient Star', 'country' => 'Japan'],
            ['name' => 'Orient', 'country' => 'Japan'],
            ['name' => 'Seiko', 'country' => 'Japan'],
            ['name' => 'Citizen', 'country' => 'Japan'],
            ['name' => 'Carnival', 'country' => 'Switzerland'],
            ['name' => 'Longines', 'country' => 'Switzerland'],
            ['name' => 'Tissot', 'country' => 'Switzerland'],
            ['name' => 'Omega', 'country' => 'Switzerland'],
        ];

        foreach ($brands as $index => $brand) {
            Brand::firstOrCreate(
                ['slug' => Str::slug($brand['name'])],
                [
                    'name' => $brand['name'],
                    'slug' => Str::slug($brand['name']),
                    'country' => $brand['country'],
                    'is_active' => true,
                    'sort_order' => $index,
                ]
            );
        }
    }
}
