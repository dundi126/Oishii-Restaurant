<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $cartItems = Cart::getCartForUser($user->id);

        // DEBUG: Check each cart item
        foreach($cartItems as $item) {
            \Log::info("Cart Item Debug:", [
                'id' => $item->id,
                'menu_item_name' => $item->menuItem->name,
                'base_price' => $item->menuItem->price,
                'customization_price' => $item->customization_price,
                'quantity' => $item->quantity,
                'total_price_accessor' => $item->total_price,
                'manual_calc' => ($item->menuItem->price + $item->customization_price) * $item->quantity,
                'are_they_equal' => $item->total_price === (($item->menuItem->price + $item->customization_price) * $item->quantity)
            ]);
        }

        $total = Cart::getCartTotal($user->id);

        return view('customer.cart', compact('cartItems', 'total'));
    }
    /**
     * Add item to cart with customizations
     */
    public function add(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Authentication required'], 401);
            }
            return redirect()->route('login');
        }

        $request->validate([
            'menu_id' => 'required|exists:menu_items,id',
            'quantity' => 'required|integer|min:1',
            'customizations' => 'sometimes|array',
            'customization_price' => 'sometimes|numeric|min:0'
        ]);

        try {
            $menuId = $request->input('menu_id');
            $quantity = $request->input('quantity');
            $customizations = $request->input('customizations', []);
            $customizationPrice = $request->input('customization_price', 0);

            // Add debug logging
            \Log::info('Cart Add Request:', [
                'user_id' => $user->id,
                'menu_id' => $menuId,
                'quantity' => $quantity,
                'customizations' => $customizations,
                'customization_price' => $customizationPrice,
                'all_request_data' => $request->all()
            ]);

            // Verify menu item exists and is active
            $menuItem = MenuItem::where('id', $menuId)
                ->where('is_active', true)
                ->firstOrFail();

            // Check if item already exists in cart with same customizations
            $existingCartItem = Cart::where('user_id', $user->id)
                ->where('menu_item_id', $menuId)
                ->where('customizations', json_encode($customizations))
                ->first();

            if ($existingCartItem) {
                // Update quantity if same item with same customizations exists
                \Log::info('Updating existing cart item:', [
                    'cart_item_id' => $existingCartItem->id,
                    'old_quantity' => $existingCartItem->quantity,
                    'new_quantity' => $existingCartItem->quantity + $quantity
                ]);

                $existingCartItem->quantity += $quantity;
                $existingCartItem->save();
            } else {
                // Add new item to cart with customizations
                \Log::info('Creating new cart item with customizations');

                $cartItem = Cart::create([
                    'user_id' => $user->id,
                    'menu_item_id' => $menuId,
                    'quantity' => $quantity,
                    'customizations' => !empty($customizations) ? $customizations : null,
                    'customization_price' => $customizationPrice
                ]);

                \Log::info('Cart item created:', [
                    'cart_item_id' => $cartItem->id,
                    'saved_customizations' => $cartItem->customizations,
                    'saved_customization_price' => $cartItem->customization_price
                ]);
            }

            $cartCount = Cart::getCartCount($user->id);

            // Log final cart state
            $finalCartItems = Cart::where('user_id', $user->id)->get();
            \Log::info('Final cart state:', [
                'cart_count' => $cartCount,
                'cart_items' => $finalCartItems->map(function($item) {
                    return [
                        'id' => $item->id,
                        'menu_item_id' => $item->menu_item_id,
                        'quantity' => $item->quantity,
                        'customizations' => $item->customizations,
                        'customization_price' => $item->customization_price,
                        'menu_item_price' => $item->menuItem->price,
                        'calculated_total' => $item->total_price
                    ];
                })->toArray()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'cart_count' => $cartCount,
                    'message' => $menuItem->name . ' added to cart successfully!'
                ]);
            }

            return redirect()->back()->with('success', $menuItem->name . ' added to cart successfully!');

        } catch (\Exception $e) {
            \Log::error('Cart add error:', [
                'user_id' => $user->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add item to cart: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to add item to cart.');
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $cartItemId)
    {
        $user = auth()->user();
        if (!$user) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Authentication required'], 401);
            }
            return redirect()->route('login');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $quantity = $request->input('quantity');

            // Find cart item by ID (not menu_item_id)
            $cartItem = Cart::where('user_id', $user->id)
                ->where('id', $cartItemId)
                ->firstOrFail();

            $cartItem->update(['quantity' => $quantity]);

            $cartCount = Cart::getCartCount($user->id);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'cart_count' => $cartCount,
                    'message' => 'Cart updated successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Cart updated successfully!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update cart!'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update cart!');
        }
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request, $cartItemId)
    {
        $user = auth()->user();
        if (!$user) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Authentication required'], 401);
            }
            return redirect()->route('login');
        }

        try {
            $cartItem = Cart::where('user_id', $user->id)
                ->where('id', $cartItemId)
                ->first();

            if ($cartItem) {
                $menuItemName = $cartItem->menuItem->name;
                $cartItem->delete();

                $cartCount = Cart::getCartCount($user->id);

                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'cart_count' => $cartCount,
                        'message' => $menuItemName . ' removed from cart!'
                    ]);
                }

                return redirect()->back()->with('success', $menuItemName . ' removed from cart!');
            }

            throw new \Exception('Item not found in cart');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found in cart!'
                ], 404);
            }

            return redirect()->back()->with('error', 'Item not found in cart!');
        }
    }

    /**
     * Clear entire cart
     */
    public function clear(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Authentication required'], 401);
            }
            return redirect()->route('login');
        }

        try {
            Cart::where('user_id', $user->id)->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'cart_count' => 0,
                    'message' => 'Cart cleared successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Cart cleared successfully!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to clear cart!'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to clear cart!');
        }
    }

    /**
     * Get cart count (for navbar)
     */
    public function getCartCount()
    {
        $user = auth()->user();
        $count = $user ? Cart::getCartCount($user->id) : 0;

        return response()->json(['count' => $count]);
    }
}
