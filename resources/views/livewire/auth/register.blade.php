<x-layouts.auth.split_with_menu :menuItems="$menuItems" :uniqueMenuItems="$uniqueMenuItems">
    <div class="flex flex-col gap-6">
        <div class="text-center mb-2">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Create an account</h1>
            <p class="text-gray-600">Enter your details below to create your account</p>
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

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Full name
                </label>
                <input 
                    id="name"
                    name="name"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    value="{{ old('name') }}"
                    placeholder="Full name"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent transition duration-200"
                >
            </div>

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
                    autocomplete="email"
                    value="{{ old('email') }}"
                    placeholder="email@example.com"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent transition duration-200"
                >
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password
                </label>
                <div class="relative">
                    <input 
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="new-password"
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
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirm password
                </label>
                <div class="relative">
                    <input 
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-japanese-red focus:border-transparent transition duration-200 pr-12"
                    >
                    <button 
                        type="button"
                        onclick="togglePassword('password_confirmation')"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                    >
                        <i class="fas fa-eye" id="password_confirmation-eye"></i>
                    </button>
                </div>
            </div>

            <button 
                type="submit" 
                class="w-full bg-japanese-red text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition duration-300 shadow-lg mt-2"
            >
                <i class="fas fa-user-plus mr-2"></i>Create account
            </button>
        </form>

        <div class="text-center text-sm text-gray-600 pt-4 border-t border-gray-200">
            <span>Already have an account? </span>
            <a href="{{ route('login') }}" class="text-japanese-red font-semibold hover:underline">
                Log in
            </a>
        </div>
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
