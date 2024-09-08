<?php

use App\Http\Controllers\API\CustomerAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/customers/register', [CustomerAuthController::class, 'register']);
Route::post('/customers/login', [CustomerAuthController::class, 'login']);
Route::post('/customers/logout', [CustomerAuthController::class, 'logout'])->middleware('auth:sanctum');
