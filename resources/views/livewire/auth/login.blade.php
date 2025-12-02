<x-layouts.auth.split_with_menu :menuItems="$menuItems" :uniqueMenuItems="$uniqueMenuItems">
    <div class="flex flex-col gap-6">
        <div class="text-center mb-2">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Log in to your account</h1>
            <p class="text-gray-600">Enter your email and password below to log in</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                <p class="text-green-700 text-sm">{{ session('status') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                <ul class="list-disc list-inside text-red-700 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email address
                </label>
                <input 
                    id="email"
                    name="email"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    value="{{ old('email') }}"
                    placeholder="email@example.com"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent transition duration-200"
                >
            </div>

            <!-- Password -->
            <div class="relative">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password
                </label>
                <div class="relative">
                    <input 
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="Password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent transition duration-200 pr-12"
                    >
                    <button 
                        type="button"
                        onclick="togglePassword('password')"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                    >
                        <i class="fas fa-eye" id="password-eye"></i>
                    </button>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-japanese-red hover:underline mt-1 block text-right">
                        Forgot your password?
                    </a>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input 
                    id="remember"
                    name="remember"
                    type="checkbox"
                    class="h-4 w-4 text-japanese-red focus:ring-japanese-red border-gray-300 rounded"
                    {{ old('remember') ? 'checked' : '' }}
                >
                <label for="remember" class="ml-2 block text-sm text-gray-700">
                    Remember me
                </label>
            </div>

            <button 
                type="submit" 
                class="w-full bg-japanese-red text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition duration-300 shadow-lg mt-2"
            >
                <i class="fas fa-sign-in-alt mr-2"></i>Log in
            </button>
        </form>

        @if (Route::has('register'))
            <div class="text-center text-sm text-gray-600 pt-4 border-t border-gray-200">
                <span>Don't have an account? </span>
                <a href="{{ route('register') }}" class="text-japanese-red font-semibold hover:underline">
                    Sign up
                </a>
            </div>
        @endif
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(inputId + '-eye');
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }
    </script>
</x-layouts.auth.split_with_menu>
