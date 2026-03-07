<?php

namespace Database\Seeders\Shop;

use App\Models\Shop\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShopCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Watches',
            'Laptops',
            'Macbooks',
            'iPads',
            'SIM Cards',
        ];

        foreach ($categories as $index => $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'is_active' => true,
                    'sort_order' => $index,
                ]
            );
        }
    }
}
