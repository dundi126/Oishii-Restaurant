<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders & Tracking - Oishii Japanese Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .bg-japanese-red { background-color: #dc2626; }
        .text-japanese-red { color: #dc2626; }
        .border-japanese-red { border-color: #dc2626; }
        .hover\:bg-japanese-red:hover { background-color: #b91c1c; }

        /* Status colors */
        .status-pending { background-color: #fef3c7; color: #d97706; }
        .status-confirmed { background-color: #dbeafe; color: #2563eb; }
        .status-preparing { background-color: #fce7f3; color: #db2777; }
        .status-ready { background-color: #dcfce7; color: #16a34a; }
        .status-out_for_delivery { background-color: #fef3c7; color: #d97706; }
        .status-delivered { background-color: #dcfce7; color: #16a34a; }
        .status-cancelled { background-color: #fecaca; color: #dc2626; }

        /* Progress bar */
        .progress-step {
            position: relative;
            flex: 1;
            text-align: center;
            padding: 0.5rem;
        }

        .progress-step.active .step-icon {
            background-color: #dc2626;
            color: white;
            border-color: #dc2626;
        }

        .progress-step.completed .step-icon {
            background-color: #16a34a;
            color: white;
            border-color: #16a34a;
        }

        .step-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            border: 2px solid #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            background-color: white;
            transition: all 0.3s ease;
        }

        .progress-connector {
            position: absolute;
            top: 1.25rem;
            left: -50%;
            right: 50%;
            height: 2px;
            background-color: #d1d5db;
            z-index: -1;
        }

        .progress-step:first-child .progress-connector {
            display: none;
        }

        .progress-step.completed .progress-connector {
            background-color: #16a34a;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
<!-- Navigation -->
@include('partials.nav')

<main class="max-w-6xl mx-auto px-3 sm:px-4 lg:px-6 py-6">


    @if($orders && count($orders) > 0)
        <!-- Orders List -->
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Order Header -->
                    <div class="border-b border-gray-200 p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Order #{{ $order->order_number }}
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Placed on {{ $order->created_at->format('M j, Y \a\t g:i A') }}
                                </p>
                            </div>
                            <div class="mt-2 sm:mt-0">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium status-{{ $order->status }}">
                                    <i class="fas
                                        @if($order->status === 'pending') fa-clock
                                        @elseif($order->status === 'confirmed') fa-check
                                        @elseif($order->status === 'preparing') fa-utensils
                                        @elseif($order->status === 'ready') fa-box
                                        @elseif($order->status === 'out_for_delivery') fa-motorcycle
                                        @elseif($order->status === 'delivered') fa-check-circle
                                        @elseif($order->status === 'cancelled') fa-times
                                        @endif mr-2">
                                    </i>
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Progress Tracking -->
                    <div class="border-b border-gray-200 p-4 sm:p-6">


                    <!-- Order Items -->
                    <div class="p-4 sm:p-6">
                        <h4 class="font-medium text-gray-900 mb-4">Order Items</h4>
                        <div class="space-y-3">
                            @foreach($order->orderItems as $item)
                                <div class="flex justify-between items-start py-2">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">
                                            {{ $item->menuItem->name }} × {{ $item->quantity }}
                                        </p>
                                        @if($item->customizations && count($item->customizations) > 0)
                                            <p class="text-sm text-gray-500 mt-1">
                                                @foreach($item->customizations as $key => $value)
                                                    {{ ucfirst($key) }}: {{ is_array($value) ? implode(', ', $value) : $value }}{{ !$loop->last ? ' • ' : '' }}
                                                @endforeach
                                            </p>
                                        @endif
                                    </div>
                                    <span class="font-medium text-gray-900">${{ number_format($item->total_price, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-gray-50 p-4 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Price Summary -->
                            <div>
                                <h4 class="font-medium text-gray-900 mb-3">Order Summary</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Subtotal</span>
                                        <span class="text-gray-900">${{ number_format($order->subtotal, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tax</span>
                                        <span class="text-gray-900">${{ number_format($order->tax_amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Delivery Fee</span>
                                        <span class="text-gray-900">${{ number_format($order->delivery_fee, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between font-semibold border-t border-gray-200 pt-2">
                                        <span class="text-gray-900">Total</span>
                                        <span class="text-japanese-red">${{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Delivery & Payment Info -->
                            <div>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Payment Method</p>
                                        <p class="text-sm text-gray-600 capitalize">
                                            {{ str_replace('_', ' ', $order->payment_method) }}
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                                @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $order->payment_status }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 flex flex-col sm:flex-row gap-3">
                            @if($order->status === 'pending' || $order->status === 'confirmed')
                                <button onclick="cancelOrder('{{ $order->id }}')"
                                        class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition duration-300 flex items-center justify-center">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancel Order
                                </button>
                            @endif

                            @if($order->status === 'delivered')
                                <button class="bg-japanese-red text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 flex items-center justify-center">
                                    <i class="fas fa-redo mr-2"></i>
                                    Reorder
                                </button>
                            @endif

                            <button onclick="contactSupport('{{ $order->order_number }}')"
                                    class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition duration-300 flex items-center justify-center">
                                <i class="fas fa-headset mr-2"></i>
                                Contact Support
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


    @else
        <!-- Empty Orders State -->
        <div class="bg-white rounded-lg shadow-md p-8 sm:p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-clipboard-list text-gray-400 text-2xl"></i>
            </div>
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-700 mb-2">No orders yet</h2>
            <p class="text-gray-500 text-sm sm:text-base mb-6 max-w-md mx-auto">
                You haven't placed any orders yet. Start exploring our delicious menu and place your first order!
            </p>
            <div class="space-y-3 sm:space-y-0 sm:space-x-3 sm:flex sm:justify-center">
                <a href="{{ route('menu.index') }}"
                   class="bg-japanese-red text-white px-6 py-3 rounded-lg hover:bg-red-700 transition duration-300 inline-flex items-center justify-center">
                    <i class="fas fa-utensils mr-2"></i>
                    Browse Menu
                </a>
                <a href="{{ route('cart.index') }}"
                   class="bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition duration-300 inline-flex items-center justify-center">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    View Cart
                </a>
            </div>
        </div>
    @endif
</main>

<!-- Cancel Order Confirmation Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6">
        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2 text-center">Cancel Order?</h3>
        <p class="text-gray-600 mb-6 text-center">Are you sure you want to cancel this order? This action cannot be undone.</p>
        <div class="flex space-x-3">
            <button onclick="closeCancelModal()"
                    class="flex-1 bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition duration-300">
                Keep Order
            </button>
            <button id="confirmCancelBtn"
                    class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-300">
                Yes, Cancel Order
            </button>
        </div>
    </div>
</div>

<!-- Support Modal -->
<div id="supportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Contact Support</h3>
        <p class="text-gray-600 mb-4">Need help with order <span id="supportOrderNumber" class="font-semibold"></span>?</p>

        <div class="space-y-4">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-phone-alt text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <p class="font-medium text-blue-900">Call Us</p>
                        <p class="text-blue-700">+1 (555) 123-4567</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-envelope text-green-500 mt-1 mr-3"></i>
                    <div>
                        <p class="font-medium text-green-900">Email Us</p>
                        <p class="text-green-700">support@oishii.com</p>
                    </div>
                </div>
            </div>

        </div>

        <button onclick="closeSupportModal()"
                class="w-full mt-6 bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition duration-300">
            Close
        </button>
    </div>
</div>

<script>
    // CSRF Token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let cancelOrderId = null;
    let refreshCountdown = 60;

    // Auto-refresh functionality
    function startAutoRefresh() {
        const timerElement = document.getElementById('refreshTimer');
        if (!timerElement) return;

        setInterval(() => {
            location.reload();
        }, 60000); // Refresh every 60 seconds

        // Update countdown timer
        setInterval(() => {
            refreshCountdown--;
            if (refreshCountdown <= 0) {
                refreshCountdown = 60;
            }
            timerElement.textContent = `Next refresh in: ${refreshCountdown} seconds`;
        }, 1000);
    }

    // Cancel order functionality
    function cancelOrder(orderId) {
        cancelOrderId = orderId;
        document.getElementById('cancelModal').classList.remove('hidden');

        // Set up confirm button
        document.getElementById('confirmCancelBtn').onclick = async function() {
            const button = this;
            const originalText = button.innerHTML;

            button.innerHTML = '<div class="spinner mr-2"></div>Cancelling...';
            button.disabled = true;

            try {
                const response = await fetch(`/orders/${cancelOrderId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();

                if (result.success) {
                    closeCancelModal();
                    location.reload();
                } else {
                    throw new Error(result.message || 'Failed to cancel order');
                }
            } catch (error) {
                alert('Error: ' + error.message);
                button.innerHTML = originalText;
                button.disabled = false;
            }
        };
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
        cancelOrderId = null;
    }

    // Support functionality
    function contactSupport(orderNumber) {
        document.getElementById('supportOrderNumber').textContent = orderNumber;
        document.getElementById('supportModal').classList.remove('hidden');
    }

    function closeSupportModal() {
        document.getElementById('supportModal').classList.add('hidden');
    }

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        startAutoRefresh();

        // Close modals when clicking outside
        document.getElementById('cancelModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeCancelModal();
        });

        document.getElementById('supportModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeSupportModal();
        });
    });

    // Spinner CSS (if not already in styles)
    const spinnerStyle = document.createElement('style');
    spinnerStyle.textContent = `
        .spinner {
            border: 2px solid #f3f4f6;
            border-top: 2px solid #dc2626;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
            display: inline-block;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(spinnerStyle);
</script>
</body>
</html>
