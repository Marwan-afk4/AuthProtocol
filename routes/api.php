<?php

use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\User\ProductController as UserProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);
Route::post('/verify-email',[AuthController::class, 'verifyEmail']);


Route::middleware(['auth:sanctum','IsAdmin'])->group(function () {
    Route::post('/add-product',[ProductController::class, 'addProduct']);
});


Route::middleware(['auth:sanctum','IsUser'])->group(function () {
    Route::get('/getProducts',[UserProductController::class, 'GetProducts']);
});
