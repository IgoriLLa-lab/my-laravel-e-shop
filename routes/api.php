<?php

use App\Api\V1\Controllers\Auth\AuthenticateController;
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

//Route::get('/login', function () {
//    return 'login api routes';
//})->name('login');

