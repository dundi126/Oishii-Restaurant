<!-- Header -->
<header class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center space-x-3"><a href="/">
                <i class="fas fa-bowl-rice text-japanese-red text-2xl"></i>
                 <span class="text-xl font-bold text-gray-800">Oishii </span></a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-japanese-red font-semibold border-b-2 border-japanese-red' : 'text-gray-600 hover:text-japanese-red font-medium' }}">Home</a>
                <a href="{{ route('menu.index') }}" class="{{ request()->routeIs('menu.*') ? 'text-japanese-red font-semibold border-b-2 border-japanese-red' : 'text-gray-600 hover:text-japanese-red font-medium' }}">Menu</a>
                <a href="{{ route('orders.track') }}" class="{{ request()->routeIs('orders.track') ? 'text-japanese-red font-semibold border-b-2 border-japanese-red' : 'text-gray-600 hover:text-japanese-red font-medium' }}">Track Order</a>
                @auth
                    @can('isAdmin')
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'text-japanese-red font-semibold border-b-2 border-japanese-red' : 'text-gray-600 hover:text-japanese-red font-medium' }}">
                            <i class="fas fa-user-cog mr-1"></i>Admin Dashboard
                        </a>
                    @endcan
                @endauth
            </nav>

            <div class="flex items-center space-x-4">
                @auth
                    <!-- Show user name and logout when authenticated -->
                    <span class="text-gray-700 hidden sm:inline">{{ Auth::user()->name }}</span>

                    <!-- Laravel Logout Form (Desktop) -->
                    <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                        @csrf
                        <button type="submit" class="bg-japanese-red text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                @else
                    <!-- Show login button when not authenticated -->
                    <a href="{{ route('login') }}" class="bg-japanese-red text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 hidden md:block">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                @endauth

                <!-- Hamburger Menu Button (Mobile) -->
                <button id="hamburger" class="hamburger md:hidden flex flex-col justify-center items-center w-8 h-8 space-y-1">
                    <span class="hamburger-line block w-6 h-0.5 bg-gray-800"></span>
                    <span class="hamburger-line block w-6 h-0.5 bg-gray-800"></span>
                    <span class="hamburger-line block w-6 h-0.5 bg-gray-800"></span>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div id="mobileMenu" class="mobile-menu fixed inset-0 bg-white z-40 md:hidden pt-20 px-6 hidden">
    <nav class="flex flex-col space-y-6">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-japanese-red text-xl font-semibold border-b-2 border-japanese-red pb-2' : 'text-gray-600 text-xl hover:text-japanese-red font-medium' }}">Home</a>
        <a href="{{ route('menu.index') }}" class="{{ request()->routeIs('menu.*') ? 'text-japanese-red text-xl font-semibold border-b-2 border-japanese-red pb-2' : 'text-gray-600 text-xl hover:text-japanese-red font-medium' }}">Menu</a>
        <a href="{{ route('orders.track') }}" class="{{ request()->routeIs('orders.track') ? 'text-japanese-red text-xl font-semibold border-b-2 border-japanese-red pb-2' : 'text-gray-600 text-xl hover:text-japanese-red font-medium' }}">Track Order</a>
        @auth
            @can('isAdmin')
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'text-japanese-red text-xl font-semibold border-b-2 border-japanese-red pb-2' : 'text-gray-600 text-xl hover:text-japanese-red font-medium' }}">
                    <i class="fas fa-user-cog mr-2"></i>Admin Dashboard
                </a>
            @endcan
        @endauth

        <div class="pt-6 border-t border-gray-200">
            @auth
                <!-- Show user name and logout when authenticated (Mobile) -->
                <span class="text-gray-700 block mb-4">Welcome, {{ Auth::user()->name }}</span>

                <!-- Laravel Logout Form (Mobile) -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-japanese-red text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 w-full">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            @else
                <!-- Show login button when not authenticated (Mobile) -->
                <a href="{{ route('login') }}" class="bg-japanese-red text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 w-full block text-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </a>
            @endauth
        </div>
    </nav>
</div>

<script>
    // Mobile menu toggle
    document.getElementById('hamburger').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobileMenu');
        mobileMenu.classList.toggle('hidden');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const mobileMenu = document.getElementById('mobileMenu');
        const hamburger = document.getElementById('hamburger');

        if (!mobileMenu.contains(event.target) && !hamburger.contains(event.target)) {
            mobileMenu.classList.add('hidden');
        }
    });
</script>

<style>
    .text-japanese-red {
        color: #dc2626; /* Japanese red color */
    }
    .bg-japanese-red {
        background-color: #dc2626;
    }
    .border-japanese-red {
        border-color: #dc2626;
    }
</style>
