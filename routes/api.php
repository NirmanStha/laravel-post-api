<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Middleware;
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
        Route::post('update/{id}', [UserController::class, 'update']);
        Route::post('logout', [UserController::class, 'logout']);
    });


});
 Route::group(["prefix" => "posts"], function () {
     Route::get('/',[PostController::class, 'index']);

     Route::group(["middleware" => "auth:sanctum"], function () {
        Route::get("myposts", [PostController::class, "getMyPost"]);
         Route::post("create", [PostController::class, "store"]);
         Route::get("show/{id}", [PostController::class, "show"]);
         Route::put("update/{post}", [PostController::class, "update"]);
         Route::post("delete/{post}" , [PostController::class, "destroy"]);

    });


 });
 Route::group(["middleware" => "auth:sanctum"], function () {
    Route::apiResource("posts.comment", CommentController::class);
 });



// Route::apiResource("posts", PostController::class)->middleware("auth:sanctum");


