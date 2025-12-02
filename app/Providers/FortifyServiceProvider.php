<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Models\MenuItem;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        // Use closures to lazy-load menu items only when views are rendered
        // This prevents database connection errors during boot
        $getMenuItems = function () {
            try {
                // Check if database connection is available and table exists
                if (Schema::hasTable('menu_items')) {
                    $uniqueMenuItems = MenuItem::with('category')
                        ->where('is_active', true)
                        ->inRandomOrder()
                        ->limit(5)
                        ->get();

                    // If we have less than 5 items, duplicate to fill (minimum 1 item needed)
                    $menuItems = $uniqueMenuItems;
                    if ($uniqueMenuItems->count() > 0 && $uniqueMenuItems->count() < 5) {
                        $needed = 5 - $uniqueMenuItems->count();
                        $additional = collect();
                        for ($i = 0; $i < $needed; $i++) {
                            $additional->push($uniqueMenuItems->random());
                        }
                        $menuItems = $uniqueMenuItems->merge($additional);
                    }
                } else {
                    $uniqueMenuItems = collect();
                    $menuItems = collect();
                }
            } catch (\Exception $e) {
                // If database is not available, use empty collection
                $uniqueMenuItems = collect();
                $menuItems = collect();
            }

            // Ensure we have at least one item (create a placeholder if needed)
            if ($uniqueMenuItems->count() === 0) {
                $menuItems = collect([
                    (object)[
                        'id' => 0,
                        'name' => 'Welcome to Oishii',
                        'description' => 'Authentic Japanese cuisine',
                        'price' => 0,
                        'image_path' => null,
                        'is_vegetarian' => false,
                        'rating' => 5.0,
                        'category' => (object)['name' => 'Featured']
                    ]
                ]);
                $uniqueMenuItems = $menuItems;
            }

            return [
                'menuItems' => $menuItems,
                'uniqueMenuItems' => $uniqueMenuItems
            ];
        };

        Fortify::loginView(fn () => view('livewire.auth.login', $getMenuItems()));
        Fortify::verifyEmailView(fn () => view('livewire.auth.verify-email'));
        Fortify::twoFactorChallengeView(fn () => view('livewire.auth.two-factor-challenge'));
        Fortify::confirmPasswordView(fn () => view('livewire.auth.confirm-password'));
        Fortify::registerView(fn () => view('livewire.auth.register', $getMenuItems()));
        Fortify::resetPasswordView(fn () => view('livewire.auth.reset-password'));
        Fortify::requestPasswordResetLinkView(fn () => view('livewire.auth.forgot-password'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
