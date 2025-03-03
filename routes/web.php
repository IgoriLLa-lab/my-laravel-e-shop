<?php

use App\Http\Controllers\Basket\BasketController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\BasketIsNotEmpty;
use Illuminate\Support\Facades\Route;

Route::get('locale/{locale}', [MainController::class, 'changeLocale'])->name('locale');
Route::get('/categories', [MainController::class, 'categories'])->name('categories');

Route::middleware(['set_locale'])->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    });

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [OrderController::class, 'index'])->name('dashboard');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    });
    Route::middleware([BasketIsNotEmpty::class])
        ->prefix('basket')
        ->group(function () {
            Route::get('/place', [BasketController::class, 'basketPlace'])->middleware(['auth'])->name('basket-place');
            Route::post('/place', [BasketController::class, 'basketConfirm'])->name('basket-confirm');
            Route::post('/add/{product}', [BasketController::class, 'addToBasket'])->name('basket-add');
            Route::post('/remove/{product}', [BasketController::class, 'removeFromBasket'])->name('basket-remove');
            Route::get('/', [BasketController::class, 'basket'])->name('basket');
        });

    Route::get('/', [MainController::class, 'index'])->name('index');
    Route::post('/subscription/{product}', [MainController::class, 'subscribe'])->name('subscribe');

    Route::get('/{category}', [MainController::class, 'category'])->name('category');
    Route::get('/{category}/{productName}', [MainController::class, 'product'])->name('product');
});



//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';


