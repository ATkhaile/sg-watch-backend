<?php

namespace Database\Seeders\Shop;

use App\Models\Shop\Brand;
use App\Models\Shop\Category;
use App\Models\Shop\Product;
use App\Models\Shop\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedBrands();
        $this->seedCategories();
        $this->seedProducts();
    }

    private function seedBrands(): void
    {
        $brands = [
            ['name' => 'Mathey-Tissot', 'country' => 'Switzerland'],
            ['name' => 'Orient Star', 'country' => 'Japan'],
            ['name' => 'Casio', 'country' => 'Japan'],
            ['name' => 'Seiko', 'country' => 'Japan'],
            ['name' => 'Citizen', 'country' => 'Japan'],
            ['name' => 'Tissot', 'country' => 'Switzerland'],
            ['name' => 'Daniel Wellington', 'country' => 'Sweden'],
            ['name' => 'Fossil', 'country' => 'USA'],
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

    private function seedCategories(): void
    {
        $categories = [
            [
                'name' => 'Men Watches',
                'slug' => 'men-watches',
                'children' => [
                    ['name' => 'Men Quartz Watches', 'slug' => 'men-quartz-watches'],
                    ['name' => 'Men Automatic Watches', 'slug' => 'men-automatic-watches'],
                    ['name' => 'Men Sport Watches', 'slug' => 'men-sport-watches'],
                ],
            ],
            [
                'name' => 'Women Watches',
                'slug' => 'women-watches',
                'children' => [
                    ['name' => 'Women Quartz Watches', 'slug' => 'women-quartz-watches'],
                    ['name' => 'Women Automatic Watches', 'slug' => 'women-automatic-watches'],
                    ['name' => 'Women Fashion Watches', 'slug' => 'women-fashion-watches'],
                ],
            ],
            [
                'name' => 'Couple Watches',
                'slug' => 'couple-watches',
                'children' => [],
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'children' => [
                    ['name' => 'Watch Straps', 'slug' => 'watch-straps'],
                    ['name' => 'Watch Boxes', 'slug' => 'watch-boxes'],
                ],
            ],
        ];

        foreach ($categories as $index => $cat) {
            $parent = Category::firstOrCreate(
                ['slug' => $cat['slug']],
                [
                    'name' => $cat['name'],
                    'slug' => $cat['slug'],
                    'is_active' => true,
                    'sort_order' => $index,
                ]
            );

            foreach ($cat['children'] as $childIndex => $child) {
                Category::firstOrCreate(
                    ['slug' => $child['slug']],
                    [
                        'name' => $child['name'],
                        'slug' => $child['slug'],
                        'parent_id' => $parent->id,
                        'is_active' => true,
                        'sort_order' => $childIndex,
                    ]
                );
            }
        }
    }

    private function seedProducts(): void
    {
        $matheyTissot = Brand::where('slug', 'mathey-tissot')->first();
        $orientStar = Brand::where('slug', 'orient-star')->first();
        $casio = Brand::where('slug', 'casio')->first();
        $seiko = Brand::where('slug', 'seiko')->first();
        $citizen = Brand::where('slug', 'citizen')->first();
        $tissot = Brand::where('slug', 'tissot')->first();
        $danielWellington = Brand::where('slug', 'daniel-wellington')->first();
        $fossil = Brand::where('slug', 'fossil')->first();

        $catWomenQuartz = Category::where('slug', 'women-quartz-watches')->first();
        $catMenAutomatic = Category::where('slug', 'men-automatic-watches')->first();
        $catMenQuartz = Category::where('slug', 'men-quartz-watches')->first();
        $catMenSport = Category::where('slug', 'men-sport-watches')->first();
        $catWomenAutomatic = Category::where('slug', 'women-automatic-watches')->first();
        $catWomenFashion = Category::where('slug', 'women-fashion-watches')->first();

        $products = [
            [
                'category_id' => $catWomenQuartz?->id,
                'brand_id' => $matheyTissot?->id,
                'name' => 'Mathey-Tissot D117ABU Women Quartz - Display Item',
                'slug' => 'mathey-tissot-d117abu',
                'sku' => 'D117ABU',
                'short_description' => 'Swiss-made Mathey-Tissot women quartz watch, display item',
                'description' => 'Mathey-Tissot D117ABU women quartz watch with elegant and luxurious design. Authentic Swiss-made product, perfect for women who love sophistication.',
                'product_info' => "Authentic watch, 100% new\nPackage includes: box, manual, warranty card\nGold warranty 5 years at Hoang Hai Watch\nShipping to Vietnam supported\nFor Watch Order items, please contact us to confirm before placing order",
                'deal_info' => "Valid until Feb 28\nFree S-Storm Power 20W speaker worth JPY 8,000\nOpt out of gift for JPY 2,000 discount\nHurry up, limited quantity!",
                'price_jpy' => 166900,
                'price_vnd' => 2346000,
                'original_price_jpy' => null,
                'original_price_vnd' => null,
                'points' => 276,
                'gender' => 'female',
                'movement_type' => 'quartz',
                'condition' => 'display',
                'attributes' => [
                    'case_size' => 32,
                    'case_material' => 'Stainless Steel',
                    'band_material' => 'Stainless Steel',
                    'dial_color' => 'Silver',
                    'glass_material' => 'Sapphire',
                    'water_resistance' => '3ATM',
                ],
                'stock_quantity' => 3,
                'warranty_months' => 60,
                'is_active' => true,
                'is_featured' => true,
                'images' => [
                    ['image_url' => '/storage/shop/products/d117abu-1.jpg', 'is_primary' => true],
                    ['image_url' => '/storage/shop/products/d117abu-2.jpg', 'is_primary' => false],
                    ['image_url' => '/storage/shop/products/d117abu-3.jpg', 'is_primary' => false],
                ],
            ],
            [
                'category_id' => $catMenAutomatic?->id,
                'brand_id' => $orientStar?->id,
                'name' => 'Orient Star Mechanical Moon Phase RK-AY0102S',
                'slug' => 'orient-star-rk-ay0102s',
                'sku' => 'RK-AY0102S',
                'short_description' => 'Orient Star men automatic watch with moon phase complication',
                'description' => 'Orient Star Mechanical Moon Phase RK-AY0102S is a premium automatic watch with moon phase display, elegant design suitable for businessmen.',
                'product_info' => "Authentic watch, 100% new\nPackage includes: box, manual, warranty card\nGold warranty 5 years at Hoang Hai Watch\nShipping to Vietnam supported",
                'deal_info' => null,
                'price_jpy' => 89800,
                'price_vnd' => 1262000,
                'original_price_jpy' => 110000,
                'original_price_vnd' => 1546000,
                'points' => 148,
                'gender' => 'male',
                'movement_type' => 'automatic',
                'condition' => 'new',
                'attributes' => [
                    'case_size' => 41,
                    'case_material' => 'Stainless Steel',
                    'band_material' => 'Leather',
                    'dial_color' => 'White',
                    'glass_material' => 'Sapphire',
                    'water_resistance' => '5ATM',
                    'power_reserve' => '50 hours',
                ],
                'stock_quantity' => 5,
                'warranty_months' => 60,
                'is_active' => true,
                'is_featured' => true,
                'images' => [
                    ['image_url' => '/storage/shop/products/rk-ay0102s-1.jpg', 'is_primary' => true],
                    ['image_url' => '/storage/shop/products/rk-ay0102s-2.jpg', 'is_primary' => false],
                ],
            ],
            [
                'category_id' => $catMenSport?->id,
                'brand_id' => $casio?->id,
                'name' => 'Casio G-Shock GA-2100-1A1',
                'slug' => 'casio-g-shock-ga-2100-1a1',
                'sku' => 'GA-2100-1A1',
                'short_description' => 'Casio G-Shock men sport watch, ultra durable',
                'description' => 'Casio G-Shock GA-2100-1A1 "CasiOak" with slim design, shock resistant, 200m water resistant. Perfect for sport and outdoor enthusiasts.',
                'product_info' => "Authentic watch, 100% new\nPackage includes: box, manual, warranty card\n5-year warranty",
                'deal_info' => null,
                'price_jpy' => 15400,
                'price_vnd' => 216000,
                'original_price_jpy' => null,
                'original_price_vnd' => null,
                'points' => 25,
                'gender' => 'male',
                'movement_type' => 'quartz',
                'condition' => 'new',
                'attributes' => [
                    'case_size' => 45.4,
                    'case_material' => 'Carbon Resin',
                    'band_material' => 'Resin',
                    'dial_color' => 'Black',
                    'glass_material' => 'Mineral',
                    'water_resistance' => '20ATM',
                ],
                'stock_quantity' => 15,
                'warranty_months' => 60,
                'is_active' => true,
                'is_featured' => false,
                'images' => [
                    ['image_url' => '/storage/shop/products/ga-2100-1a1-1.jpg', 'is_primary' => true],
                    ['image_url' => '/storage/shop/products/ga-2100-1a1-2.jpg', 'is_primary' => false],
                ],
            ],
            [
                'category_id' => $catMenAutomatic?->id,
                'brand_id' => $seiko?->id,
                'name' => 'Seiko Presage Cocktail Time SRPB43J1',
                'slug' => 'seiko-presage-srpb43j1',
                'sku' => 'SRPB43J1',
                'short_description' => 'Seiko Presage men automatic watch, Cocktail Time series',
                'description' => 'Seiko Presage Cocktail Time SRPB43J1 inspired by cocktails, featuring a beautiful blue gradient dial. Powered by the reliable 4R35 automatic movement.',
                'product_info' => "Authentic watch, 100% new\nPackage includes: box, manual, warranty card\n5-year warranty",
                'deal_info' => "Free replacement leather strap worth JPY 3,000",
                'price_jpy' => 52800,
                'price_vnd' => 742000,
                'original_price_jpy' => 60000,
                'original_price_vnd' => 843000,
                'points' => 87,
                'gender' => 'male',
                'movement_type' => 'automatic',
                'condition' => 'new',
                'attributes' => [
                    'case_size' => 40.5,
                    'case_material' => 'Stainless Steel',
                    'band_material' => 'Leather',
                    'dial_color' => 'Blue Gradient',
                    'glass_material' => 'Hardlex',
                    'water_resistance' => '5ATM',
                    'power_reserve' => '41 hours',
                ],
                'stock_quantity' => 8,
                'warranty_months' => 60,
                'is_active' => true,
                'is_featured' => true,
                'images' => [
                    ['image_url' => '/storage/shop/products/srpb43j1-1.jpg', 'is_primary' => true],
                    ['image_url' => '/storage/shop/products/srpb43j1-2.jpg', 'is_primary' => false],
                ],
            ],
            [
                'category_id' => $catWomenQuartz?->id,
                'brand_id' => $citizen?->id,
                'name' => 'Citizen Eco-Drive EM0990-81Y',
                'slug' => 'citizen-eco-drive-em0990-81y',
                'sku' => 'EM0990-81Y',
                'short_description' => 'Citizen Eco-Drive women solar powered watch',
                'description' => 'Citizen Eco-Drive EM0990-81Y uses Eco-Drive solar technology, no battery replacement needed. Elegant design with mother-of-pearl dial.',
                'product_info' => "Authentic watch, 100% new\nPackage includes: box, manual, warranty card\n5-year warranty",
                'deal_info' => null,
                'price_jpy' => 38500,
                'price_vnd' => 541000,
                'original_price_jpy' => null,
                'original_price_vnd' => null,
                'points' => 63,
                'gender' => 'female',
                'movement_type' => 'solar',
                'condition' => 'new',
                'attributes' => [
                    'case_size' => 30,
                    'case_material' => 'Rose Gold Plated Stainless Steel',
                    'band_material' => 'Rose Gold Plated Stainless Steel',
                    'dial_color' => 'Mother of Pearl',
                    'glass_material' => 'Sapphire',
                    'water_resistance' => '5ATM',
                ],
                'stock_quantity' => 6,
                'warranty_months' => 60,
                'is_active' => true,
                'is_featured' => false,
                'images' => [
                    ['image_url' => '/storage/shop/products/em0990-81y-1.jpg', 'is_primary' => true],
                    ['image_url' => '/storage/shop/products/em0990-81y-2.jpg', 'is_primary' => false],
                ],
            ],
            [
                'category_id' => $catWomenAutomatic?->id,
                'brand_id' => $orientStar?->id,
                'name' => 'Orient Star Classic Semi Skeleton RK-ND0101S',
                'slug' => 'orient-star-rk-nd0101s',
                'sku' => 'RK-ND0101S',
                'short_description' => 'Orient Star women automatic watch with semi skeleton design',
                'description' => 'Orient Star Classic Semi Skeleton RK-ND0101S with exquisite open heart at 3 o\'clock position, Sapphire crystal glass. Premium product for women.',
                'product_info' => "Authentic watch, 100% new\nPackage includes: box, manual, warranty card\nGold warranty 5 years at Hoang Hai Watch\nShipping to Vietnam supported",
                'deal_info' => null,
                'price_jpy' => 68000,
                'price_vnd' => 956000,
                'original_price_jpy' => 75000,
                'original_price_vnd' => 1054000,
                'points' => 112,
                'gender' => 'female',
                'movement_type' => 'automatic',
                'condition' => 'new',
                'attributes' => [
                    'case_size' => 30.5,
                    'case_material' => 'Stainless Steel',
                    'band_material' => 'Leather',
                    'dial_color' => 'White Mother of Pearl',
                    'glass_material' => 'Sapphire',
                    'water_resistance' => '5ATM',
                    'power_reserve' => '40 hours',
                ],
                'stock_quantity' => 4,
                'warranty_months' => 60,
                'is_active' => true,
                'is_featured' => true,
                'images' => [
                    ['image_url' => '/storage/shop/products/rk-nd0101s-1.jpg', 'is_primary' => true],
                    ['image_url' => '/storage/shop/products/rk-nd0101s-2.jpg', 'is_primary' => false],
                ],
            ],
            [
                'category_id' => $catMenQuartz?->id,
                'brand_id' => $tissot?->id,
                'name' => 'Tissot PRX T137.410.11.041.00',
                'slug' => 'tissot-prx-t137-410-11-041-00',
                'sku' => 'T137.410.11.041.00',
                'short_description' => 'Tissot PRX men quartz watch with retro-modern design',
                'description' => 'Tissot PRX T137.410.11.041.00 sports-elegant style with blue dial and integrated steel bracelet. Inspired by the legendary PRX model from the 70s.',
                'product_info' => "Authentic watch, 100% new\nPackage includes: box, manual, warranty card\nInternational warranty 2 years by Swatch Group\nShipping to Vietnam supported",
                'deal_info' => "JPY 5,000 off when purchased with replacement leather strap",
                'price_jpy' => 46200,
                'price_vnd' => 649000,
                'original_price_jpy' => 51200,
                'original_price_vnd' => 720000,
                'points' => 76,
                'gender' => 'male',
                'movement_type' => 'quartz',
                'condition' => 'new',
                'attributes' => [
                    'case_size' => 40,
                    'case_material' => '316L Stainless Steel',
                    'band_material' => 'Integrated Stainless Steel',
                    'dial_color' => 'Blue',
                    'glass_material' => 'Sapphire',
                    'water_resistance' => '10ATM',
                ],
                'stock_quantity' => 10,
                'warranty_months' => 24,
                'is_active' => true,
                'is_featured' => true,
                'images' => [
                    ['image_url' => '/storage/shop/products/t137-410-1.jpg', 'is_primary' => true],
                    ['image_url' => '/storage/shop/products/t137-410-2.jpg', 'is_primary' => false],
                ],
            ],
            [
                'category_id' => $catWomenFashion?->id,
                'brand_id' => $danielWellington?->id,
                'name' => 'Daniel Wellington Petite Melrose DW00100163',
                'slug' => 'daniel-wellington-petite-melrose-dw00100163',
                'sku' => 'DW00100163',
                'short_description' => 'Daniel Wellington women fashion watch with rose gold mesh band',
                'description' => 'Daniel Wellington Petite Melrose DW00100163 with minimalist design, elegant white dial and rose gold mesh band. Suitable for office and casual wear.',
                'product_info' => "Authentic watch, 100% new\nPackage includes: box, manual, warranty card\n2-year official warranty",
                'deal_info' => null,
                'price_jpy' => 22000,
                'price_vnd' => 309000,
                'original_price_jpy' => null,
                'original_price_vnd' => null,
                'points' => 36,
                'gender' => 'female',
                'movement_type' => 'quartz',
                'condition' => 'new',
                'attributes' => [
                    'case_size' => 28,
                    'case_material' => 'Rose Gold Plated Stainless Steel',
                    'band_material' => 'Rose Gold Mesh Stainless Steel',
                    'dial_color' => 'White',
                    'glass_material' => 'Mineral',
                    'water_resistance' => '3ATM',
                ],
                'stock_quantity' => 12,
                'warranty_months' => 24,
                'is_active' => true,
                'is_featured' => false,
                'images' => [
                    ['image_url' => '/storage/shop/products/dw00100163-1.jpg', 'is_primary' => true],
                    ['image_url' => '/storage/shop/products/dw00100163-2.jpg', 'is_primary' => false],
                ],
            ],
            [
                'category_id' => $catMenQuartz?->id,
                'brand_id' => $fossil?->id,
                'name' => 'Fossil Neutra Chronograph FS5453',
                'slug' => 'fossil-neutra-chronograph-fs5453',
                'sku' => 'FS5453',
                'short_description' => 'Fossil Neutra men chronograph watch with vintage style',
                'description' => 'Fossil Neutra Chronograph FS5453 with chronograph movement, cream vintage dial and brown leather strap. Classic yet modern, perfect for retro style lovers.',
                'product_info' => "Authentic watch, 100% new\nPackage includes: tin box, manual, warranty card\n2-year official Fossil warranty",
                'deal_info' => null,
                'price_jpy' => 18700,
                'price_vnd' => 263000,
                'original_price_jpy' => 22000,
                'original_price_vnd' => 309000,
                'points' => 31,
                'gender' => 'male',
                'movement_type' => 'quartz',
                'condition' => 'new',
                'attributes' => [
                    'case_size' => 44,
                    'case_material' => 'Stainless Steel',
                    'band_material' => 'Leather',
                    'dial_color' => 'Cream',
                    'glass_material' => 'Mineral',
                    'water_resistance' => '5ATM',
                ],
                'stock_quantity' => 7,
                'warranty_months' => 24,
                'is_active' => true,
                'is_featured' => false,
                'images' => [
                    ['image_url' => '/storage/shop/products/fs5453-1.jpg', 'is_primary' => true],
                    ['image_url' => '/storage/shop/products/fs5453-2.jpg', 'is_primary' => false],
                ],
            ],
            [
                'category_id' => $catMenSport?->id,
                'brand_id' => $seiko?->id,
                'name' => 'Seiko Prospex Solar Diver SNE589P1',
                'slug' => 'seiko-prospex-sne589p1',
                'sku' => 'SNE589P1',
                'short_description' => 'Seiko Prospex men solar powered diver watch',
                'description' => 'Seiko Prospex Solar Diver SNE589P1 is a professional diver watch with 200m water resistance, solar powered. Unidirectional bezel, rugged design for underwater adventures.',
                'product_info' => "Authentic watch, 100% new\nPackage includes: box, manual, warranty card\n5-year warranty\nShipping to Vietnam supported",
                'deal_info' => "Free replacement rubber strap worth JPY 4,000",
                'price_jpy' => 55000,
                'price_vnd' => 773000,
                'original_price_jpy' => null,
                'original_price_vnd' => null,
                'points' => 91,
                'gender' => 'male',
                'movement_type' => 'solar',
                'condition' => 'new',
                'attributes' => [
                    'case_size' => 43.5,
                    'case_material' => 'Stainless Steel',
                    'band_material' => 'Silicone',
                    'dial_color' => 'Black',
                    'glass_material' => 'Hardlex',
                    'water_resistance' => '20ATM',
                    'bezel' => 'Unidirectional',
                ],
                'stock_quantity' => 5,
                'warranty_months' => 60,
                'is_active' => true,
                'is_featured' => true,
                'images' => [
                    ['image_url' => '/storage/shop/products/sne589p1-1.jpg', 'is_primary' => true],
                    ['image_url' => '/storage/shop/products/sne589p1-2.jpg', 'is_primary' => false],
                ],
            ],
        ];

        foreach ($products as $index => $data) {
            $images = $data['images'];
            unset($data['images']);

            $product = Product::firstOrCreate(
                ['sku' => $data['sku']],
                array_merge($data, ['sort_order' => $index])
            );

            foreach ($images as $imgIndex => $image) {
                ProductImage::firstOrCreate(
                    ['product_id' => $product->id, 'image_url' => $image['image_url']],
                    [
                        'product_id' => $product->id,
                        'image_url' => $image['image_url'],
                        'is_primary' => $image['is_primary'],
                        'sort_order' => $imgIndex,
                    ]
                );
            }
        }
    }
}
