<?php
// database/seeders/MenuItemCustomizationSeeder.php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\MenuItemCustomization;
use Illuminate\Database\Seeder;

class MenuItemCustomizationSeeder extends Seeder
{
    public function run(): void
    {
        // RAMEN CUSTOMIZATIONS (Category 2)
        $ramenItems = MenuItem::where('category_id', 2)->get();

        foreach ($ramenItems as $ramen) {
            // Broth Type - Only for ramen items that need broth selection
            if (str_contains(strtolower($ramen->name), 'ramen')) {
                MenuItemCustomization::create([
                    'menu_item_id' => $ramen->id,
                    'name' => 'Broth Type',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        ['value' => 'Tonkotsu (Pork Bone)', 'price' => 0],
                        ['value' => 'Shoyu (Soy Sauce)', 'price' => 0],
                        ['value' => 'Miso (Fermented Soybean)', 'price' => 0],
                        ['value' => 'Shio (Salt)', 'price' => 0],
                    ],
                    'sort_order' => 1,
                ]);
            }

            // Spice Level - For all ramen except vegetarian
            if (!$ramen->is_vegetarian) {
                MenuItemCustomization::create([
                    'menu_item_id' => $ramen->id,
                    'name' => 'Spice Level',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        ['value' => 'Mild', 'price' => 0],
                        ['value' => 'Medium', 'price' => 0.50],
                        ['value' => 'Hot', 'price' => 1.00],
                        ['value' => 'Extra Hot', 'price' => 1.50],
                    ],
                    'sort_order' => 2,
                ]);
            }

            // Extra Toppings - For all ramen
            MenuItemCustomization::create([
                'menu_item_id' => $ramen->id,
                'name' => 'Extra Toppings',
                'type' => 'multiple',
                'required' => false,
                'options' => [
                    ['value' => 'Extra Chashu Pork', 'price' => 2.50],
                    ['value' => 'Soft Boiled Egg', 'price' => 1.50],
                    ['value' => 'Bamboo Shoots', 'price' => 1.00],
                    ['value' => 'Corn', 'price' => 1.00],
                    ['value' => 'Seaweed', 'price' => 0.50],
                    ['value' => 'Green Onions', 'price' => 0.50],
                    ['value' => 'Extra Mushrooms', 'price' => 1.00],
                ],
                'sort_order' => 3,
            ]);

