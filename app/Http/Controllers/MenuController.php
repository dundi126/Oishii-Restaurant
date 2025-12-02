<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class MenuController extends Controller
{

    // List all menu items
    public function index()
    {
        // Get menus with categories AND customizations
        $menus = MenuItem::with(['category', 'customizations'])
            ->where('is_active', true)
            ->get();

        $categories = Category::where('is_active', true)->get();
        $user = auth()->user();

        // Calculate cart count
        $cartCount = $user ? Cart::getCartCount($user->id) : 0;

        if (!$user) {
            Log::warning('Unauthenticated access attempt to menu index.');
            return view('welcome', compact('menus', 'categories'));
        }

        Log::info('User accessed menu index.', [
            'user_id' => $user->id,
            'role' => $user->role,
            'email' => $user->email ?? 'N/A'
        ]);

        switch ($user->role) {
            case 'admin':
                Log::info('Admin user accessing menu management.');
                return view('menus.index', compact('menus', 'categories'));

            case 'staff':
                Log::info('Staff user accessing staff dashboard.');
                return view('staff.index', compact('menus', 'categories'));

            case 'customer':
                Log::info('Customer user accessing public menu.');
                return view('customer.index', compact('menus', 'categories', 'cartCount'));

            default:
                Log::warning('User with unknown role accessed the system.', ['role' => $user->role]);
                return view('welcome');
        }
    }

    // Create menu item
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('menus.create', compact('categories'));
    }

    // Store new menu item
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_vegetarian' => 'boolean',
            'rating' => 'nullable|numeric|min:0|max:5',
            'review_count' => 'nullable|integer'
        ]);

        // Handle image upload - Store directly in public folder
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('menu-images'), $imageName);
            $validated['image_path'] = 'menu-images/' . $imageName;
        }

        MenuItem::create($validated);

        return redirect()->route('menus.index')->with('success', 'Menu item created successfully!');
    }

    // Update method - also fix this
    public function update(Request $request, MenuItem $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_vegetarian' => 'boolean',
            'rating' => 'nullable|numeric|min:0|max:5',
            'review_count' => 'nullable|integer'
        ]);

        // Handle image upload - Store directly in public folder
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('menu-images'), $imageName);
            $validated['image_path'] = 'menu-images/' . $imageName;
        }

        $menu->update($validated);

        return redirect()->route('menus.index')->with('success', 'Menu item updated successfully!');
    }

    public function edit(Request $request, MenuItem $menu)
    {
        try {
            // Log entry point
            Log::info('Menu Edit Request Received', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ajax' => $request->ajax(),
                'input' => $request->all(),
                'menu_id' => $menu->id ?? 'N/A',
            ]);

            // If the request comes from fetch() (AJAX), return JSON
            if ($request->ajax()) {
                if (empty($menu)) {
                    Log::warning('AJAX edit request with missing menu data', [
                        'request' => $request->all(),
                    ]);
                    return response()->json(['error' => 'Menu not found.'], 404);
                }

                Log::info('Returning JSON response for menu edit.', [
                    'menu_name' => $menu->name,
                    'category_id' => $menu->category_id,
                ]);

                return response()->json([
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'description' => $menu->description,
                    'price' => $menu->price,
                    'category_id' => $menu->category_id,
                    'is_vegetarian' => $menu->is_vegetarian,
                    'rating' => $menu->rating,
                    'review_count' => $menu->review_count,
                    'image_path' => $menu->image_path,
                ]);
            }

            // Otherwise, return normal edit view
            $categories = Category::where('is_active', true)->get();

            Log::info('Returning Blade view for menu edit.', [
                'menu_name' => $menu->name,
                'categories_count' => $categories->count(),
            ]);

            return view('menus.edit', compact('menu', 'categories'));

        } catch (\Exception $e) {
            Log::error('Error in Menu Edit', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'menu_id' => $menu->id ?? 'N/A',
            ]);

            return $request->ajax()
                ? response()->json(['error' => 'An unexpected error occurred.'], 500)
                : back()->with('error', 'An unexpected error occurred.');
        }
    }


    // Delete menu item
    public function destroy(MenuItem $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu item deleted successfully!');
    }

    // Show a single menu item
    public function show(MenuItem $menu)
    {
        return view('menus.show', compact('menu'));
    }



}
