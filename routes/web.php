<?php

use App\Models\Fruit;
use App\Models\Category;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Auth\LoginController;

// Landing Page
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('user.login');
    }

    $query = Fruit::query();

    if (request('search')) {
        $query->where('name', 'like', '%' . request('search') . '%');
    }

    if (request('category')) {
        $query->where('category_id', request('category'));
    }

    $cartItems = [];

     if (Auth::check()) {
        $cartItems = Cart::where('user_id', Auth::id())->get()->keyBy('fruit_id');
    }

    return view('landingpage', [
        'fruits' => $query->get(),
        'categories' => Category::all(),
        'cartItems' => $cartItems,
    ]);
});

// Custom Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('user.login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('user.logout');
Route::get('/transaction/print/{id}', [TransactionController::class, 'print'])->name('transaction.print');

// Authenticated User Route
Route::middleware('auth')->group(function () {
    Route::get('/profile', fn () => view('profile'));

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');
    Route::post('/payment/finish', [CheckoutController::class, 'finish'])->name('checkout.finish');

    Route::get('/transaction/create/{fruit_id}', [TransactionController::class, 'create'])->name('transaction.create');
    Route::post('/transaction/store', [TransactionController::class, 'store'])->name('transaction.store');
    Route::get('/transaction/history', [TransactionController::class, 'history'])->name('transaction.history');
});
Route::get('/transaction/print/{id}', [TransactionController::class, 'print'])->name('transaction.print');