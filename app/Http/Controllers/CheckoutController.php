<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to proceed with checkout.');
        }

        // Get cart items
        $cartItems = Cart::with('menuItem.category')
            ->where('user_id', $user->id)
            ->get();

        // Check if cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add some items before checkout.');
        }

        // Calculate totals - FIXED: Match variable names in your view
        $total = $cartItems->sum(function ($cartItem) {
            $basePrice = $cartItem->menuItem->price;
            $customizationPrice = $cartItem->customization_price ?? 0;
            return ($basePrice + $customizationPrice) * $cartItem->quantity;
        });

        $deliveryFee = 3.00; // Fixed delivery fee
        $taxRate = 0.08; // 8% tax
        $taxAmount = $total * $taxRate;
        $grandTotal = $total + $taxAmount + $deliveryFee;

        return view('customer.checkout', compact(
            'cartItems',
            'total', // Changed from 'subtotal' to match your view
            'deliveryFee',
            'taxAmount', // This is calculated but not directly used in your view
            'grandTotal'
        ));
    }

    /**
     * Validate checkout form (optional - can be handled in OrderController)
     */
    public function validateCheckout(Request $request)
    {
        $request->validate([
            'customer.first_name' => 'required|string|max:255',
            'customer.last_name' => 'required|string|max:255',
            'customer.email' => 'required|email',
            'customer.phone' => 'required|string|max:20',
            'delivery.address' => 'required|string|max:500',
            'delivery.city' => 'required|string|max:255',
            'delivery.postal_code' => 'required|string|max:20',
            'payment.method' => 'required|in:credit_card,cash',
            // Credit card validation (if payment method is credit_card)
            'payment.card_number' => 'required_if:payment.method,credit_card|string|max:19',
            'payment.card_holder' => 'required_if:payment.method,credit_card|string|max:255',
            'payment.expiry_date' => 'required_if:payment.method,credit_card|string|max:5',
            'payment.cvv' => 'required_if:payment.method,credit_card|string|max:4',
        ]);

        // Additional validation can be added here
        // For example, validating credit card format, expiry date, etc.

        return response()->json(['valid' => true]);
    }
}
