<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banhMiCategory = Category::where('name', 'Bánh Mì')->first();
        $cafeCategory = Category::where('name', 'Cà Phê')->first();
        $traCategory = Category::where('name', 'Trà')->first();

        // Bánh mì
        Product::create([
            'name' => 'Bánh Mì Thịt',
            'description' => 'Bánh mì thịt nướng thơm ngon',
            'image' => 'products/banh-mi-thit.jpg',
            'category_id' => $banhMiCategory->id,
            'status' => 'active'
        ]);

        Product::create([
            'name' => 'Bánh Mì Chả',
            'description' => 'Bánh mì kẹp chả lụa truyền thống',
            'image' => 'products/banh-mi-cha.jpg',
            'category_id' => $banhMiCategory->id,
            'status' => 'active'
        ]);

        // Cà phê
        Product::create([
            'name' => 'Cà Phê Đen',
            'description' => 'Cà phê đen đậm đà',
            'image' => 'products/ca-phe-den.jpg',
            'category_id' => $cafeCategory->id,
            'status' => 'active'
        ]);

        Product::create([
            'name' => 'Cà Phê Sữa',
            'description' => 'Cà phê sữa béo ngậy',
            'image' => 'products/ca-phe-sua.jpg',
            'category_id' => $cafeCategory->id,
            'status' => 'active'
        ]);

        // Trà
        Product::create([
            'name' => 'Trà Đào',
            'description' => 'Trà đào thơm mát',
            'image' => 'products/tra-dao.jpg',
            'category_id' => $traCategory->id,
            'status' => 'active'
        ]);

        Product::create([
            'name' => 'Trà Chanh',
            'description' => 'Trà chanh giải khát',
            'image' => 'products/tra-chanh.jpg',
            'category_id' => $traCategory->id,
            'status' => 'active'
        ]);
    }
}
