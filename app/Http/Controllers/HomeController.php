<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Category;
use App\Models\Cart;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        try {
            // Get featured menu items (top rated or most popular)
            $featuredMenus = MenuItem::with(['category', 'customizations'])
                ->where('is_active', true)
                ->orderBy('rating', 'desc')
                ->orderBy('review_count', 'desc')
                ->limit(6)
                ->get();

            // Get all categories for navigation
            $categories = Category::where('is_active', true)->get();

            // Get cart count if user is authenticated
            $user = auth()->user();
            $cartCount = $user ? Cart::getCartCount($user->id) : 0;
        } catch (\Exception $e) {
            // If database is not available, use empty collections
            $featuredMenus = collect();
            $categories = collect();
            $cartCount = 0;
        }

        return view('home', compact('featuredMenus', 'categories', 'cartCount'));
    }
}

