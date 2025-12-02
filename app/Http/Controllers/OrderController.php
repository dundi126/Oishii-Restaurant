<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Authentication required'], 401);
        }

        DB::beginTransaction();
        try {
            $cartItems = Cart::getCartForUser($user->id);

            if (count($cartItems) === 0) {
                throw new \Exception('Your cart is empty');
            }

            $subtotal = Cart::getCartTotal($user->id);
            $deliveryFee = 3.00;
            $taxRate = 0.08;
            $taxAmount = $subtotal * $taxRate;
            $grandTotal = $subtotal + $taxAmount + $deliveryFee;

            // Process payment if credit card is selected
            $paymentStatus = 'pending';
            $stripePaymentIntentId = null;

            if ($request->payment['method'] === 'credit_card') {
                $paymentResult = $this->processStripePayment($grandTotal, $request->payment);

                if (!$paymentResult['success']) {
                    throw new \Exception('Payment failed: ' . $paymentResult['message']);
                }

                $paymentStatus = 'paid';
                $stripePaymentIntentId = $paymentResult['payment_intent_id'];
            }

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'OIS-' . date('Ymd') . '-' . strtoupper(uniqid()),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'delivery_fee' => $deliveryFee,
                'total_amount' => $grandTotal,
                'customer_name' => $request->customer['first_name'] . ' ' . $request->customer['last_name'],
                'customer_email' => $request->customer['email'],
                'customer_phone' => $request->customer['phone'],
                'delivery_address' => $request->delivery['address'],
                'delivery_city' => $request->delivery['city'],
                'delivery_postal_code' => $request->delivery['postal_code'],
                'delivery_instructions' => $request->delivery['instructions'] ?? null,
                'payment_method' => $request->payment['method'],
                'payment_status' => $paymentStatus,
                'stripe_payment_intent_id' => $stripePaymentIntentId,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $cartItem->menu_item_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->menuItem->price,
                    'customizations' => $cartItem->customizations,
                    'customization_price' => $cartItem->customization_price,
                    'total_price' => $cartItem->total_price,
                ]);
            }

            // Clear cart only after successful payment and order creation
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'order_number' => $order->order_number,
                'message' => 'Order placed successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function cancel(Order $order)
    {
        $user = auth()->user();

        // Verify the order belongs to the user
        if ($order->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Only allow cancellation for pending or confirmed orders
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be cancelled at this stage'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Update order status
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now()
            ]);

            // If payment was made by card, process refund
            if ($order->payment_method === 'credit_card' && $order->payment_status === 'paid') {
                // Implement Stripe refund logic here
                $order->update(['payment_status' => 'refunded']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order: ' . $e->getMessage()
            ], 500);
        }
    }

    private function processStripePayment($amount, $paymentData)
    {
        try {
            // Use correct key from .env
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $amountInCents = (int)($amount * 100);

            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'payment_method' => $paymentData['payment_method_id'],
                'confirm' => true,
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never', // <- prevents redirect-based methods
                ],
                'metadata' => [
                    'order_type' => 'food_delivery',
                ],
            ]);

            if ($paymentIntent->status === 'succeeded') {
                return [
                    'success' => true,
                    'payment_intent_id' => $paymentIntent->id,
                ];
            }

            return [
                'success' => false,
                'message' => 'Payment not completed: ' . $paymentIntent->status,
            ];
        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }


    public function track()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login'); // ensure non-authenticated users are redirected
        }

        // Fetch orders
        $orders = Order::when($user->role !== 'admin', function ($query) use ($user) {
            // Only filter by user_id for non-admins
            $query->where('user_id', $user->id);
        })
            ->with('orderItems.menuItem')
            ->orderBy('created_at', 'desc')
            ->get();

        // Choose view based on role
        $view = $user->role === 'admin' ? 'menus.orders' : 'customer.orders';

        return view($view, compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,out_for_delivery,delivered,cancelled'
        ]);

        try {
            $order->update([
                'status' => $request->status,
                'status_updated_at' => now()
            ]);

            // You can add notification logic here
            // $this->sendStatusNotification($order);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status: ' . $e->getMessage()
            ], 500);
        }
    }


}
