<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Menu - Oishii Japanese Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-japanese-red { background-color: #dc2626; }
        .text-japanese-red { color: #dc2626; }
        .border-japanese-red { border-color: #dc2626; }
        .hover\:bg-japanese-red:hover { background-color: #b91c1c; }
        .menu-item { transition: transform 0.3s ease; }
        .menu-item:hover { transform: translateY(-5px); }

        /* Mobile menu styles */
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        .mobile-menu.active {
            transform: translateX(0);
        }
        .hamburger-line {
            transition: all 0.3s ease;
        }
        .hamburger.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }
        .hamburger.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }
        .hamburger.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .category-filters {
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 10px;
            }
            .category-filter {
                display: inline-block;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
@include('partials.nav')
<!-- Main Content -->

<!-- Main Content -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Message -->
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">Staff Dashboard</h1>
        <p class="text-lg text-gray-600">Welcome back! Here's today's order overview.</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pending Orders</p>
                    <p class="text-3xl font-bold text-gray-800">12</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-clock text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Preparing</p>
                    <p class="text-3xl font-bold text-gray-800">8</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-utensils text-yellow-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Ready for Pickup</p>
                    <p class="text-3xl font-bold text-gray-800">5</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Today's Orders</p>
                    <p class="text-3xl font-bold text-gray-800">42</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-receipt text-purple-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <a href="/orders" class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 hover:border-japanese-red hover:shadow-xl transition duration-300">
            <div class="flex items-center space-x-4">
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-list-alt text-japanese-red text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Manage Orders</h3>
                    <p class="text-gray-600 text-sm">View and update all orders</p>
                </div>
            </div>
        </a>

        <a href="/tracking" class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 hover:border-japanese-red hover:shadow-xl transition duration-300">
            <div class="flex items-center space-x-4">
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-map-marker-alt text-blue-500 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Order Tracking</h3>
                    <p class="text-gray-600 text-sm">Track order status</p>
                </div>
            </div>
        </a>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center space-x-4">
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-chart-bar text-green-500 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Today's Specials</h3>
                    <p class="text-gray-600 text-sm">Spicy Tonkotsu Ramen</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Recent Orders</h2>
            <a href="/orders" class="text-japanese-red hover:text-red-700 font-medium">View All</a>
        </div>

        <div class="space-y-4">
            <!-- Order 1 -->
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                <div>
                    <h4 class="font-semibold text-gray-800">Order #ORD-7845</h4>
                    <p class="text-gray-600 text-sm">Takashi Tanaka • 2x Tonkotsu Ramen</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="status-pending px-3 py-1 rounded-full text-sm font-medium">Pending</span>
                    <span class="text-gray-500">5 min ago</span>
                </div>
            </div>

            <!-- Order 2 -->
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                <div>
                    <h4 class="font-semibold text-gray-800">Order #ORD-7844</h4>
                    <p class="text-gray-600 text-sm">Yuki Sato • Premium Bento Box</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="status-preparing px-3 py-1 rounded-full text-sm font-medium">Preparing</span>
                    <span class="text-gray-500">12 min ago</span>
                </div>
            </div>

            <!-- Order 3 -->
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                <div>
                    <h4 class="font-semibold text-gray-800">Order #ORD-7843</h4>
                    <p class="text-gray-600 text-sm">Hanako Kimura • 4x Salmon Sashimi</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="status-ready px-3 py-1 rounded-full text-sm font-medium">Ready</span>
                    <span class="text-gray-500">18 min ago</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Kitchen Status -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Popular Items -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Popular Today</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">Tonkotsu Ramen</span>
                    <span class="bg-japanese-red text-white px-2 py-1 rounded-full text-sm">24 orders</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">Salmon Sashimi</span>
                    <span class="bg-japanese-red text-white px-2 py-1 rounded-full text-sm">18 orders</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">Premium Bento Box</span>
                    <span class="bg-japanese-red text-white px-2 py-1 rounded-full text-sm">15 orders</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">Spicy Tuna Roll</span>
                    <span class="bg-japanese-red text-white px-2 py-1 rounded-full text-sm">12 orders</span>
                </div>
            </div>
        </div>

        <!-- Kitchen Notes -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Kitchen Notes</h2>
            <div class="space-y-4">
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
                    <p class="text-yellow-700 font-medium">Low stock: Salmon for sashimi</p>
                </div>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                    <p class="text-blue-700">New staff training: Remember to check spice levels</p>
                </div>
                <div class="bg-green-50 border-l-4 border-green-500 p-4">
                    <p class="text-green-700">Special request: Extra noodles for table 12</p>
                </div>
            </div>
        </div>
    </div>
</main>

@include('partials.footer')

<script>
    // Mobile menu toggle functionality
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');

    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        mobileMenu.classList.toggle('active');
    });

    // Close mobile menu when clicking on a link
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            mobileMenu.classList.remove('active');
        });
    });

    // Simple category filter functionality
    document.querySelectorAll('.category-filter').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.category-filter').forEach(btn => {
                btn.classList.remove('bg-japanese-red', 'text-white');
                btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            });
            this.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            this.classList.add('bg-japanese-red', 'text-white');
        });
    });
</script>
</body>
</html>