            // Noodle Firmness - For all ramen
            MenuItemCustomization::create([
                'menu_item_id' => $ramen->id,
                'name' => 'Noodle Firmness',
                'type' => 'select',
                'required' => false,
                'options' => [
                    ['value' => 'Soft', 'price' => 0],
                    ['value' => 'Medium', 'price' => 0],
                    ['value' => 'Firm', 'price' => 0],
                ],
                'sort_order' => 4,
            ]);
        }

        // SUSHI CUSTOMIZATIONS (Category 3)
        $sushiItems = MenuItem::where('category_id', 3)->get();

        foreach ($sushiItems as $sushi) {
            // Sauce Options - For all sushi except platter
            if (!str_contains(strtolower($sushi->name), 'platter')) {
                MenuItemCustomization::create([
                    'menu_item_id' => $sushi->id,
                    'name' => 'Sauce Options',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        ['value' => 'Soy Sauce', 'price' => 0],
                        ['value' => 'Spicy Mayo', 'price' => 0.50],
                        ['value' => 'Eel Sauce', 'price' => 0.50],
                        ['value' => 'Ponzu Sauce', 'price' => 0.75],
                    ],
                    'sort_order' => 1,
                ]);
            }

            // Extra Toppings - For all sushi
            $toppings = [
                ['value' => 'Sesame Seeds', 'price' => 0.25],
                ['value' => 'Extra Fish', 'price' => 2.00],
                ['value' => 'Avocado', 'price' => 1.00],
                ['value' => 'Cucumber', 'price' => 0.50],
            ];

            // Add vegetarian-friendly toppings for veg sushi
            if ($sushi->is_vegetarian) {
                $toppings = array_filter($toppings, fn($t) => $t['value'] !== 'Extra Fish');
            }

            MenuItemCustomization::create([
                'menu_item_id' => $sushi->id,
                'name' => 'Extra Toppings',
                'type' => 'multiple',
                'required' => false,
                'options' => $toppings,
                'sort_order' => 2,
            ]);

            // Rice Type - For nigiri and rolls
            if (str_contains(strtolower($sushi->name), 'nigiri') || str_contains(strtolower($sushi->name), 'roll')) {
                MenuItemCustomization::create([
                    'menu_item_id' => $sushi->id,
                    'name' => 'Rice Type',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        ['value' => 'White Rice', 'price' => 0],
                        ['value' => 'Brown Rice', 'price' => 0.50],
                        ['value' => 'Light Rice', 'price' => 0],
                        ['value' => 'No Rice', 'price' => -1.00],
                    ],
                    'sort_order' => 3,
                ]);
            }
        }

        // BENTO CUSTOMIZATIONS (Category 4)
        $bentoItems = MenuItem::where('category_id', 4)->get();

        foreach ($bentoItems as $bento) {
            // Side Dish Selection
            MenuItemCustomization::create([
                'menu_item_id' => $bento->id,
                'name' => 'Side Dish',
                'type' => 'select',
                'required' => true,
                'options' => [
                    ['value' => 'Miso Soup', 'price' => 0],
                    ['value' => 'Green Salad', 'price' => 0],
                    ['value' => 'Seaweed Salad', 'price' => 1.50],
                    ['value' => 'Edamame', 'price' => 2.00],
                ],
                'sort_order' => 1,
            ]);

            // Rice Type
            MenuItemCustomization::create([
                'menu_item_id' => $bento->id,
                'name' => 'Rice Type',
                'type' => 'select',
                'required' => true,
                'options' => [
                    ['value' => 'White Rice', 'price' => 0],
                    ['value' => 'Brown Rice', 'price' => 0.50],
                    ['value' => 'Fried Rice', 'price' => 1.50],
                ],
                'sort_order' => 2,
            ]);

            // Sauce Options - For teriyaki and similar bentos
            if (str_contains(strtolower($bento->name), 'teriyaki') || str_contains(strtolower($bento->name), 'chicken') || str_contains(strtolower($bento->name), 'beef')) {
                MenuItemCustomization::create([
                    'menu_item_id' => $bento->id,
                    'name' => 'Sauce',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        ['value' => 'Teriyaki Sauce', 'price' => 0],
                        ['value' => 'Ginger Sauce', 'price' => 0],
                        ['value' => 'Spicy Sauce', 'price' => 0.50],
                        ['value' => 'Ponzu Sauce', 'price' => 0.50],
                    ],
                    'sort_order' => 3,
                ]);
            }
        }

        // APPETIZERS CUSTOMIZATIONS (Category 1) - Simple ones
        $appetizerItems = MenuItem::where('category_id', 1)->get();

        foreach ($appetizerItems as $appetizer) {
            // Dipping Sauce for appropriate items
            if (str_contains(strtolower($appetizer->name), 'gyoza') ||
                str_contains(strtolower($appetizer->name), 'tempura') ||
                str_contains(strtolower($appetizer->name), 'tofu')) {
                MenuItemCustomization::create([
                    'menu_item_id' => $appetizer->id,
                    'name' => 'Dipping Sauce',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        ['value' => 'Soy Sauce', 'price' => 0],
                        ['value' => 'Ponzu Sauce', 'price' => 0.50],
                        ['value' => 'Spicy Mayo', 'price' => 0.50],
                        ['value' => 'Teriyaki Sauce', 'price' => 0.50],
                    ],
                    'sort_order' => 1,
                ]);
            }

            // Spice Level for Takoyaki
            if (str_contains(strtolower($appetizer->name), 'takoyaki')) {
                MenuItemCustomization::create([
                    'menu_item_id' => $appetizer->id,
                    'name' => 'Spice Level',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        ['value' => 'Mild', 'price' => 0],
                        ['value' => 'Medium', 'price' => 0],
                        ['value' => 'Spicy', 'price' => 0.50],
                    ],
                    'sort_order' => 2,
                ]);
            }
        }

        // BEVERAGES CUSTOMIZATIONS (Category 6)
        $beverageItems = MenuItem::where('category_id', 6)->get();

        foreach ($beverageItems as $beverage) {
            // Temperature for hot drinks
            if (str_contains(strtolower($beverage->name), 'tea') || str_contains(strtolower($beverage->name), 'latte')) {
                MenuItemCustomization::create([
                    'menu_item_id' => $beverage->id,
                    'name' => 'Temperature',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        ['value' => 'Hot', 'price' => 0],
                        ['value' => 'Iced', 'price' => 0],
                    ],
                    'sort_order' => 1,
                ]);

                // Sweetness Level
                MenuItemCustomization::create([
                    'menu_item_id' => $beverage->id,
                    'name' => 'Sweetness',
                    'type' => 'select',
                    'required' => false,
                    'options' => [
                        ['value' => 'No Sugar', 'price' => 0],
                        ['value' => 'Less Sugar', 'price' => 0],
                        ['value' => 'Regular', 'price' => 0],
                        ['value' => 'Extra Sugar', 'price' => 0],
                    ],
                    'sort_order' => 2,
                ]);
            }

            // Size for drinks
            MenuItemCustomization::create([
                'menu_item_id' => $beverage->id,
                'name' => 'Size',
                'type' => 'select',
                'required' => true,
                'options' => [
                    ['value' => 'Regular', 'price' => 0],
                    ['value' => 'Large', 'price' => 1.00],
                ],
                'sort_order' => 3,
            ]);
        }

        // DESSERT CUSTOMIZATIONS (Category 5)
        $dessertItems = MenuItem::where('category_id', 5)->get();

        foreach ($dessertItems as $dessert) {
            // Ice Cream Flavors
            if (str_contains(strtolower($dessert->name), 'ice cream')) {
                MenuItemCustomization::create([
                    'menu_item_id' => $dessert->id,
                    'name' => 'Flavor',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        ['value' => 'Matcha', 'price' => 0],
                        ['value' => 'Vanilla', 'price' => 0],
                        ['value' => 'Chocolate', 'price' => 0],
                        ['value' => 'Strawberry', 'price' => 0],
                        ['value' => 'Black Sesame', 'price' => 0.50],
                    ],
                    'sort_order' => 1,
                ]);

                // Toppings for ice cream
                MenuItemCustomization::create([
                    'menu_item_id' => $dessert->id,
                    'name' => 'Toppings',
                    'type' => 'multiple',
                    'required' => false,
                    'options' => [
                        ['value' => 'Red Bean', 'price' => 0.50],
                        ['value' => 'Mochi Pieces', 'price' => 0.75],
                        ['value' => 'Whipped Cream', 'price' => 0.50],
                        ['value' => 'Chocolate Sauce', 'price' => 0.50],
                    ],
                    'sort_order' => 2,
                ]);
            }

            // Filling for Taiyaki
            if (str_contains(strtolower($dessert->name), 'taiyaki')) {
                MenuItemCustomization::create([
                    'menu_item_id' => $dessert->id,
                    'name' => 'Filling',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        ['value' => 'Red Bean', 'price' => 0],
                        ['value' => 'Chocolate', 'price' => 0.50],
                        ['value' => 'Custard', 'price' => 0.50],
                        ['value' => 'Sweet Potato', 'price' => 0.50],
                    ],
                    'sort_order' => 1,
                ]);
            }
        }
    }
}
