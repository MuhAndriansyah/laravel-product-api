<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth Route
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

//Public Routes
Route::get('products', [ProductController::class, 'index'])->name('products.index');
Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');



Route::middleware('auth:sanctum')->group(function () {
    Route::post('products', [ProductController::class, 'store'])->name('products.store');

    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');

    Route::delete('products/{product}',  [ProductController::class, 'destroy'])->name('products.destroy');

    Route::post('logout', [AuthController::class, 'logout']);

});
