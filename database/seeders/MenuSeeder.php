<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Menu Items
        $menuItems = [
            // Appetizers (Category 1)
            ['name' => 'Edamame', 'description' => 'Steamed soybeans with sea salt', 'price' => 5.99, 'category_id' => 1, 'is_vegetarian' => true, 'rating' => 4.5, 'review_count' => 120, 'image_path' => 'menu-images/edamame.jpg'],
            ['name' => 'Gyoza', 'description' => 'Pan-fried pork dumplings', 'price' => 7.99, 'category_id' => 1, 'is_vegetarian' => false, 'rating' => 4.7, 'review_count' => 95, 'image_path' => 'menu-images/gyoza.jpg'],
            ['name' => 'Agedashi Tofu', 'description' => 'Deep-fried tofu in tentsuyu sauce', 'price' => 6.99, 'category_id' => 1, 'is_vegetarian' => true, 'rating' => 4.6, 'review_count' => 80, 'image_path' => 'menu-images/agedashi-tofu.jpg'],
            ['name' => 'Takoyaki', 'description' => 'Octopus balls with special sauce', 'price' => 8.99, 'category_id' => 1, 'is_vegetarian' => false, 'rating' => 4.8, 'review_count' => 110, 'image_path' => 'menu-images/takoyaki.jpg'],
            ['name' => 'Vegetable Tempura', 'description' => 'Assorted vegetables lightly battered and fried', 'price' => 9.99, 'category_id' => 1, 'is_vegetarian' => true, 'rating' => 4.4, 'review_count' => 75, 'image_path' => 'menu-images/vegetable-tempura.jpg'],

            // Ramen (Category 2)
            ['name' => 'Tonkotsu Ramen', 'description' => 'Rich pork bone broth with chashu pork, soft-boiled egg, and fresh noodles', 'price' => 14.99, 'category_id' => 2, 'is_vegetarian' => false, 'rating' => 4.8, 'review_count' => 200, 'image_path' => 'menu-images/tonkotsu-ramen.jpg'],
            ['name' => 'Shoyu Ramen', 'description' => 'Soy sauce based broth with chicken', 'price' => 13.99, 'category_id' => 2, 'is_vegetarian' => false, 'rating' => 4.6, 'review_count' => 150, 'image_path' => 'menu-images/shoyu-ramen.jpg'],
            ['name' => 'Miso Ramen', 'description' => 'Fermented soybean paste broth', 'price' => 14.49, 'category_id' => 2, 'is_vegetarian' => false, 'rating' => 4.7, 'review_count' => 130, 'image_path' => 'menu-images/miso-ramen.jpg'],
            ['name' => 'Vegetarian Ramen', 'description' => 'Vegetable broth with tofu and mushrooms', 'price' => 12.99, 'category_id' => 2, 'is_vegetarian' => true, 'rating' => 4.5, 'review_count' => 85, 'image_path' => 'menu-images/vegetarian-ramen.jpg'],
            ['name' => 'Spicy Miso Ramen', 'description' => 'Spicy miso broth with ground pork', 'price' => 15.49, 'category_id' => 2, 'is_vegetarian' => false, 'rating' => 4.9, 'review_count' => 180, 'image_path' => 'menu-images/spicy-miso-ramen.jpg'],

            // Sushi (Category 3)
            ['name' => 'Salmon Nigiri', 'description' => 'Fresh salmon over seasoned rice', 'price' => 5.99, 'category_id' => 3, 'is_vegetarian' => false, 'rating' => 4.9, 'review_count' => 150, 'image_path' => 'menu-images/salmon-nigiri.jpg'],
            ['name' => 'California Roll', 'description' => 'Crab, avocado, and cucumber roll', 'price' => 8.99, 'category_id' => 3, 'is_vegetarian' => false, 'rating' => 4.5, 'review_count' => 180, 'image_path' => 'menu-images/california-roll.jpg'],
            ['name' => 'Dragon Roll', 'description' => 'Eel, avocado, and cucumber roll', 'price' => 12.99, 'category_id' => 3, 'is_vegetarian' => false, 'rating' => 4.8, 'review_count' => 120, 'image_path' => 'menu-images/dragon-roll.jpg'],
            ['name' => 'Vegetable Roll', 'description' => 'Avocado, cucumber, and carrot roll', 'price' => 7.99, 'category_id' => 3, 'is_vegetarian' => true, 'rating' => 4.4, 'review_count' => 90, 'image_path' => 'menu-images/vegetable-roll.jpg'],
            ['name' => 'Spicy Tuna Roll', 'description' => 'Spicy tuna with mayo', 'price' => 10.99, 'category_id' => 3, 'is_vegetarian' => false, 'rating' => 4.7, 'review_count' => 160, 'image_path' => 'menu-images/spicy-tuna-roll.jpg'],
            ['name' => 'Sashimi Platter', 'description' => 'Assorted raw fish slices', 'price' => 18.99, 'category_id' => 3, 'is_vegetarian' => false, 'rating' => 4.9, 'review_count' => 95, 'image_path' => 'menu-images/sashimi-platter.jpg'],

            // Bento Boxes (Category 4)
            ['name' => 'Chicken Teriyaki Bento', 'description' => 'Grilled chicken with teriyaki sauce, rice, and vegetables', 'price' => 16.99, 'category_id' => 4, 'is_vegetarian' => false, 'rating' => 4.6, 'review_count' => 140, 'image_path' => 'menu-images/chicken-teriyaki-bento.jpg'],
            ['name' => 'Salmon Bento', 'description' => 'Grilled salmon with vegetables and rice', 'price' => 17.99, 'category_id' => 4, 'is_vegetarian' => false, 'rating' => 4.7, 'review_count' => 110, 'image_path' => 'menu-images/salmon-bento.jpg'],
            ['name' => 'Vegetable Tempura Bento', 'description' => 'Assorted tempura with rice and miso soup', 'price' => 15.99, 'category_id' => 4, 'is_vegetarian' => true, 'rating' => 4.5, 'review_count' => 85, 'image_path' => 'menu-images/vegetable-tempura-bento.jpg'],
            ['name' => 'Sushi Bento', 'description' => 'Assorted sushi and sashimi with sides', 'price' => 19.99, 'category_id' => 4, 'is_vegetarian' => false, 'rating' => 4.8, 'review_count' => 125, 'image_path' => 'menu-images/sushi-bento.jpg'],
            ['name' => 'Beef Bento', 'description' => 'Grilled beef with ginger sauce and rice', 'price' => 18.99, 'category_id' => 4, 'is_vegetarian' => false, 'rating' => 4.6, 'review_count' => 100, 'image_path' => 'menu-images/beef-bento.jpg'],

            // Desert (Category 5)
            ['name' => 'Matcha Ice Cream', 'description' => 'Green tea flavored ice cream', 'price' => 6.99, 'category_id' => 5, 'is_vegetarian' => true, 'rating' => 4.8, 'review_count' => 200, 'image_path' => 'menu-images/matcha-ice-cream.jpg'],
            ['name' => 'Mochi Ice Cream', 'description' => 'Rice cake with ice cream filling', 'price' => 7.99, 'category_id' => 5, 'is_vegetarian' => true, 'rating' => 4.7, 'review_count' => 150, 'image_path' => 'menu-images/mochi-ice-cream.jpg'],
            ['name' => 'Dorayaki', 'description' => 'Red bean pancake sandwich', 'price' => 5.99, 'category_id' => 5, 'is_vegetarian' => true, 'rating' => 4.5, 'review_count' => 120, 'image_path' => 'menu-images/dorayaki.jpg'],
            ['name' => 'Taiyaki', 'description' => 'Fish-shaped cake with filling', 'price' => 6.49, 'category_id' => 5, 'is_vegetarian' => true, 'rating' => 4.6, 'review_count' => 95, 'image_path' => 'menu-images/taiyaki.jpg'],
            ['name' => 'Green Tea Cheesecake', 'description' => 'Matcha flavored cheesecake', 'price' => 8.99, 'category_id' => 5, 'is_vegetarian' => true, 'rating' => 4.9, 'review_count' => 180, 'image_path' => 'menu-images/green-tea-cheesecake.jpg'],

            // Beverages (Category 6)
            ['name' => 'Japanese Green Tea', 'description' => 'Hot or cold traditional green tea', 'price' => 3.99, 'category_id' => 6, 'is_vegetarian' => true, 'rating' => 4.4, 'review_count' => 250, 'image_path' => 'menu-images/japanese-green-tea.jpg'],
            ['name' => 'Ramune', 'description' => 'Japanese soda with marble stopper', 'price' => 4.99, 'category_id' => 6, 'is_vegetarian' => true, 'rating' => 4.6, 'review_count' => 180, 'image_path' => 'menu-images/ramune.jpg'],
            ['name' => 'Calpico', 'description' => 'Refreshing yogurt drink', 'price' => 4.49, 'category_id' => 6, 'is_vegetarian' => true, 'rating' => 4.5, 'review_count' => 140, 'image_path' => 'menu-images/calpico.jpg'],
            ['name' => 'Sake', 'description' => 'Japanese rice wine', 'price' => 7.99, 'category_id' => 6, 'is_vegetarian' => true, 'rating' => 4.7, 'review_count' => 120, 'image_path' => 'menu-images/sake.jpg'],
            ['name' => 'Asahi Beer', 'description' => 'Japanese lager', 'price' => 6.99, 'category_id' => 6, 'is_vegetarian' => true, 'rating' => 4.6, 'review_count' => 160, 'image_path' => 'menu-images/asahi-beer.jpg'],
            ['name' => 'Matcha Latte', 'description' => 'Green tea latte', 'price' => 5.49, 'category_id' => 6, 'is_vegetarian' => true, 'rating' => 4.8, 'review_count' => 190, 'image_path' => 'menu-images/matcha-latte.jpg'],
        ];

        foreach ($menuItems as $item) {
            MenuItem::create($item);
        }
    }
}
