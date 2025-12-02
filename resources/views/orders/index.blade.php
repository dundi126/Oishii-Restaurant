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
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->


    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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
                    <p class="text-gray-500 text-sm">Today's Revenue</p>
                    <p class="text-3xl font-bold text-gray-800">$42,580.00</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-yen-sign text-purple-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
        <div class="flex items-center space-x-4 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <input type="text" placeholder="Search orders..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent w-full">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
        <div class="flex space-x-3 w-full sm:w-auto">
            <button class="bg-white text-gray-700 px-4 py-2 rounded-lg border border-gray-300 hover:border-japanese-red hover:text-japanese-red transition duration-300 flex items-center w-full sm:w-auto justify-center">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
            <button class="bg-japanese-red text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 flex items-center w-full sm:w-auto justify-center">
                <i class="fas fa-sync-alt mr-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Order Filters -->
    <div class="order-filters flex flex-nowrap justify-start md:justify-center gap-3 mb-8">
        <button class="order-filter px-4 py-3 sm:px-6 sm:py-3 rounded-full bg-japanese-red text-white font-medium shadow-lg">
            All Orders
        </button>
        <button class="order-filter px-4 py-3 sm:px-6 sm:py-3 rounded-full bg-white text-gray-700 border border-gray-300 hover:border-japanese-red hover:text-japanese-red font-medium transition duration-300">
            Pending
        </button>
        <button class="order-filter px-4 py-3 sm:px-6 sm:py-3 rounded-full bg-white text-gray-700 border border-gray-300 hover:border-japanese-red hover:text-japanese-red font-medium transition duration-300">
            Preparing
        </button>
        <button class="order-filter px-4 py-3 sm:px-6 sm:py-3 rounded-full bg-white text-gray-700 border border-gray-300 hover:border-japanese-red hover:text-japanese-red font-medium transition duration-300">
            Ready
        </button>
        <button class="order-filter px-4 py-3 sm:px-6 sm:py-3 rounded-full bg-white text-gray-700 border border-gray-300 hover:border-japanese-red hover:text-japanese-red font-medium transition duration-300">
            Completed
        </button>
    </div>

    <!-- Orders Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Order 1 - New Order -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-japanese-red new-order">
            <div class="bg-japanese-red text-white p-4 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg">Order #ORD-7842</h3>
                    <p class="text-red-100">New - Just received</p>
                </div>
                <span class="bg-white text-japanese-red px-3 py-1 rounded-full text-sm font-bold">NEW</span>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="font-semibold text-gray-800">Takashi Tanaka</p>
                        <p class="text-gray-600 text-sm">Table 12 • 2 guests</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">Pending</span>
                </div>

                <div class="border-t border-gray-200 pt-4 mb-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span>1× Tonkotsu Ramen</span>
                        <span>$1,480</span>
                    </div>
                    <div class="flex justify-between text-sm mb-2">
                        <span>2× Salmon Sashimi</span>
                        <span>$4,400</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>1× Edamame</span>
                        <span>$450</span>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Order Time</p>
                        <p class="font-semibold">18:24</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500 text-sm">Total</p>
                        <p class="text-xl font-bold text-japanese-red">$6,330</p>
                    </div>
                </div>

                <div class="flex space-x-3 mt-6">
                    <button class="flex-1 bg-japanese-red text-white py-2 rounded-lg hover:bg-red-700 transition duration-300">
                        Accept Order
                    </button>
                    <button class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300 transition duration-300">
                        View Details
                    </button>
                </div>
            </div>
        </div>

        <!-- Order 2 - Preparing -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
            <div class="bg-blue-50 p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">Order #ORD-7841</h3>
                        <p class="text-gray-600">Preparing - Started 10 min ago</p>
                    </div>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">Preparing</span>
                </div>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="font-semibold text-gray-800">Yuki Sato</p>
                        <p class="text-gray-600 text-sm">Takeaway • #45</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500 text-sm">Est. Ready</p>
                        <p class="font-semibold">18:45</p>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 mb-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span>1× Premium Bento Box</span>
                        <span>$1,850</span>
                    </div>
                    <div class="flex justify-between text-sm mb-2">
                        <span>1× Miso Soup</span>
                        <span>$250</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>1× Green Tea</span>
                        <span>$300</span>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Order Time</p>
                        <p class="font-semibold">18:15</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500 text-sm">Total</p>
                        <p class="text-xl font-bold text-japanese-red">$2,400</p>
                    </div>
                </div>

                <div class="flex space-x-3 mt-6">
                    <button class="flex-1 bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition duration-300">
                        Mark Ready
                    </button>
                    <button class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300 transition duration-300">
                        View Details
                    </button>
                </div>
            </div>
        </div>

        <!-- Order 3 - Ready for Pickup -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
            <div class="bg-green-50 p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">Order #ORD-7840</h3>
                        <p class="text-gray-600">Ready - Waiting for pickup</p>
                    </div>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Ready</span>
                </div>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="font-semibold text-gray-800">Delivery - Kenji Watanabe</p>
                        <p class="text-gray-600 text-sm">Address: 3-5-12 Shibuya</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500 text-sm">Ready Since</p>
                        <p class="font-semibold">18:20</p>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 mb-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span>2× Spicy Tuna Roll</span>
                        <span>$1,200</span>
                    </div>
                    <div class="flex justify-between text-sm mb-2">
                        <span>1× Chicken Teriyaki</span>
                        <span>$1,200</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>1× Gyoza (6pcs)</span>
                        <span>$650</span>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Order Time</p>
                        <p class="font-semibold">17:55</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500 text-sm">Total</p>
                        <p class="text-xl font-bold text-japanese-red">$3,050</p>
                    </div>
                </div>

                <div class="flex space-x-3 mt-6">
                    <button class="flex-1 bg-japanese-red text-white py-2 rounded-lg hover:bg-red-700 transition duration-300">
                        Notify Customer
                    </button>
                    <button class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300 transition duration-300">
                        View Details
                    </button>
                </div>
            </div>
        </div>

        <!-- Order 4 - Preparing -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
            <div class="bg-blue-50 p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">Order #ORD-7839</h3>
                        <p class="text-gray-600">Preparing - Started 15 min ago</p>
                    </div>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">Preparing</span>
                </div>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="font-semibold text-gray-800">Hanako Kimura</p>
                        <p class="text-gray-600 text-sm">Table 8 • 4 guests</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500 text-sm">Est. Ready</p>
                        <p class="font-semibold">19:00</p>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 mb-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span>4× Tonkotsu Ramen</span>
                        <span>$5,920</span>
                    </div>
                    <div class="flex justify-between text-sm mb-2">
                        <span>2× California Roll</span>
                        <span>$1,100</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>4× Green Tea</span>
                        <span>$1,200</span>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Order Time</p>
                        <p class="font-semibold">18:10</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500 text-sm">Total</p>
                        <p class="text-xl font-bold text-japanese-red">$8,220</p>
                    </div>
                </div>

                <div class="flex space-x-3 mt-6">
                    <button class="flex-1 bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition duration-300">
                        Mark Ready
                    </button>
                    <button class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300 transition duration-300">
                        View Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center items-center space-x-2 mt-12">
        <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="px-4 py-2 bg-japanese-red text-white rounded-lg">1</button>
        <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">2</button>
        <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">3</button>
        <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
            <i class="fas fa-chevron-right"></i>
        </button>
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
