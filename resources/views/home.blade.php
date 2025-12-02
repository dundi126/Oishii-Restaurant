<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oishii - Authentic Japanese Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            font-family: 'Noto Sans JP', sans-serif;
        }
        .bg-japanese-red { background-color: #dc2626; }
        .text-japanese-red { color: #dc2626; }
        .border-japanese-red { border-color: #dc2626; }
        .hover\:bg-japanese-red:hover { background-color: #b91c1c; }
        
        /* Japanese pattern background */
        .japanese-pattern {
            background-image: 
                repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(220, 38, 38, 0.03) 10px, rgba(220, 38, 38, 0.03) 20px);
        }
        
        /* Cherry blossom animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        .cherry-blossom {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Fade in animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }
        
        /* Menu item hover effect */
        .menu-card {
            transition: all 0.3s ease;
        }
        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        /* Japanese wave pattern */
        .wave-pattern {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            position: relative;
            overflow: hidden;
        }
        .wave-pattern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('partials.nav')

    <!-- Hero Section -->
    <section class="wave-pattern text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32">
            <div class="text-center fade-in-up">
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold mb-6 tracking-tight">
                    <span class="block">おいしい</span>
                    <span class="block text-4xl sm:text-5xl lg:text-6xl mt-2">Oishii</span>
                </h1>
                <p class="text-xl sm:text-2xl mb-4 text-red-100">Authentic Japanese Cuisine</p>
                <p class="text-lg sm:text-xl mb-8 text-red-50 max-w-2xl mx-auto">
                    Experience the art of traditional Japanese cooking with our carefully crafted dishes, 
                    made with the finest ingredients and served with authentic hospitality.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('menu.index') }}" 
                       class="bg-white text-japanese-red px-8 py-4 rounded-lg font-semibold text-lg hover:bg-red-50 transition duration-300 shadow-lg">
                        <i class="fas fa-utensils mr-2"></i>View Our Menu
                    </a>
                    <a href="#about" 
                       class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-japanese-red transition duration-300">
                        <i class="fas fa-info-circle mr-2"></i>Learn More
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Decorative cherry blossoms -->
        <div class="absolute top-10 left-10 cherry-blossom opacity-30">
            <i class="fas fa-circle text-white text-2xl"></i>
        </div>
        <div class="absolute top-20 right-20 cherry-blossom opacity-20" style="animation-delay: 2s;">
            <i class="fas fa-circle text-white text-xl"></i>
        </div>
        <div class="absolute bottom-20 left-1/4 cherry-blossom opacity-25" style="animation-delay: 4s;">
            <i class="fas fa-circle text-white text-lg"></i>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16 sm:py-24 japanese-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 fade-in-up">
                <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
                    <span class="text-japanese-red">私たちについて</span>
                    <span class="block text-2xl sm:text-3xl mt-2">About Oishii</span>
                </h2>
                <div class="w-24 h-1 bg-japanese-red mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="fade-in-up">
                    <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-12">
                        <h3 class="text-3xl font-bold text-gray-900 mb-6">Our Story</h3>
                        <p class="text-gray-700 text-lg leading-relaxed mb-4">
                            Oishii (おいしい) means "delicious" in Japanese, and that's exactly what we strive to deliver 
                            with every dish. Founded with a passion for authentic Japanese cuisine, we bring the traditional 
                            flavors of Japan to your table.
                        </p>
                        <p class="text-gray-700 text-lg leading-relaxed mb-4">
                            Our chefs have trained in the art of Japanese cooking, mastering techniques passed down through 
                            generations. We use only the freshest ingredients, sourced with care to ensure every bite is a 
                            celebration of flavor and tradition.
                        </p>
                        <p class="text-gray-700 text-lg leading-relaxed">
                            From our signature ramen bowls to our delicate sushi rolls, every dish is prepared with 
                            meticulous attention to detail and served with the warm hospitality that Japanese culture is 
                            known for.
                        </p>
                    </div>
                </div>

                <div class="fade-in-up" style="animation-delay: 0.2s;">
                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl shadow-xl p-8 sm:p-12 border-4 border-japanese-red">
                        <h3 class="text-3xl font-bold text-gray-900 mb-6">Our Values</h3>
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-japanese-red rounded-full flex items-center justify-center">
                                        <i class="fas fa-heart text-white text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Authenticity</h4>
                                    <p class="text-gray-700">We stay true to traditional Japanese recipes and cooking methods.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-japanese-red rounded-full flex items-center justify-center">
                                        <i class="fas fa-leaf text-white text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Fresh Ingredients</h4>
                                    <p class="text-gray-700">Only the finest, freshest ingredients make it to your plate.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-japanese-red rounded-full flex items-center justify-center">
                                        <i class="fas fa-users text-white text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Hospitality</h4>
                                    <p class="text-gray-700">Omotenashi - the spirit of selfless hospitality in every interaction.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Menu Section -->
    <section class="py-16 sm:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 fade-in-up">
                <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
                    <span class="text-japanese-red">おすすめ</span>
                    <span class="block text-2xl sm:text-3xl mt-2">Featured Dishes</span>
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Discover our most popular dishes, loved by our customers
                </p>
                <div class="w-24 h-1 bg-japanese-red mx-auto mt-4"></div>
            </div>

            @if(isset($featuredMenus) && count($featuredMenus) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @foreach($featuredMenus as $menu)
                        <div class="menu-card bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                            <div class="relative h-48 overflow-hidden">
                                @if($menu->image_path)
                                    <img src="{{ asset($menu->image_path) }}" 
                                         alt="{{ $menu->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <i class="fas fa-utensils text-6xl text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3 bg-japanese-red text-white px-3 py-1 rounded-full text-xs font-medium">
                                    {{ $menu->category->name }}
                                </div>
                                <div class="absolute top-3 left-3 {{ $menu->is_vegetarian ? 'bg-green-500' : 'bg-red-500' }} text-white px-2 py-1 rounded-full text-xs font-medium">
                                    {{ $menu->is_vegetarian ? 'Veg' : 'Non-Veg' }}
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $menu->name }}</h3>
                                    <span class="text-2xl font-bold text-japanese-red">${{ number_format($menu->price, 2) }}</span>
                                </div>
                                <p class="text-gray-600 mb-4 text-sm line-clamp-2">{{ $menu->description }}</p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-1 text-yellow-400">
                                        <i class="fas fa-star text-sm"></i>
                                        <span class="text-gray-700 font-medium text-sm">{{ $menu->rating ?? '4.5' }}</span>
                                        <span class="text-gray-500 text-xs">({{ $menu->review_count ?? '0' }} reviews)</span>
                                    </div>
                                    @auth
                                        <button onclick="window.location.href='{{ route('menu.index') }}#menu-{{ $menu->id }}'" 
                                                class="bg-japanese-red text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 text-sm">
                                            <i class="fas fa-plus mr-1"></i>Order
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" 
                                           class="bg-japanese-red text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300 text-sm">
                                            <i class="fas fa-plus mr-1"></i>Order
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="text-center mt-12">
                    <a href="{{ route('menu.index') }}" 
                       class="inline-block bg-japanese-red text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-red-700 transition duration-300 shadow-lg">
                        <i class="fas fa-utensils mr-2"></i>View Full Menu
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-utensils text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Menu items coming soon!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-16 sm:py-24 bg-gradient-to-br from-red-50 to-red-100 japanese-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 fade-in-up">
                <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
                    <span class="text-japanese-red">なぜ選ぶ</span>
                    <span class="block text-2xl sm:text-3xl mt-2">Why Choose Oishii</span>
                </h2>
                <div class="w-24 h-1 bg-japanese-red mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <div class="bg-white rounded-xl shadow-lg p-6 text-center fade-in-up">
                    <div class="w-16 h-16 bg-japanese-red rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shipping-fast text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Fast Delivery</h3>
                    <p class="text-gray-600">Quick and reliable delivery to your doorstep</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 text-center fade-in-up" style="animation-delay: 0.1s;">
                    <div class="w-16 h-16 bg-japanese-red rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-award text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Premium Quality</h3>
                    <p class="text-gray-600">Only the finest ingredients in every dish</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 text-center fade-in-up" style="animation-delay: 0.2s;">
                    <div class="w-16 h-16 bg-japanese-red rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-chef text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Expert Chefs</h3>
                    <p class="text-gray-600">Trained in traditional Japanese culinary arts</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 text-center fade-in-up" style="animation-delay: 0.3s;">
                    <div class="w-16 h-16 bg-japanese-red rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Made with Love</h3>
                    <p class="text-gray-600">Every dish prepared with passion and care</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-16 sm:py-24 wave-pattern text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl sm:text-5xl font-bold mb-6 fade-in-up">
                Ready to Experience Authentic Japanese Cuisine?
            </h2>
            <p class="text-xl mb-8 text-red-100 fade-in-up" style="animation-delay: 0.2s;">
                Order now and taste the difference that authentic Japanese cooking makes
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center fade-in-up" style="animation-delay: 0.4s;">
                <a href="{{ route('menu.index') }}" 
                   class="bg-white text-japanese-red px-8 py-4 rounded-lg font-semibold text-lg hover:bg-red-50 transition duration-300 shadow-lg">
                    <i class="fas fa-shopping-cart mr-2"></i>Order Now
                </a>
                @auth
                    <a href="{{ route('orders.track') }}" 
                       class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-japanese-red transition duration-300">
                        <i class="fas fa-clipboard-list mr-2"></i>Track Your Order
                    </a>
                @else
                    <a href="{{ route('register') }}" 
                       class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-japanese-red transition duration-300">
                        <i class="fas fa-user-plus mr-2"></i>Create Account
                    </a>
                @endauth
            </div>
        </div>
    </section>

    @include('partials.footer')

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Fade in on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in-up').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';
            observer.observe(el);
        });
    </script>
</body>
</html>

