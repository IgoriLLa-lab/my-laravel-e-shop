<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/users', function (Request $request) {
    $user = $request->user()->makeHidden(['password', 'remember_token']);

    if ($request->user()->tokenCan('user-avatar')) {
        $user = $user->makeVisible(['password', 'remember_token']);
    }

    return $user;
})->middleware('auth:api');

//Route::get('/login', function () {
//    return 'login api routes';
//})->name('login');

