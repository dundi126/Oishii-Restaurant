<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
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
                100% { transform: translateX(-{{ count($menuItems) * 100 }}%); }
            }
            .menu-carousel:hover .menu-carousel-track {
                animation-play-state: paused;
            }
            .menu-card {
                transition: transform 0.3s ease;
            }
            .menu-card:hover {
                transform: scale(1.05);
            }
        </style>
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="relative grid h-dvh flex-col items-center justify-center lg:grid-cols-2 lg:px-0">
            <!-- Left Side - Menu Carousel -->
            <div class="bg-gradient-to-br from-red-600 to-red-800 relative hidden h-full flex-col p-10 text-white lg:flex overflow-hidden">
                <div class="absolute inset-0 bg-black opacity-20"></div>
                
                <!-- Logo -->
                <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-medium mb-8" wire:navigate>
                    <span class="flex h-10 w-10 items-center justify-center rounded-md bg-white bg-opacity-20">
                        <x-app-logo-icon class="h-7 fill-current text-white" />
                    </span>
                    <span class="ml-3 text-xl font-bold">{{ config('app.name', 'Oishii Restaurant') }}</span>
                </a>

                <!-- Menu Carousel -->
                <div class="relative z-20 flex-1 flex flex-col justify-center">
                    <div class="mb-6">
                        <h2 class="text-3xl font-bold mb-2">Discover Our Menu</h2>
                        <p class="text-red-100 text-lg">Authentic Japanese cuisine awaits you</p>
                    </div>
                    
                    <div class="menu-carousel h-96">
                        <div class="menu-carousel-track">
                            @foreach($menuItems as $menu)
                                <div class="menu-carousel-item px-4">
                                    <div class="menu-card bg-white bg-opacity-10 backdrop-blur-md rounded-2xl p-6 h-full flex flex-col shadow-2xl border border-white border-opacity-20">
                                        @if($menu->image_path)
                                            <div class="relative h-48 mb-4 rounded-xl overflow-hidden">
                                                <img src="{{ asset($menu->image_path) }}" 
                                                     alt="{{ $menu->name }}" 
                                                     class="w-full h-full object-cover">
                                                <div class="absolute top-2 right-2 bg-red-600 bg-opacity-90 text-white px-3 py-1 rounded-full text-xs font-medium">
                                                    {{ $menu->category->name }}
                                                </div>
                                                <div class="absolute top-2 left-2 {{ $menu->is_vegetarian ? 'bg-green-500' : 'bg-red-500' }} text-white px-2 py-1 rounded-full text-xs font-medium">
                                                    {{ $menu->is_vegetarian ? 'Veg' : 'Non-Veg' }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="h-48 mb-4 rounded-xl bg-white bg-opacity-10 flex items-center justify-center">
                                                <i class="fas fa-utensils text-6xl text-white opacity-50"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="flex-1 flex flex-col">
                                            <h3 class="text-xl font-bold mb-2 text-white">{{ $menu->name }}</h3>
                                            <p class="text-red-100 text-sm mb-4 line-clamp-2 flex-1">{{ $menu->description }}</p>
                                            
                                            <div class="flex items-center justify-between mt-auto">
                                                <div class="flex items-center space-x-1 text-yellow-300">
                                                    <i class="fas fa-star text-sm"></i>
                                                    <span class="text-sm font-medium">{{ $menu->rating ?? '4.5' }}</span>
                                                </div>
                                                <div class="text-2xl font-bold text-white">
                                                    ${{ number_format($menu->price, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            <!-- Duplicate items for seamless loop -->
                            @foreach($menuItems as $menu)
                                <div class="menu-carousel-item px-4">
                                    <div class="menu-card bg-white bg-opacity-10 backdrop-blur-md rounded-2xl p-6 h-full flex flex-col shadow-2xl border border-white border-opacity-20">
                                        @if($menu->image_path)
                                            <div class="relative h-48 mb-4 rounded-xl overflow-hidden">
                                                <img src="{{ asset($menu->image_path) }}" 
                                                     alt="{{ $menu->name }}" 
                                                     class="w-full h-full object-cover">
                                                <div class="absolute top-2 right-2 bg-red-600 bg-opacity-90 text-white px-3 py-1 rounded-full text-xs font-medium">
                                                    {{ $menu->category->name }}
                                                </div>
                                                <div class="absolute top-2 left-2 {{ $menu->is_vegetarian ? 'bg-green-500' : 'bg-red-500' }} text-white px-2 py-1 rounded-full text-xs font-medium">
                                                    {{ $menu->is_vegetarian ? 'Veg' : 'Non-Veg' }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="h-48 mb-4 rounded-xl bg-white bg-opacity-10 flex items-center justify-center">
                                                <i class="fas fa-utensils text-6xl text-white opacity-50"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="flex-1 flex flex-col">
                                            <h3 class="text-xl font-bold mb-2 text-white">{{ $menu->name }}</h3>
                                            <p class="text-red-100 text-sm mb-4 line-clamp-2 flex-1">{{ $menu->description }}</p>
                                            
                                            <div class="flex items-center justify-between mt-auto">
                                                <div class="flex items-center space-x-1 text-yellow-300">
                                                    <i class="fas fa-star text-sm"></i>
                                                    <span class="text-sm font-medium">{{ $menu->rating ?? '4.5' }}</span>
                                                </div>
                                                <div class="text-2xl font-bold text-white">
                                                    ${{ number_format($menu->price, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Carousel Indicators -->
                    <div class="flex justify-center space-x-2 mt-6">
                        @foreach($menuItems as $index => $menu)
                            <div class="w-2 h-2 rounded-full bg-white {{ $index === 0 ? 'bg-opacity-100' : 'bg-opacity-40' }} transition-all duration-300" 
                                 id="indicator-{{ $index }}"></div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Side - Auth Form -->
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden mb-4" wire:navigate>
                        <span class="flex h-9 w-9 items-center justify-center rounded-md">
                            <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                        </span>
                        <span class="text-lg font-bold">{{ config('app.name', 'Oishii Restaurant') }}</span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>
        
        <script>
            // Update carousel indicators
            document.addEventListener('DOMContentLoaded', function() {
                const track = document.querySelector('.menu-carousel-track');
                const items = document.querySelectorAll('.menu-carousel-item');
                const indicators = document.querySelectorAll('[id^="indicator-"]');
                const totalItems = {{ count($menuItems) }};
                let currentIndex = 0;

                function updateIndicators() {
                    indicators.forEach((indicator, index) => {
                        if (index === currentIndex % totalItems) {
                            indicator.classList.remove('bg-opacity-40');
                            indicator.classList.add('bg-opacity-100');
                        } else {
                            indicator.classList.remove('bg-opacity-100');
                            indicator.classList.add('bg-opacity-40');
                        }
                    });
                }

                // Update indicators every 2 seconds (matching animation speed)
                setInterval(() => {
                    currentIndex = (currentIndex + 1) % totalItems;
                    updateIndicators();
                }, 2000);
            });
        </script>
        @fluxScripts
    </body>
</html>

