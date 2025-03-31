<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/',  [HomeController::class, 'index'])->name('home');
Route::get('/products', [HomeController::class, 'products'])->name('products.index');
Route::get('/products/{product}', [HomeController::class, 'productDetail'])->name('products.detail');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{cart}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cart}/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/{cart}/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

