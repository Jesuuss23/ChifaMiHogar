<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\OrderCheckoutController;
use App\Http\Controllers\Admin\DashboardController;

use Illuminate\Support\Facades\Route;

// Rutas Públicas (Clientes)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/plato/{product:slug}', [HomeController::class, 'show'])->name('product.show');
Route::get('/checkout', [OrderCheckoutController::class, 'checkout'])->name('order.checkout');
Route::post('/checkout', [OrderCheckoutController::class, 'process'])->name('order.process');
Route::get('/pedido-confirmado/{orderCode}', [OrderCheckoutController::class, 'success'])->name('order.success');

// Redirección del dashboard de Breeze
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Perfil Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Panel de Administración
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';