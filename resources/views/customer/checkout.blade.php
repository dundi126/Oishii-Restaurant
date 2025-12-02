<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Oishii Japanese Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Stripe.js library -->
    <script src="https://js.stripe.com/v3/"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .bg-japanese-red { background-color: #dc2626; }
        .text-japanese-red { color: #dc2626; }
        .border-japanese-red { border-color: #dc2626; }
        .hover\:bg-japanese-red:hover { background-color: #b91c1c; }

        /* Customization badge styles */
        .customization-badge {
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            color: #374151;
        }

        /* Loading spinner */
        .spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #dc2626;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Stripe Elements styling */
        .StripeElement {
            box-sizing: border-box;
            height: 40px;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background-color: white;
            transition: box-shadow 150ms ease;
        }

        .StripeElement--focus {
            box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.2);
            border-color: #dc2626;
        }

        .StripeElement--invalid {
            border-color: #ef4444;
        }

        .StripeElement--webkit-autofill {
            background-color: #fefde8 !important;
        }

        .card-errors {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
<!-- Include your navigation partial -->
@include('partials.nav')

<main class="max-w-6xl mx-auto px-3 sm:px-4 lg:px-6 py-6">

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(count($cartItems) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Order Details & Customer Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">Customer Information</h2>

                    <form id="customerForm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                <input type="text" name="first_name" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent"
                                       value="{{ auth()->user()->first_name ?? '' }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                <input type="text" name="last_name" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent"
                                       value="{{ auth()->user()->last_name ?? '' }}">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent"
                                       value="{{ auth()->user()->email ?? '' }}">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" name="phone" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent"
                                       placeholder="+1 (555) 123-4567">
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Delivery Information -->
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">Delivery Information</h2>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Address</label>
                                <input type="text" name="address" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent"
                                       placeholder="123 Main Street">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                <input type="text" name="city" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent"
                                       placeholder="Tokyo">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                                <input type="text" name="postal_code" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent"
                                       placeholder="100-0001">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Instructions (Optional)</label>
                            <textarea name="delivery_instructions" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent"
                                      placeholder="Any special delivery instructions..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">Payment Method</h2>

                    <div class="space-y-3">
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="credit_card" checked class="text-japanese-red focus:ring-japanese-red">
                            <div class="ml-3">
                                <span class="font-medium text-gray-900">Credit/Debit Card</span>
                                <p class="text-sm text-gray-500">Pay with Visa, Mastercard, or American Express</p>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="cash" class="text-japanese-red focus:ring-japanese-red">
                            <div class="ml-3">
                                <span class="font-medium text-gray-900">Cash on Delivery</span>
                                <p class="text-sm text-gray-500">Pay with cash when your order arrives</p>
                            </div>
                        </label>
                    </div>

                    <!-- Stripe Card Form -->
                    <div id="stripeCardForm" class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Card Holder Name</label>
                            <input type="text" id="cardholder-name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent"
                                   placeholder="John Doe">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Card Details</label>
                            <div id="card-element" class="p-3 border border-gray-300 rounded-lg">
                                <!-- Stripe Elements will create form elements here -->
                            </div>
                            <div id="card-errors" class="card-errors" role="alert"></div>
                        </div>

                        <!-- Payment processing info -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-700">
                            <i class="fas fa-lock mr-2"></i>
                            Your payment information is secure and encrypted
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="space-y-6">
                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>

                    <!-- Order Items -->
                    <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                        @foreach($cartItems as $cartItem)
                            <div class="flex justify-between items-start py-2 border-b border-gray-100">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 text-sm">{{ $cartItem->menuItem->name }} × {{ $cartItem->quantity }}</p>
                                    @if($cartItem->customizations && count($cartItem->customizations) > 0)
                                        <p class="text-xs text-gray-500 mt-1">
                                            @foreach($cartItem->customizations as $key => $value)
                                                {{ ucfirst($key) }}: {{ is_array($value) ? implode(', ', $value) : $value }}{{ !$loop->last ? ' • ' : '' }}
                                            @endforeach
                                        </p>
                                    @endif
                                </div>
                                <span class="font-medium text-gray-900 text-sm">${{ number_format($cartItem->total_price, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Price Breakdown -->
                    <div class="space-y-2 border-t border-gray-200 pt-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-900">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax (8%)</span>
                            <span class="text-gray-900">${{ number_format($total * 0.08, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Delivery Fee</span>
                            <span class="text-gray-900">${{ number_format($deliveryFee, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-semibold border-t border-gray-200 pt-2">
                            <span class="text-gray-900">Total</span>
                            <span class="text-japanese-red">${{ number_format($grandTotal, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Place Order Button -->
                <button id="placeOrderBtn"
                        class="w-full bg-japanese-red text-white py-3 px-4 rounded-lg hover:bg-red-700 transition duration-300 flex items-center justify-center text-base font-semibold">
                    <i class="fas fa-lock mr-2"></i>
                    Place Order
                </button>

                <!-- Back to Cart -->
                <a href="{{ route('cart.index') }}"
                   class="w-full bg-gray-500 text-white py-3 px-4 rounded-lg hover:bg-gray-600 transition duration-300 flex items-center justify-center text-base font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Cart
                </a>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 lg:p-12 text-center">
            <i class="fas fa-shopping-cart text-4xl sm:text-5xl lg:text-6xl text-gray-400 mb-4"></i>
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-700 mb-2">Your cart is empty</h2>
            <p class="text-gray-500 text-sm sm:text-base mb-6">Looks like you haven't added any delicious items to your cart yet.</p>
            <a href="{{ route('menu.index') }}"
               class="bg-japanese-red text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg hover:bg-red-700 transition duration-300 inline-flex items-center text-sm sm:text-base">
                <i class="fas fa-utensils mr-2"></i>
                Browse Our Menu
            </a>
        </div>
    @endif
</main>

<!-- Order Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6 text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check text-green-600 text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Order Placed Successfully!</h3>
        <p class="text-gray-600 mb-6">Thank you for your order. Your food is being prepared.</p>
        <div class="space-y-3">
            <a href="{{ route('orders.track') }}"
               class="w-full bg-japanese-red text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-300 block">
                Track Your Order
            </a>
            <button onclick="closeSuccessModal()"
                    class="w-full bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition duration-300">
                Continue Shopping
            </button>
        </div>
    </div>
</div>

<!-- Payment Processing Modal -->
<div id="paymentProcessingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6 text-center">
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <div class="spinner"></div>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Processing Payment</h3>
        <p class="text-gray-600 mb-6">Please wait while we process your payment...</p>
    </div>
</div>

<script>
    // CSRF Token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Stripe initialization
    const stripe = Stripe('{{ env("STRIPE_KEY") }}'); // Your publishable key
    const elements = stripe.elements();

    // Stripe card element
    let cardElement;

    // Initialize Stripe Elements
    function initializeStripe() {
        const style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#ef4444',
                iconColor: '#ef4444'
            }
        };

        cardElement = elements.create('card', { style: style });
        cardElement.mount('#card-element');

        // Handle real-time validation errors from the card Element
        cardElement.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });
    }

    // Toggle payment form based on payment method
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const stripeCardForm = document.getElementById('stripeCardForm');
            if (this.value === 'credit_card') {
                stripeCardForm.style.display = 'block';
            } else {
                stripeCardForm.style.display = 'none';
            }
        });
    });

    // Initialize - hide Stripe form if cash is selected
    document.addEventListener('DOMContentLoaded', function() {
        initializeStripe();

        const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
        if (selectedPayment && selectedPayment.value === 'cash') {
            document.getElementById('stripeCardForm').style.display = 'none';
        }
    });

    // Place order function with Stripe payment
    document.getElementById('placeOrderBtn').addEventListener('click', async function() {
        const button = this;
        const originalText = button.innerHTML;

        // Show loading state
        button.innerHTML = '<div class="spinner mr-2"></div>Processing...';
        button.disabled = true;

        try {
            // Collect form data
            const formData = {
                customer: {
                    first_name: document.querySelector('input[name="first_name"]').value,
                    last_name: document.querySelector('input[name="last_name"]').value,
                    email: document.querySelector('input[name="email"]').value,
                    phone: document.querySelector('input[name="phone"]').value,
                },
                delivery: {
                    address: document.querySelector('input[name="address"]').value,
                    city: document.querySelector('input[name="city"]').value,
                    postal_code: document.querySelector('input[name="postal_code"]').value,
                    instructions: document.querySelector('textarea[name="delivery_instructions"]').value,
                },
                payment: {
                    method: document.querySelector('input[name="payment_method"]:checked').value,
                }
            };

            // Basic validation
            if (!formData.customer.first_name || !formData.customer.last_name || !formData.customer.email || !formData.customer.phone) {
                throw new Error('Please fill in all customer information');
            }

            if (!formData.delivery.address || !formData.delivery.city || !formData.delivery.postal_code) {
                throw new Error('Please fill in all delivery information');
            }

            // Process payment if credit card is selected
            if (formData.payment.method === 'credit_card') {
                // Validate card holder name
                const cardholderName = document.getElementById('cardholder-name').value;
                if (!cardholderName) {
                    throw new Error('Please enter the cardholder name');
                }

                // Show payment processing modal
                document.getElementById('paymentProcessingModal').classList.remove('hidden');

                try {
                    // Create payment method with Stripe
                    const { paymentMethod, error } = await stripe.createPaymentMethod({
                        type: 'card',
                        card: cardElement,
                        billing_details: {
                            name: cardholderName,
                            email: formData.customer.email,
                            phone: formData.customer.phone,
                        },
                    });

                    if (error) {
                        throw new Error(error.message);
                    }

                    // Add payment method ID to form data
                    formData.payment.payment_method_id = paymentMethod.id;

                    // Hide payment processing modal
                    document.getElementById('paymentProcessingModal').classList.add('hidden');
                } catch (paymentError) {
                    document.getElementById('paymentProcessingModal').classList.add('hidden');
                    throw new Error('Payment processing failed: ' + paymentError.message);
                }
            }

            // Send order data to server
            const response = await fetch('{{ route("orders.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (result.success) {
                // Show success modal
                document.getElementById('successModal').classList.remove('hidden');
            } else {
                throw new Error(result.message || 'Failed to place order');
            }
        } catch (error) {
            alert('Error: ' + error.message);
            // Reset button
            button.innerHTML = originalText;
            button.disabled = false;
        }
    });

    function closeSuccessModal() {
        document.getElementById('successModal').classList.add('hidden');
        window.location.href = '{{ route("menu.index") }}';
    }
</script>
</body>
</html>
