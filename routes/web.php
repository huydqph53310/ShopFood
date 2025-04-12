<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;

Route::get('/',  [HomeController::class, 'index'])->name('home');
Route::get('/products', [HomeController::class, 'products'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.detail');

Route::resource('/cart', CartController::class);
Route::post('/cart/{cart}/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
Route::post('/checkout/notify', [CheckoutController::class, 'notify'])->name('checkout.notify');


require __DIR__.'/auth.php';
