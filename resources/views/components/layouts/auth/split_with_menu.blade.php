<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name', 'Oishii Restaurant') }} - {{ request()->routeIs('register') ? 'Sign Up' : 'Login' }}</title>
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
            
            /* Menu carousel */
            .menu-carousel {
                overflow: hidden;
                position: relative;
            }
            .menu-carousel-track {
                display: flex;
                transition: transform 0.5s ease-in-out;
                animation: scroll 20s linear infinite;
            }
            .menu-carousel-item {
                flex: 0 0 100%;
                min-width: 100%;
            }
            @keyframes scroll {
                0% { transform: translateX(0); }
                100% { transform: translateX(-{{ max(count($uniqueMenuItems ?? []), 1) * 100 }}%); }
            }
            .menu-carousel:hover .menu-carousel-track {
                animation-play-state: paused;
            }
            
            /* Menu card styling */
            .menu-card {
                transition: all 0.3s ease;
            }
            .menu-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            }
        </style>
    </head>
    <body class="bg-gray-50 min-h-screen">
        <div class="relative grid min-h-screen lg:grid-cols-2">
            <!-- Left Side - Menu Carousel -->
            <div class="wave-pattern text-white relative hidden h-screen flex-col p-10 lg:flex overflow-hidden">
                <div class="absolute inset-0 bg-black opacity-20"></div>
                
                <!-- Logo -->
                <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-medium mb-8" wire:navigate>
                    <span class="flex h-12 w-12 items-center justify-center rounded-lg bg-white bg-opacity-20 backdrop-blur-sm">
                        <i class="fas fa-bowl-rice text-white text-2xl"></i>
                    </span>
                    <span class="ml-3 text-2xl font-bold">Oishii</span>
                </a>

                <!-- Menu Carousel -->
                <div class="relative z-20 flex-1 flex flex-col justify-center">
                    <div class="mb-8">
                        <h2 class="text-4xl font-bold mb-3">Discover Our Menu</h2>
                        <p class="text-red-100 text-lg">Authentic Japanese cuisine awaits you</p>
                    </div>
                    
                    <div class="menu-carousel h-[500px]">
                        <div class="menu-carousel-track">
                            @if(isset($menuItems) && count($menuItems) > 0)
                            @foreach($menuItems as $menu)
                                <div class="menu-carousel-item px-4">
                                    <div class="menu-card bg-white bg-opacity-15 backdrop-blur-md rounded-3xl p-8 h-full flex flex-col shadow-2xl border-2 border-white border-opacity-30">
                                        @if($menu->image_path)
                                            <div class="relative h-64 mb-6 rounded-2xl overflow-hidden shadow-lg">
                                                <img src="{{ asset($menu->image_path) }}" 
                                                     alt="{{ $menu->name }}" 
                                                     class="w-full h-full object-cover">
                                                <div class="absolute top-3 right-3 bg-japanese-red bg-opacity-95 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                                                    {{ $menu->category->name ?? 'Featured' }}
                                                </div>
                                                <div class="absolute top-3 left-3 {{ $menu->is_vegetarian ? 'bg-green-500' : 'bg-red-500' }} text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                                    {{ $menu->is_vegetarian ? 'Veg' : 'Non-Veg' }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="h-64 mb-6 rounded-2xl bg-white bg-opacity-10 flex items-center justify-center shadow-lg">
                                                <i class="fas fa-utensils text-8xl text-white opacity-50"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="flex-1 flex flex-col">
                                            <h3 class="text-2xl font-bold mb-3 text-white leading-tight">{{ $menu->name }}</h3>
                                            <p class="text-red-50 text-base mb-6 line-clamp-3 flex-1 leading-relaxed">{{ $menu->description ?? 'Delicious Japanese cuisine' }}</p>
                                            
                                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-white border-opacity-20">
                                                <div class="flex items-center space-x-2 text-yellow-300">
                                                    <i class="fas fa-star text-lg"></i>
                                                    <span class="text-base font-semibold">{{ $menu->rating ?? '4.5' }}</span>
                                                    <span class="text-red-200 text-sm">({{ $menu->review_count ?? '0' }} reviews)</span>
                                                </div>
                                                <div class="text-3xl font-bold text-white">
                                                    ${{ number_format($menu->price, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @endif
                            
                            <!-- Duplicate items for seamless loop -->
                            @if(isset($menuItems) && count($menuItems) > 0)
                            @foreach($menuItems as $menu)
                                <div class="menu-carousel-item px-4">
                                    <div class="menu-card bg-white bg-opacity-15 backdrop-blur-md rounded-3xl p-8 h-full flex flex-col shadow-2xl border-2 border-white border-opacity-30">
                                        @if($menu->image_path)
                                            <div class="relative h-64 mb-6 rounded-2xl overflow-hidden shadow-lg">
                                                <img src="{{ asset($menu->image_path) }}" 
                                                     alt="{{ $menu->name }}" 
                                                     class="w-full h-full object-cover">
                                                <div class="absolute top-3 right-3 bg-japanese-red bg-opacity-95 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                                                    {{ $menu->category->name ?? 'Featured' }}
                                                </div>
                                                <div class="absolute top-3 left-3 {{ $menu->is_vegetarian ? 'bg-green-500' : 'bg-red-500' }} text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                                    {{ $menu->is_vegetarian ? 'Veg' : 'Non-Veg' }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="h-64 mb-6 rounded-2xl bg-white bg-opacity-10 flex items-center justify-center shadow-lg">
                                                <i class="fas fa-utensils text-8xl text-white opacity-50"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="flex-1 flex flex-col">
                                            <h3 class="text-2xl font-bold mb-3 text-white leading-tight">{{ $menu->name }}</h3>
                                            <p class="text-red-50 text-base mb-6 line-clamp-3 flex-1 leading-relaxed">{{ $menu->description ?? 'Delicious Japanese cuisine' }}</p>
                                            
                                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-white border-opacity-20">
                                                <div class="flex items-center space-x-2 text-yellow-300">
                                                    <i class="fas fa-star text-lg"></i>
                                                    <span class="text-base font-semibold">{{ $menu->rating ?? '4.5' }}</span>
                                                    <span class="text-red-200 text-sm">({{ $menu->review_count ?? '0' }} reviews)</span>
                                                </div>
                                                <div class="text-3xl font-bold text-white">
                                                    ${{ number_format($menu->price, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    
                    <!-- Carousel Indicators -->
                    <div class="flex justify-center space-x-3 mt-8">
                        @if(isset($uniqueMenuItems) && count($uniqueMenuItems) > 0)
                        @foreach($uniqueMenuItems as $index => $menu)
                            <div class="w-3 h-3 rounded-full bg-white {{ $index === 0 ? 'bg-opacity-100 shadow-lg' : 'bg-opacity-40' }} transition-all duration-300 cursor-pointer" 
                                 id="indicator-{{ $index }}"></div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Side - Auth Form -->
            <div class="w-full bg-white flex items-center justify-center p-8 lg:p-12">
                <div class="mx-auto w-full max-w-md">
                    <!-- Mobile Logo -->
                    <a href="{{ route('home') }}" class="flex items-center justify-center mb-8 lg:hidden" wire:navigate>
                        <span class="flex h-12 w-12 items-center justify-center rounded-lg bg-japanese-red">
                            <i class="fas fa-bowl-rice text-white text-2xl"></i>
                        </span>
                        <span class="ml-3 text-2xl font-bold text-gray-800">Oishii</span>
                    </a>
                    
                    <!-- Desktop Logo -->
                    <div class="hidden lg:flex items-center mb-8">
                        <span class="flex h-12 w-12 items-center justify-center rounded-lg bg-japanese-red">
                            <i class="fas fa-bowl-rice text-white text-2xl"></i>
                        </span>
                        <span class="ml-3 text-2xl font-bold text-gray-800">Oishii</span>
                    </div>
                    
                    {{ $slot }}
                </div>
            </div>
        </div>
        
        <script>
            // Update carousel indicators
            document.addEventListener('DOMContentLoaded', function() {
                const track = document.querySelector('.menu-carousel-track');
                const indicators = document.querySelectorAll('[id^="indicator-"]');
                const totalItems = {{ max(count($uniqueMenuItems ?? []), 1) }};
                let currentIndex = 0;

                function updateIndicators() {
                    indicators.forEach((indicator, index) => {
                        if (index === currentIndex % totalItems) {
                            indicator.classList.remove('bg-opacity-40');
                            indicator.classList.add('bg-opacity-100', 'shadow-lg');
                        } else {
                            indicator.classList.remove('bg-opacity-100', 'shadow-lg');
                            indicator.classList.add('bg-opacity-40');
                        }
                    });
                }

                // Update indicators every 2 seconds (matching animation speed)
                setInterval(() => {
                    currentIndex = (currentIndex + 1) % totalItems;
                    updateIndicators();
                }, 2000);
                
                // Click indicator to jump to that item
                indicators.forEach((indicator, index) => {
                    indicator.addEventListener('click', () => {
                        currentIndex = index;
                        updateIndicators();
                    });
                });
            });
        </script>
    </body>
</html>
