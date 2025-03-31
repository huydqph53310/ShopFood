<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Appetizers',
                'description' => 'Start your meal with these delicious appetizers',
                'status' => 1
            ],
            [
                'name' => 'Main Courses',
                'description' => 'Hearty and satisfying main dishes',
                'status' => 1
            ],
            [
                'name' => 'Salads',
                'description' => 'Fresh and healthy salad options',
                'status' => 1
            ],
            [
                'name' => 'Seafood',
                'description' => 'Fresh seafood dishes',
                'status' => 1
            ],
            [
                'name' => 'Desserts',
                'description' => 'Sweet treats to end your meal',
                'status' => 1
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
