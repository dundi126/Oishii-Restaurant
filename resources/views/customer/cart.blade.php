<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Oishii Japanese Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .bg-japanese-red { background-color: #dc2626; }
        .text-japanese-red { color: #dc2626; }
        .border-japanese-red { border-color: #dc2626; }
        .hover\:bg-japanese-red:hover { background-color: #b91c1c; }

        /* Toast styles */
        .toast {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Customization badge styles */
        .customization-badge {
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            color: #374151;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
@include('partials.nav')

<main class="max-w-6xl mx-auto px-3 sm:px-4 lg:px-6 py-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        @if(count($cartItems) > 0)
            <form action="{{ route('cart.clear') }}" method="POST" class="inline" id="clearCartForm">
                @csrf
                <button type="button"
                        onclick="clearCart()"
                        class="text-red-600 hover:text-red-800 flex items-center transition duration-300 text-sm sm:text-base">
                    <i class="fas fa-trash mr-2"></i>Clear Cart
                </button>
            </form>
        @endif
    </div>

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
        <!-- Cart Items -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @foreach($cartItems as $cartItem)
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 sm:p-6 border-b border-gray-200 hover:bg-gray-50 transition duration-300 gap-4">
                    <!-- Item Info -->
                    <div class="flex items-start flex-1 w-full sm:w-auto">
                        @if($cartItem->menuItem->image_path)
                            <img src="{{ asset($cartItem->menuItem->image_path) }}"
                                 alt="{{ $cartItem->menuItem->name }}"
                                 class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg flex-shrink-0">
                        @else
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-utensils text-gray-400 text-xl sm:text-2xl"></i>
                            </div>
                        @endif

                        <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">{{ $cartItem->menuItem->name }}</h3>
                            <p class="text-gray-600 text-xs sm:text-sm mt-1 line-clamp-2">{{ $cartItem->menuItem->description }}</p>

                            <!-- Customizations Display -->
                            @if($cartItem->customizations && count($cartItem->customizations) > 0)
                                <div class="mt-2">
                                    <p class="text-xs font-medium text-gray-700 mb-1">Customizations:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($cartItem->customizations as $key => $value)
                                            @if(is_array($value))
                                                <span class="customization-badge px-2 py-1 rounded-full text-xs">
                                                    {{ ucfirst($key) }}: {{ implode(', ', $value) }}
                                                </span>
                                            @else
                                                <span class="customization-badge px-2 py-1 rounded-full text-xs">
                                                    {{ ucfirst($key) }}: {{ $value }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="flex flex-wrap items-center mt-2 gap-2">
                                <!-- Base Price -->
                                <span class="text-gray-700 font-semibold text-sm sm:text-base">
                                    ${{ number_format($cartItem->menuItem->price, 2) }}
                                </span>

                                <!-- Customization Price (if any) -->
                                @if($cartItem->customization_price > 0)
                                    <span class="text-green-600 font-medium text-sm sm:text-base">
                                        +${{ number_format($cartItem->customization_price, 2) }}
                                    </span>
                                @endif

                                <!-- Category and Diet Tags -->
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                    {{ $cartItem->menuItem->category->name }}
                                </span>
                                <span class="text-xs {{ $cartItem->menuItem->is_vegetarian ? 'text-green-600' : 'text-red-600' }} bg-gray-100 px-2 py-1 rounded">
                                    {{ $cartItem->menuItem->diet_type }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity Controls & Actions -->
                    <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4">
                        <!-- Quantity Controls -->
                        <div class="flex items-center space-x-3">
                            <form action="{{ route('cart.update', $cartItem->id) }}" method="POST"
                                  class="flex items-center" id="quantity-form-{{ $cartItem->id }}">
                                @csrf
                                @method('PUT')
                                <div class="flex items-center border border-gray-300 rounded-lg">
                                    <button type="button"
                                            onclick="updateQuantity({{ $cartItem->id }}, -1)"
                                            class="px-2 sm:px-3 py-1 text-gray-600 hover:text-japanese-red transition duration-300">
                                        <i class="fas fa-minus text-xs sm:text-sm"></i>
                                    </button>
                                    <input type="number"
                                           name="quantity"
                                           value="{{ $cartItem->quantity }}"
                                           min="1"
                                           max="99"
                                           class="w-10 sm:w-12 text-center border-0 focus:ring-0 focus:border-0 text-sm sm:text-base"
                                           onchange="submitQuantity({{ $cartItem->id }})"
                                           id="quantity-input-{{ $cartItem->id }}">
                                    <button type="button"
                                            onclick="updateQuantity({{ $cartItem->id }}, 1)"
                                            class="px-2 sm:px-3 py-1 text-gray-600 hover:text-japanese-red transition duration-300">
                                        <i class="fas fa-plus text-xs sm:text-sm"></i>
                                    </button>
                                </div>
                            </form>

                            <!-- Item Total (with customizations) -->
                            <span class="text-base sm:text-lg font-semibold text-gray-900 w-16 sm:w-20 text-right">
                                ${{ number_format($cartItem->total_price, 2) }}
                            </span>
                        </div>

                        <!-- Remove Item -->
                        <form action="{{ route('cart.remove', $cartItem->id) }}" method="POST" class="inline" id="remove-form-{{ $cartItem->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    onclick="removeItem({{ $cartItem->id }}, '{{ $cartItem->menuItem->name }}')"
                                    class="text-red-500 hover:text-red-700 p-1 sm:p-2 transition duration-300">
                                <i class="fas fa-trash text-sm sm:text-base"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

            <!-- Cart Summary -->
            <div class="p-4 sm:p-6 bg-gray-50">
                <div class="space-y-2 mb-4">
                    <!-- Subtotal -->
                    <div class="flex justify-between items-center">
                        <span class="text-lg sm:text-xl font-semibold text-gray-900">Subtotal:</span>
                        <span class="text-lg sm:text-xl font-bold text-japanese-red">${{ number_format($total, 2) }}</span>
                    </div>

                    <!-- Tax Calculation -->
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm sm:text-base">Tax (8%):</span>
                        <span class="text-gray-600 text-sm sm:text-base">${{ number_format($total * 0.08, 2) }}</span>
                    </div>

                    <!-- Total -->
                    <div class="flex justify-between items-center pt-2 border-t border-gray-300">
                        <span class="text-lg sm:text-xl font-semibold text-gray-900">Total:</span>
                        <span class="text-lg sm:text-xl font-bold text-japanese-red">${{ number_format($total * 1.08, 2) }}</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between gap-3">
                    <a href="{{ route('menu.index') }}"
                       class="bg-gray-500 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg hover:bg-gray-600 transition duration-300 flex items-center justify-center text-sm sm:text-base">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Continue Shopping
                    </a>
                    <!-- In your cart.blade.php file, replace the current checkout button -->
                    <a href="{{ route('checkout.index') }}"
                       class="bg-japanese-red text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg hover:bg-red-700 transition duration-300 flex items-center justify-center text-sm sm:text-base">
                        <i class="fas fa-credit-card mr-2"></i>
                        Proceed to Checkout
                    </a>
                </div>
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

<script>
    // CSRF Token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Update quantity with buttons
    function updateQuantity(cartItemId, change) {
        const input = document.getElementById(`quantity-input-${cartItemId}`);
        let newQuantity = parseInt(input.value) + change;

        if (newQuantity < 1) newQuantity = 1;
        if (newQuantity > 99) newQuantity = 99;

        input.value = newQuantity;
        submitQuantity(cartItemId);
    }

    // Submit quantity form via AJAX
    async function submitQuantity(cartItemId) {
        const form = document.getElementById(`quantity-form-${cartItemId}`);
        const quantity = document.getElementById(`quantity-input-${cartItemId}`).value;

        try {
            showToast('Updating quantity...', 'info');

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: JSON.stringify({
                    quantity: parseInt(quantity)
                })
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message, 'success');
                // Reload page after success
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {

                // Reload page even on error to ensure consistency
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        } catch (error) {

            // Reload page on network error
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        }
    }

    // Remove item from cart
    async function removeItem(cartItemId, itemName) {
        if (!confirm(`Remove "${itemName}" from cart?`)) {
            return;
        }

        const form = document.getElementById(`remove-form-${cartItemId}`);

        try {
            showToast('Removing item...', 'info');

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-HTTP-Method-Override': 'DELETE'
                }
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message, 'success');
                // Reload page after success
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {

                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        } catch (error) {

            setTimeout(() => {
                window.location.reload();
            }, 2000);
        }
    }

    // Clear entire cart
    async function clearCart() {
        if (!confirm('Are you sure you want to clear your entire cart?')) {
            return;
        }

        const form = document.getElementById('clearCartForm');

        try {
            showToast('Clearing cart...', 'info');

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message, 'success');
                // Reload page after success
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {

                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        } catch (error) {

            // Reload page on network error
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        }
    }

    // Toast notification
    function showToast(message, type = 'success') {
        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.toast');
        existingToasts.forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                    'bg-blue-500 text-white'
        }`;
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${
            type === 'success' ? 'fa-check-circle' :
                type === 'error' ? 'fa-exclamation-circle' :
                    'fa-info-circle'
        } mr-2"></i>
                <span class="text-sm">${message}</span>
            </div>
        `;

        document.body.appendChild(toast);

        // Remove toast after 3 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 3000);
    }
</script>
</body>
</html>
