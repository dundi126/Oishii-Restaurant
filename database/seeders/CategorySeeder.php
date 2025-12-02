<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Appetizers',
                'color' => 'green',
                'icon' => 'fas fa-utensils',
                'sort_order' => 1,
            ],
            [
                'name' => 'Ramen',
                'color' => 'blue',
                'icon' => 'fas fa-bowl-food',
                'sort_order' => 2,
            ],
            [
                'name' => 'Sushi',
                'color' => 'red',
                'icon' => 'fas fa-fish',
                'sort_order' => 3,
            ],
            [
                'name' => 'Bento',
                'color' => 'yellow',
                'icon' => 'fas fa-box',
                'sort_order' => 4,
            ],
            [
                'name' => 'Desert',
                'color' => 'pink',
                'icon' => 'fas fa-ice-cream',
                'sort_order' => 5,
            ],
            [
                'name' => 'Beverages',
                'color' => 'indigo',
                'icon' => 'fas fa-glass-whiskey',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
