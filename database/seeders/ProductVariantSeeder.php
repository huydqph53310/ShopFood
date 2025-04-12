<?php

namespace Database\Seeders;

use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\Size;
use App\Models\Topping;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    public function run()
    {
        // Tạo sizes
        $sizes = [
            ['name' => 'Nhỏ'],
            ['name' => 'Vừa'],
            ['name' => 'Lớn']
        ];
        foreach ($sizes as $size) {
            Size::firstOrCreate($size);
        }

        // Tạo toppings
        $toppings = [
            ['name' => 'Không', 'price' => 0],
            ['name' => 'Trân Châu', 'price' => 5000],
            ['name' => 'Thạch', 'price' => 5000],
            ['name' => 'Jelly', 'price' => 5000]
        ];
        foreach ($toppings as $topping) {
            Topping::firstOrCreate($topping);
        }

        // Lấy các size và topping mặc định
        $sizeNho = Size::where('name', 'Nhỏ')->first();
        $sizeVua = Size::where('name', 'Vừa')->first();
        $sizeLon = Size::where('name', 'Lớn')->first();
        $toppingKhong = Topping::where('name', 'Không')->first();

        // Tạo variants cho từng sản phẩm
        $products = Product::all();
        foreach ($products as $product) {
            // Bánh mì chỉ có size vừa
            if (str_contains($product->name, 'Bánh Mì')) {
                ProductVariant::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'size_id' => $sizeVua->id,
                        'topping_id' => $toppingKhong->id
                    ],
                    [
                        'price' => 25000,
                        'sale' => 0,
                        'stock' => 100,
                        'image' => str_replace(' ', '-', strtolower($product->name)) . '.jpg'
                    ]
                );
            }
            // Cà phê có 3 size
            elseif (str_contains($product->name, 'Cà Phê')) {
                $sizes = [$sizeNho, $sizeVua, $sizeLon];
                $prices = [15000, 20000, 25000];
                foreach ($sizes as $index => $size) {
                    ProductVariant::firstOrCreate(
                        [
                            'product_id' => $product->id,
                            'size_id' => $size->id,
                            'topping_id' => $toppingKhong->id
                        ],
                        [
                            'price' => $prices[$index],
                            'sale' => 0,
                            'stock' => 100,
                            'image' => str_replace(' ', '-', strtolower($product->name)) . '-' . strtolower($size->name) . '.jpg'
                        ]
                    );
                }
            }
            // Trà có 3 size và các topping
            else {
                $sizes = [$sizeNho, $sizeVua, $sizeLon];
                $prices = [20000, 25000, 30000];
                foreach ($sizes as $index => $size) {
                    foreach (Topping::all() as $topping) {
                        ProductVariant::firstOrCreate(
                            [
                                'product_id' => $product->id,
                                'size_id' => $size->id,
                                'topping_id' => $topping->id
                            ],
                            [
                                'price' => $prices[$index],
                                'sale' => 0,
                                'stock' => 100,
                                'image' => str_replace(' ', '-', strtolower($product->name)) . '-' . strtolower($size->name) . '.jpg'
                            ]
                        );
                    }
                }
            }
        }
    }
}
