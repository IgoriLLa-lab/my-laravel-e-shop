<?php

use App\Api\V1\Controllers\Auth\AuthenticateController;
use App\Api\V1\Controllers\Cart\CartController;
use App\Api\V1\Controllers\Catalog\CatalogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/users', function (Request $request) {
    $user = $request->user()->makeHidden(['password', 'remember_token']);

    if ($request->user()->tokenCan('user-avatar')) {
        $user = $user->makeVisible(['password', 'remember_token']);
    }

    return $user;
})->middleware('auth:api');

Route::post('authenticate', [AuthenticateController::class, 'authenticate'])->name('api.authenticate');
Route::delete('authenticate', [AuthenticateController::class, 'logout'])->name('api.logout')->middleware('auth:jwt');
Route::put('authenticate', [AuthenticateController::class, 'refresh'])->name('api.refresh');

Route::controller(CartController::class)->as('api.cart.')->middleware('auth:jwt')->group(function () {
    Route::get('/cart', 'index')->name('index');
    Route::post('/cart{id}', 'add')->name('add');
//    Route::delete('cart', 'destroy')->name('destroy');
//    Route::put('cart{id}', 'quantity')->name('quantity');
//    Route::delete('cart{id}','delete')->name('delete');
});

Route::get('/catalog', [CatalogController::class, 'index'])->middleware('auth:jwt')->name('catalog.index');


//Route::get('/login', function () {
//    return 'login api routes';
//})->name('login');

