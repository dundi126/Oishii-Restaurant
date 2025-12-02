<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Oishii Japanese Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .bg-japanese-red { background-color: #dc2626; }
        .text-japanese-red { color: #dc2626; }
        .border-japanese-red { border-color: #dc2626; }
        .hover\:bg-japanese-red:hover { background-color: #b91c1c; }
        .menu-item { transition: transform 0.3s ease; }
        .menu-item:hover { transform: translateY(-5px); }

        /* Success message styles */
        .alert-success {
            background-color: #d1fae5;
            border-color: #a7f3d0;
            color: #065f46;
        }

        .alert-error {
            background-color: #fee2e2;
            border-color: #fecaca;
            color: #991b1b;
        }

        /* Loading state */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Custom checkbox style */
        .custom-checkbox:checked {
            background-color: #dc2626;
            border-color: #dc2626;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
@include('partials.nav')

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert-success border-l-4 p-4 mb-4">
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
    <div class="alert-error border-l-4 p-4 mb-4">
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

<!-- Main Content -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 flex-grow w-full">

    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 sm:mb-8 gap-4">
        <div class="flex items-center w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <input type="text" placeholder="Search menu items..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent w-full" id="searchInput">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>

        <!-- Cart Button -->
        <a href="{{ route('cart.index') }}" class="relative bg-japanese-red text-white px-4 sm:px-6 py-3 rounded-lg hover:bg-red-700 transition duration-300 flex items-center justify-center">
            <i class="fas fa-shopping-cart mr-2"></i>View Cart
            <span id="cartCount" class="absolute -top-2 -right-2 bg-yellow-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">
                {{ $cartCount }}
            </span>
        </a>
    </div>

    <!-- Category Filters -->
    <div class="category-filters flex flex-nowrap overflow-x-auto pb-4 mb-6 sm:mb-8 gap-3">
        <button class="category-filter px-4 py-3 rounded-full bg-japanese-red text-white font-medium shadow-lg flex-shrink-0" data-category="all">
            All Items
        </button>
        @foreach($categories as $category)
            <button class="category-filter px-4 py-3 rounded-full bg-white text-gray-700 border border-gray-300 hover:border-japanese-red hover:text-japanese-red font-medium transition duration-300 flex-shrink-0" data-category="{{ $category->id }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    <!-- Menu Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8" id="menu-grid">
        @foreach($menus as $menu)
            <div class="menu-item bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100" data-category="{{ $menu->category_id }}">
                <div class="menu-image-container bg-gradient-to-br from-gray-100 to-gray-50 flex items-center justify-center" style="height: 12rem; position: relative; overflow: hidden;">
                    @if($menu->category->icon)
                        <i class="{{ $menu->category->icon }} text-4xl sm:text-6xl text-gray-400 opacity-20"></i>
                    @else
                        <i class="fas fa-utensils text-4xl sm:text-6xl text-gray-400 opacity-20"></i>
                    @endif

                    @if($menu->image_path)
                        <img src="{{ asset($menu->image_path) }}" alt="{{ $menu->name }}" class="absolute inset-0 w-full h-full object-cover">
                    @endif

                    <span class="absolute top-3 sm:top-4 right-3 sm:right-4 bg-japanese-red text-white px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">
                        {{ $menu->category->name }}
                    </span>
                    <span class="absolute top-3 sm:top-4 left-3 sm:left-4 {{ $menu->is_vegetarian ? 'bg-green-500' : 'bg-red-500' }} text-white px-2 py-1 rounded-full text-xs font-medium">
                        {{ $menu->is_vegetarian ? 'Veg' : 'Non-Veg' }}
                    </span>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900">{{ $menu->name }}</h3>
                        <span class="text-xl sm:text-2xl font-bold text-japanese-red">${{ number_format($menu->price, 2) }}</span>
                    </div>
                    <p class="text-gray-600 mb-4 text-sm sm:text-base">{{ $menu->description }}</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-yellow-400">
                            <i class="fas fa-star text-sm"></i>
                            <span class="text-gray-700 font-medium text-sm">{{ $menu->rating ?? '4.5' }}</span>
                            <span class="text-gray-500 text-xs">({{ $menu->review_count ?? '0' }} reviews)</span>
                        </div>
                        <!-- Updated Add to Cart Button -->
                        <button onclick="openCustomizationModal({{ $menu->id }}, '{{ $menu->name }}', {{ $menu->price }}, {{ $menu->customizations }})"
                                class="bg-japanese-red text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 flex items-center justify-center cart-btn"
                                id="cart-btn-{{ $menu->id }}">
                            <i class="fas fa-plus mr-2"></i>Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</main>

@include('partials.footer')

<!-- Customization Modal -->
<div id="customizationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Customize Your Order</h3>
                <button onclick="closeCustomizationModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Customization Form -->
            <form id="customizationForm" onsubmit="submitCustomizedOrder(event)">
                <input type="hidden" id="customizationMenuId" name="menu_id">

                <!-- Customization Options will be inserted here -->
                <div id="customizationOptions" class="space-y-4 mb-6">
                    <!-- Dynamic content -->
                </div>

                <!-- Price Summary -->
                <div class="border-t pt-4 mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Base Price:</span>
                        <span id="basePrice" class="font-semibold">$0.00</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Extra Charges:</span>
                        <span id="extraPrice" class="font-semibold text-green-600">+$0.00</span>
                    </div>
                    <div class="flex justify-between items-center text-lg font-bold border-t pt-2">
                        <span>Total:</span>
                        <span id="totalPrice" class="text-japanese-red">$0.00</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="button" onclick="closeCustomizationModal()"
                            class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-300">
                        Cancel
                    </button>
                    <button type="submit" id="submitCustomizationBtn"
                            class="flex-1 px-4 py-3 bg-japanese-red text-white rounded-lg hover:bg-red-700 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-cart-plus mr-2"></i>
                        Add to Cart
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // CSRF Token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Global variables
    let currentMenuId = null;
    let currentBasePrice = 0;

    // Number formatting helper - formats with 2 decimal places
    function numberFormat(number) {
        const num = typeof number === 'string' ? parseFloat(number) : number;
        return num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    // Open customization modal
    function openCustomizationModal(menuId, menuName, basePrice, customizations) {
        currentMenuId = menuId;
        currentBasePrice = basePrice;

        // Update modal title
        document.getElementById('modalTitle').textContent = `Customize ${menuName}`;
        document.getElementById('customizationMenuId').value = menuId;
        document.getElementById('basePrice').textContent = `$${numberFormat(basePrice)}`;

        // Build customization options
        const optionsContainer = document.getElementById('customizationOptions');
        optionsContainer.innerHTML = '';

        if (customizations && customizations.length > 0) {
            customizations.forEach((customization, index) => {
                const optionHtml = buildCustomizationOption(customization, index);
                optionsContainer.innerHTML += optionHtml;
            });
        } else {
            optionsContainer.innerHTML = '<p class="text-gray-500 text-center py-4">No customization options available.</p>';
        }

        // Reset prices
        updatePriceSummary(0);

        // Show modal
        document.getElementById('customizationModal').classList.remove('hidden');
    }

    // Close customization modal
    function closeCustomizationModal() {
        document.getElementById('customizationModal').classList.add('hidden');
        document.getElementById('customizationForm').reset();
    }

    // Build HTML for a customization option
    function buildCustomizationOption(customization, index) {
        const options = customization.options || [];
        let optionsHtml = '';
        const requiredAttr = customization.required ? 'required' : '';

        if (customization.type === 'select') {
            optionsHtml = `
                <select name="customizations[${customization.name}]"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent customization-select"
                        data-customization-name="${customization.name}"
                        onchange="updatePriceSummary()" ${requiredAttr}>
                    <option value="">Select ${customization.name}</option>
                    ${options.map(option => `
                        <option value="${option.value}" data-price="${option.price}">
                            ${option.value} ${option.price > 0 ? `(+$${numberFormat(option.price)})` : ''}
                        </option>
                    `).join('')}
                </select>
            `;
        } else if (customization.type === 'multiple') {
            optionsHtml = options.map(option => `
                <label class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg cursor-pointer">
                    <input type="checkbox"
                           name="customizations[${customization.name}][]"
                           value="${option.value}"
                           data-price="${option.price}"
                           class="custom-checkbox h-4 w-4 text-japanese-red border-gray-300 rounded focus:ring-japanese-red"
                           onchange="updatePriceSummary()">
                    <span class="flex-1">${option.value}</span>
                    <span class="text-green-600 font-medium">
                        ${option.price > 0 ? `+$${numberFormat(option.price)}` : 'Free'}
                    </span>
                </label>
            `).join('');
        }

        return `
            <div class="customization-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    ${customization.name}
                    ${customization.required ? '<span class="text-red-500">*</span>' : ''}
                </label>
                <div class="space-y-2">
                    ${optionsHtml}
                </div>
            </div>
        `;
    }

    // Update price summary based on selected customizations
    function updatePriceSummary() {
        let extraPrice = 0;

        // Calculate from select elements
        document.querySelectorAll('.customization-select').forEach(select => {
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption && selectedOption.value) {
                extraPrice += parseFloat(selectedOption.dataset.price) || 0;
            }
        });

        // Calculate from checkboxes
        document.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
            extraPrice += parseFloat(checkbox.dataset.price) || 0;
        });

        const totalPrice = currentBasePrice + extraPrice;

        // Update display
        document.getElementById('extraPrice').textContent = `+$${numberFormat(extraPrice)}`;
        document.getElementById('totalPrice').textContent = `$${numberFormat(totalPrice)}`;

        return extraPrice;
    }

    // Submit customized order
    async function submitCustomizedOrder(event) {
        event.preventDefault();

        const submitBtn = document.getElementById('submitCustomizationBtn');
        const originalText = submitBtn.innerHTML;

        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';
        submitBtn.disabled = true;

        try {
            // Collect form data
            const formData = new FormData(document.getElementById('customizationForm'));
            const customizations = {};
            let customizationPrice = 0;

            // Process customizations
            for (let [key, value] of formData.entries()) {
                if (key.startsWith('customizations[')) {
                    const fieldName = key.match(/\[(.*?)\]/)[1];

                    if (key.endsWith('[]')) {
                        // Multiple selection
                        if (!customizations[fieldName]) {
                            customizations[fieldName] = [];
                        }
                        customizations[fieldName].push(value);

                        // Find price for this option
                        const checkbox = document.querySelector(`input[value="${value}"]`);
                        if (checkbox) {
                            customizationPrice += parseFloat(checkbox.dataset.price) || 0;
                        }
                    } else {
                        // Single selection
                        customizations[fieldName] = value;

                        // Find price for this option
                        const select = document.querySelector(`select[name="${key}"]`);
                        if (select) {
                            const selectedOption = select.options[select.selectedIndex];
                            if (selectedOption) {
                                customizationPrice += parseFloat(selectedOption.dataset.price) || 0;
                            }
                        }
                    }
                }
            }

            const menuId = document.getElementById('customizationMenuId').value;

            const response = await fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    menu_id: menuId,
                    quantity: 1,
                    customizations: customizations,
                    customization_price: customizationPrice
                })
            });

            const result = await response.json();

            if (result.success) {
                // Update cart count
                document.getElementById('cartCount').textContent = result.cart_count;

                // Show success feedback
                showToast(result.message, 'success');

                // Close modal
                closeCustomizationModal();

                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            } else {

                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        } catch (error) {

            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    }

    // Toast notification function
    function showToast(message, type = 'success') {
        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.toast');
        existingToasts.forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(toast);

        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    // Update cart count periodically
    async function updateCartCount() {
        try {
            const response = await fetch('{{ route("cart.count") }}');
            const result = await response.json();
            document.getElementById('cartCount').textContent = result.count;
        } catch (error) {
        }
    }

    // Update cart count every 30 seconds
    setInterval(updateCartCount, 30000);

    // Category filter functionality
    document.querySelectorAll('.category-filter').forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;

            // Update active button
            document.querySelectorAll('.category-filter').forEach(btn => {
                btn.classList.remove('bg-japanese-red', 'text-white');
                btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            });
            this.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            this.classList.add('bg-japanese-red', 'text-white');

            // Filter items
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const menuItems = document.querySelectorAll('.menu-item');

        menuItems.forEach(item => {
            const title = item.querySelector('h3').textContent.toLowerCase();
            const description = item.querySelector('p').textContent.toLowerCase();

            if (title.includes(searchTerm) || description.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Close modal when clicking outside
    document.getElementById('customizationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCustomizationModal();
        }
    });
</script>
</body>
</html>
