<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Oishii Japanese Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-japanese-red { background-color: #dc2626; }
        .text-japanese-red { color: #dc2626; }
        .border-japanese-red { border-color: #dc2626; }
        .hover\:bg-japanese-red:hover { background-color: #b91c1c; }
        .menu-item { transition: transform 0.3s ease; }
        .menu-item:hover { transform: translateY(-5px); }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            box-sizing: border-box;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: white;
            border-radius: 12px;
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }

        /* Footer at bottom */
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
        }

        /* Fix for category filters scrolling */
        .category-filters {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none; /* Firefox */
        }
        .category-filters::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Edge */
        }

        /* Responsive image fixes */
        .menu-image-container {
            position: relative;
            height: 12rem; /* Fixed height for consistency */
            overflow: hidden;
        }
        .menu-image-container img {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }

        /* Responsive text sizing */
        @media (max-width: 640px) {
            .menu-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .modal-content {
                margin: 0.5rem;
                max-height: 95vh;
            }

            .menu-title {
                font-size: 1.125rem;
            }

            .menu-price {
                font-size: 1.25rem;
            }

            .menu-description {
                font-size: 0.875rem;
            }
        }

        /* Fix for veg/non-veg badge color classes */
        .veg-badge {
            background-color: #10b981;
        }
        .nonveg-badge {
            background-color: #ef4444;
        }

        /* Cart button animation */
        .cart-btn {
            transition: all 0.3s ease;
        }
        .cart-btn:hover {
            transform: scale(1.05);
        }

        /* Quantity controls */
        .quantity-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .quantity-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            cursor: pointer;
            transition: all 0.2s;
        }
        .quantity-btn:hover {
            background-color: #e5e7eb;
        }
        .quantity-input {
            width: 40px;
            text-align: center;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 4px;
        }

        /* Login required overlay */
        .login-required {
            position: relative;
        }
        .login-required::after {
            content: "Login Required";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border-radius: 8px;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }
        .login-required:hover::after {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
@include('partials.nav')

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

        <!-- Cart Button - Show different state based on login status -->
        @auth
            <button id="cartButton" class="relative bg-japanese-red text-white px-4 sm:px-6 py-3 rounded-lg hover:bg-red-700 transition duration-300 flex items-center justify-center cart-btn">
                <i class="fas fa-shopping-cart mr-2"></i>View Cart
                <span id="cartCount" class="absolute -top-2 -right-2 bg-yellow-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">0</span>
            </button>
        @else
            <button onclick="redirectToLogin()" class="relative bg-gray-400 text-white px-4 sm:px-6 py-3 rounded-lg hover:bg-gray-500 transition duration-300 flex items-center justify-center cart-btn" title="Login to access cart">
                <i class="fas fa-shopping-cart mr-2"></i>View Cart
                <span class="absolute -top-2 -right-2 bg-gray-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">
                    <i class="fas fa-lock text-xs"></i>
                </span>
            </button>
        @endauth
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
                <div class="menu-image-container bg-gradient-to-br from-gray-100 to-gray-50 flex items-center justify-center">
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
                    <span class="absolute top-3 sm:top-4 left-3 sm:left-4 {{ $menu->is_vegetarian ? 'veg-badge' : 'nonveg-badge' }} text-white px-2 py-1 rounded-full text-xs font-medium">
                        {{ $menu->is_vegetarian ? 'Veg' : 'Non-Veg' }}
                    </span>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="menu-title text-lg sm:text-xl font-bold text-gray-900">{{ $menu->name }}</h3>
                        <span class="menu-price text-xl sm:text-2xl font-bold text-japanese-red">${{ number_format($menu->price, 2) }}</span>
                    </div>
                    <p class="menu-description text-gray-600 mb-4 text-sm sm:text-base">{{ $menu->description }}</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-yellow-400">
                            <i class="fas fa-star text-sm"></i>
                            <span class="text-gray-700 font-medium text-sm">{{ $menu->rating ?? '4.5' }}</span>
                            <span class="text-gray-500 text-xs">({{ $menu->review_count ?? '0' }} reviews)</span>
                        </div>
                        <!-- Add to Cart Button - Different behavior based on login status -->
                        @auth
                            <button onclick="addToCart({{ $menu->id }})" class="bg-japanese-red text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 flex items-center justify-center cart-btn">
                                <i class="fas fa-plus mr-2"></i>Add to Cart
                            </button>
                        @else
                            <button onclick="redirectToLogin()" class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500 transition duration-300 flex items-center justify-center cart-btn login-required" title="Login to add to cart">
                                <i class="fas fa-plus mr-2"></i>Add to Cart
                            </button>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Cart Modal - Only show for logged in users -->
    @auth
        <div id="cartModal" class="modal">
            <div class="modal-content">
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-4 sm:mb-6">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900">Your Cart</h3>
                        <button onclick="closeCartModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl sm:text-2xl"></i>
                        </button>
                    </div>

                    <div id="cartItems" class="space-y-4 mb-6">
                        <!-- Cart items will be populated here -->
                        <div class="text-center text-gray-500 py-8" id="emptyCartMessage">
                            <i class="fas fa-shopping-cart text-4xl mb-3"></i>
                            <p>Your cart is empty</p>
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-lg font-semibold">Total:</span>
                            <span id="cartTotal" class="text-xl font-bold text-japanese-red">$0.00</span>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button onclick="closeCartModal()" class="px-4 sm:px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300 order-2 sm:order-1">
                                Continue Shopping
                            </button>
                            <button onclick="checkout()" class="px-4 sm:px-6 py-2 bg-japanese-red text-white rounded-lg hover:bg-red-700 transition duration-300 order-1 sm:order-2" id="checkoutButton" disabled>
                                Proceed to Checkout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endauth
</main>

@include('partials.footer')

<script>
    // Check if user is logged in (you might want to set this from your backend)
    const isLoggedIn = @json(auth()->check());
    const loginUrl = @json(route('login'));

    function redirectToLogin() {
        // Store the current URL to redirect back after login
        sessionStorage.setItem('redirect_url', window.location.href);
        window.location.href = loginUrl;
    }

    // Cart functionality - only initialize if user is logged in
    let cart = isLoggedIn ? JSON.parse(localStorage.getItem('cart')) || [] : [];

    function updateCartCount() {
        if (!isLoggedIn) return;

        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        document.getElementById('cartCount').textContent = totalItems;
    }

    function addToCart(menuId) {
        if (!isLoggedIn) {
            redirectToLogin();
            return;
        }

        const menuItem = {
            id: menuId,
            name: document.querySelector(`[data-category] .menu-title`).textContent,
            price: parseFloat(document.querySelector(`[data-category] .menu-price`).textContent.replace('$', '').replace(',', '')),
            quantity: 1
        };

        const existingItem = cart.find(item => item.id === menuId);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push(menuItem);
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();

        // Show success feedback
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check mr-2"></i>Added!';
        button.classList.add('bg-green-600');
        button.classList.remove('bg-japanese-red');

        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-japanese-red');
        }, 1500);
    }

    function openCartModal() {
        if (!isLoggedIn) {
            redirectToLogin();
            return;
        }
        updateCartDisplay();
        document.getElementById('cartModal').classList.add('active');
    }

    function closeCartModal() {
        document.getElementById('cartModal').classList.remove('active');
    }

    function updateCartDisplay() {
        if (!isLoggedIn) return;

        const cartItems = document.getElementById('cartItems');
        const emptyCartMessage = document.getElementById('emptyCartMessage');
        const checkoutButton = document.getElementById('checkoutButton');
        const cartTotal = document.getElementById('cartTotal');

        if (cart.length === 0) {
            emptyCartMessage.style.display = 'block';
            cartItems.innerHTML = '';
            checkoutButton.disabled = true;
            cartTotal.textContent = '$0.00';
            return;
        }

        emptyCartMessage.style.display = 'none';

        let total = 0;
        let itemsHTML = '';

        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;

            itemsHTML += `
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-semibold">${item.name}</h4>
                        <p class="text-japanese-red font-bold">$${item.price.toFixed(2)}</p>
                    </div>
                    <div class="quantity-controls">
                        <button class="quantity-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                        <input type="number" class="quantity-input" value="${item.quantity}" min="1" onchange="setQuantity(${item.id}, this.value)">
                        <button class="quantity-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                    </div>
                    <button class="text-red-600 hover:text-red-800 ml-3" onclick="removeFromCart(${item.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
        });

        cartItems.innerHTML = itemsHTML;
        checkoutButton.disabled = false;
        cartTotal.textContent = `$${total.toFixed(2)}`;
    }

    function updateQuantity(itemId, change) {
        if (!isLoggedIn) {
            redirectToLogin();
            return;
        }

        const item = cart.find(item => item.id === itemId);
        if (item) {
            item.quantity += change;
            if (item.quantity < 1) {
                removeFromCart(itemId);
            } else {
                localStorage.setItem('cart', JSON.stringify(cart));
                updateCartDisplay();
                updateCartCount();
            }
        }
    }

    function setQuantity(itemId, quantity) {
        if (!isLoggedIn) {
            redirectToLogin();
            return;
        }

        const item = cart.find(item => item.id === itemId);
        if (item && quantity > 0) {
            item.quantity = parseInt(quantity);
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartDisplay();
            updateCartCount();
        }
    }

    function removeFromCart(itemId) {
        if (!isLoggedIn) {
            redirectToLogin();
            return;
        }

        cart = cart.filter(item => item.id !== itemId);
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartDisplay();
        updateCartCount();
    }

    function checkout() {
        if (!isLoggedIn) {
            redirectToLogin();
            return;
        }

        // Redirect to checkout page
        {{--window.location.href = @json(route('checkout'));--}}
    }

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
            const title = item.querySelector('.menu-title').textContent.toLowerCase();
            const description = item.querySelector('.menu-description').textContent.toLowerCase();

            if (title.includes(searchTerm) || description.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Initialize cart count on page load (only for logged in users)
    if (isLoggedIn) {
        updateCartCount();
    }

    // Set up cart button (only for logged in users)
    const cartButton = document.getElementById('cartButton');
    if (cartButton && isLoggedIn) {
        cartButton.addEventListener('click', openCartModal);
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const cartModal = document.getElementById('cartModal');
        if (cartModal && event.target === cartModal) {
            closeCartModal();
        }
    });
</script>
</body>
</html>
