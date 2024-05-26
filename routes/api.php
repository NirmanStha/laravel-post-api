<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => "users"], function () {
    Route::post('register', [UserController::class, "store"]);
    Route::post('login', [UserController::class, "login"])->name("login");
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('profile/{id}', [UserController::class, 'show']);
        Route::put('update/{id}', [UserController::class, 'update']);
        Route::post('logout', [UserController::class, 'logout']);
    });


});