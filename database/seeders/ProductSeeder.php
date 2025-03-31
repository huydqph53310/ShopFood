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
        $products = [
            [
                'name' => 'Classic Burger',
                'code' => 'BURG001',
                'description' => 'Juicy beef patty with fresh lettuce, tomatoes, and special sauce',
                'price' => 12.99,
                'image' => 'menu/menu-item-1.png',
                'category_id' => 1,
                'status' => 1
            ],
            [
                'name' => 'Margherita Pizza',
                'code' => 'PIZZ001',
                'description' => 'Fresh tomatoes, mozzarella, basil, and olive oil',
                'price' => 14.99,
                'image' => 'menu/menu-item-2.png',
                'category_id' => 2,
                'status' => 1
            ],
            [
                'name' => 'Caesar Salad',
                'code' => 'SALD001',
                'description' => 'Crisp romaine lettuce, croutons, parmesan cheese with Caesar dressing',
                'price' => 8.99,
                'image' => 'menu/menu-item-3.png',
                'category_id' => 3,
                'status' => 1
            ],
            [
                'name' => 'Spaghetti Carbonara',
                'code' => 'PAST001',
                'description' => 'Pasta with eggs, cheese, pancetta, and black pepper',
                'price' => 13.99,
                'image' => 'menu/menu-item-4.png',
                'category_id' => 2,
                'status' => 1
            ],
            [
                'name' => 'Grilled Salmon',
                'code' => 'FISH001',
                'description' => 'Fresh salmon fillet with seasonal vegetables',
                'price' => 24.99,
                'image' => 'menu/menu-item-5.png',
                'category_id' => 4,
                'status' => 1
            ],
            [
                'name' => 'Chicken Wings',
                'code' => 'WING001',
                'description' => 'Crispy chicken wings with your choice of sauce',
                'price' => 11.99,
                'image' => 'menu/menu-item-6.png',
                'category_id' => 1,
                'status' => 1
            ],
            [
                'name' => 'Vegetable Stir Fry',
                'code' => 'VEGG001',
                'description' => 'Mixed vegetables in a light soy sauce',
                'price' => 9.99,
                'image' => 'menu/menu-item-7.png',
                'category_id' => 3,
                'status' => 1
            ],
            [
                'name' => 'Beef Tenderloin',
                'code' => 'BEEF001',
                'description' => 'Premium cut of beef with red wine sauce',
                'price' => 29.99,
                'image' => 'menu/menu-item-8.png',
                'category_id' => 4,
                'status' => 1
            ],
            [
                'name' => 'Mushroom Risotto',
                'code' => 'RICE001',
                'description' => 'Creamy risotto with mixed mushrooms',
                'price' => 16.99,
                'image' => 'menu/menu-item-9.png',
                'category_id' => 2,
                'status' => 1
            ],
            [
                'name' => 'Chocolate Cake',
                'code' => 'DESS001',
                'description' => 'Rich chocolate cake with ganache',
                'price' => 6.99,
                'image' => 'menu/menu-item-10.png',
                'category_id' => 5,
                'status' => 1
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
