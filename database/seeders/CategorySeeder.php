<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Bánh Mì',
                'description' => 'Các loại bánh mì thơm ngon',
                'image' => 'categories/banh-mi.jpg',
            ],
            [
                'name' => 'Cà Phê',
                'description' => 'Cà phê rang xay nguyên chất',
                'image' => 'categories/ca-phe.jpg',
            ],
            [
                'name' => 'Trà',
                'description' => 'Các loại trà thơm ngon',
                'image' => 'categories/tra.jpg',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => $category['image'],
                'status' => 'active'
            ]);
        }
    }
}
