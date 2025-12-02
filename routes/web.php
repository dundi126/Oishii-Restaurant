<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AdminCustomizationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

// Menu routes (protected for admin/staff)
Route::middleware(['auth'])->group(function () {
    Route::resource('menus', MenuController::class);
    Route::get('/menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
});

// Customer Menu Routes (public)
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

// Cart Routes (public)
// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{menuItemId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{menuItemId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');

// Order Routes (public)
Route::post('/order', [OrderController::class, 'store'])->name('order.store');

// Admin customization routes
Route::prefix('admin')->group(function () {
    Route::get('/menu-items/{menuItem}/customizations', [AdminCustomizationController::class, 'index']);
    Route::post('/menu-items/{menuItem}/customizations', [AdminCustomizationController::class, 'store']);
    Route::delete('/customizations/{customization}', [AdminCustomizationController::class, 'destroy']);
});


// Checkout and Order routes
Route::middleware(['auth'])->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

    // Orders
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orderstrack', [OrderController::class, 'track'])->name('orders.track');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Update order status
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
// Category Routes (protected)
Route::middleware(['auth'])->group(function () {
    Route::resource('categories', CategoryController::class);
});

// Order routes (protected)
Route::middleware('auth')->group(function () {
    Route::resource('orders', OrderController::class)->except(['store', 'confirmation']);
    Route::resource('payments', PaymentController::class);
});

// Admin-only
Route::middleware(['auth', 'can:isAdmin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
});
