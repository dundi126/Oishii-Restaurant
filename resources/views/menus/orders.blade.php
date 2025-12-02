<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders - Oishii Japanese Restaurant</title>
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

<main class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Admin Order Management</h1>
        <p class="text-gray-600 mt-2">Manage and update order status for all customers</p>
    </div>

    @if($orders && count($orders) > 0)
        <!-- Orders List -->
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Order Header -->
                    <div class="border-b border-gray-200 p-4 sm:p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        Order #{{ $order->order_number }}
                                    </h3>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium status-{{ $order->status }}">
                                        <i class="fas
                                            @if($order->status === 'pending') fa-clock
                                            @elseif($order->status === 'confirmed') fa-check
                                            @elseif($order->status === 'preparing') fa-utensils
                                            @elseif($order->status === 'ready') fa-box
                                            @elseif($order->status === 'out_for_delivery') fa-motorcycle
                                            @elseif($order->status === 'delivered') fa-check-circle
                                            @elseif($order->status === 'cancelled') fa-times
                                            @endif mr-1">
                                        </i>
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-user mr-2 w-4"></i>
                                        <span>{{ $order->customer_name }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-phone mr-2 w-4"></i>
                                        <span>{{ $order->customer_phone }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2 w-4"></i>
                                        <span>{{ $order->created_at->format('M j, g:i A') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Update Dropdown - ADMIN ONLY -->
                            <div class="mt-3 lg:mt-0 lg:ml-4">
                                <select onchange="updateOrderStatus('{{ $order->id }}', this.value)"
                                        class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-japanese-red focus:border-transparent text-sm">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Preparing</option>
                                    <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready</option>
                                    <option value="out_for_delivery" {{ $order->status === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Order Progress Tracking -->
                    <div class="border-b border-gray-200 p-4 sm:p-6">
                        <div class="flex relative">
                            <!-- Progress Steps -->
                            <div class="progress-step @if(in_array($order->status, ['confirmed', 'preparing', 'ready', 'out_for_delivery', 'delivered'])) completed @endif @if($order->status === 'pending') active @endif">
                                <div class="step-icon">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <span class="text-xs font-medium">Ordered</span>
                                <div class="progress-connector"></div>
                            </div>

                            <div class="progress-step @if(in_array($order->status, ['preparing', 'ready', 'out_for_delivery', 'delivered'])) completed @endif @if($order->status === 'confirmed') active @endif">
                                <div class="step-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span class="text-xs font-medium">Confirmed</span>
                                <div class="progress-connector"></div>
                            </div>

                            <div class="progress-step @if(in_array($order->status, ['ready', 'out_for_delivery', 'delivered'])) completed @endif @if($order->status === 'preparing') active @endif">
                                <div class="step-icon">
                                    <i class="fas fa-utensils"></i>
                                </div>
                                <span class="text-xs font-medium">Preparing</span>
                                <div class="progress-connector"></div>
                            </div>

                            <div class="progress-step @if(in_array($order->status, ['out_for_delivery', 'delivered'])) completed @endif @if($order->status === 'ready') active @endif">
                                <div class="step-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <span class="text-xs font-medium">Ready</span>
                                <div class="progress-connector"></div>
                            </div>

                            <div class="progress-step @if(in_array($order->status, ['delivered'])) completed @endif @if($order->status === 'out_for_delivery') active @endif">
                                <div class="step-icon">
                                    <i class="fas fa-motorcycle"></i>
                                </div>
                                <span class="text-xs font-medium">On the Way</span>
                                <div class="progress-connector"></div>
                            </div>

                            <div class="progress-step @if($order->status === 'delivered') completed active @endif">
                                <div class="step-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <span class="text-xs font-medium">Delivered</span>
                                <div class="progress-connector"></div>
                            </div>
                        </div>
                    </div>

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
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">Customer Info</h4>
                                        <p class="text-sm text-gray-600">{{ $order->customer_name }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->customer_email }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->customer_phone }}</p>
                                    </div>
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
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Delivery Address</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $order->delivery_address }}, {{ $order->delivery_city }}, {{ $order->delivery_postal_code }}
                                        </p>
                                        @if($order->delivery_instructions)
                                            <p class="text-sm text-gray-500 mt-1">
                                                <span class="font-medium">Instructions:</span> {{ $order->delivery_instructions }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Action Buttons -->
                        <div class="mt-6 flex flex-wrap gap-3">
                            <button onclick="contactCustomer('{{ $order->customer_phone }}', '{{ $order->customer_name }}')"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 flex items-center">
                                <i class="fas fa-phone mr-2"></i>
                                Call Customer
                            </button>
                            <button onclick="printOrder('{{ $order->id }}')"
                                    class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-300 flex items-center">
                                <i class="fas fa-print mr-2"></i>
                                Print Order
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
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-700 mb-2">No orders found</h2>
            <p class="text-gray-500 text-sm sm:text-base mb-6 max-w-md mx-auto">
                There are no orders in the system.
            </p>
        </div>
    @endif
</main>

<script>
    // CSRF Token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Update order status
    async function updateOrderStatus(orderId, newStatus) {
        const selectElement = event.target;
        const originalValue = selectElement.value;

        // Show loading state
        selectElement.disabled = true;
        const originalHTML = selectElement.innerHTML;
        selectElement.innerHTML = '<option>Updating...</option>';

        try {
            const response = await fetch(`/admin/orders/${orderId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    status: newStatus
                })
            });

            const result = await response.json();

            if (result.success) {
                // Update the status badge
                const statusBadge = selectElement.closest('.bg-white').querySelector('.status-badge');
                if (statusBadge) {
                    statusBadge.className = `inline-flex items-center px-2 py-1 rounded-full text-xs font-medium status-${newStatus}`;
                    statusBadge.innerHTML = getStatusBadgeHTML(newStatus);
                }

                // Update progress bar
                updateProgressBar(selectElement.closest('.bg-white'), newStatus);

                showSuccessMessage('Order status updated successfully');
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            alert('Error: ' + error.message);
            selectElement.value = originalValue;
        } finally {
            // Restore select element
            selectElement.disabled = false;
            selectElement.innerHTML = originalHTML;
            selectElement.value = newStatus;
        }
    }

    function updateProgressBar(orderCard, newStatus) {
        const steps = orderCard.querySelectorAll('.progress-step');
        const statusOrder = ['pending', 'confirmed', 'preparing', 'ready', 'out_for_delivery', 'delivered'];
        const currentIndex = statusOrder.indexOf(newStatus);

        steps.forEach((step, index) => {
            step.classList.remove('completed', 'active');
            if (index <= currentIndex) {
                step.classList.add('completed');
            }
            if (index === currentIndex) {
                step.classList.add('active');
            }
        });
    }

    function getStatusBadgeHTML(status) {
        const icons = {
            'pending': 'fa-clock',
            'confirmed': 'fa-check',
            'preparing': 'fa-utensils',
            'ready': 'fa-box',
            'out_for_delivery': 'fa-motorcycle',
            'delivered': 'fa-check-circle',
            'cancelled': 'fa-times'
        };

        const icon = icons[status] || 'fa-clock';
        const text = status.replace('_', ' ');

        return `<i class="fas ${icon} mr-1"></i>${text.charAt(0).toUpperCase() + text.slice(1)}`;
    }

    function showSuccessMessage(message) {
        const successDiv = document.createElement('div');
        successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        successDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(successDiv);

        setTimeout(() => {
            successDiv.remove();
        }, 3000);
    }

    function contactCustomer(phone, name) {
        if (confirm(`Call customer ${name} at ${phone}?`)) {
            window.location.href = `tel:${phone}`;
        }
    }

    function printOrder(orderId) {
        // Implement print functionality
        console.log('Print order:', orderId);
    }

    // Auto-refresh every 2 minutes
    setInterval(() => {
        location.reload();
    }, 120000);
</script>
</body>
</html>
