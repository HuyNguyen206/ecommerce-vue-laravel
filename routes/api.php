<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('categories', \App\Http\Controllers\CategoryController::class);
Route::resource('products', \App\Http\Controllers\ProductController::class);

Route::prefix('auth')->group(function (){
    Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
});
