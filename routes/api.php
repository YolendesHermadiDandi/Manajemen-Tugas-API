<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Register
Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');

// Login
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Logout
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');

// Get All Tugas
Route::apiResource('/tugas', App\Http\Controllers\Api\TugasController::class);
