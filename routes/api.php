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
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::resources([
    'categories' => \App\Http\Controllers\CategoryController::class,
    'products' => \App\Http\Controllers\ProductController::class,
    'addresses' => \App\Http\Controllers\AddressController::class,
    'countries' => \App\Http\Controllers\CountryController::class
]);
Route::prefix('auth')->group(function (){
    Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
    Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::get('me', [\App\Http\Controllers\Auth\MeController::class, 'me']);
    Route::get('refresh', [\App\Http\Controllers\Auth\MeController::class, 'refresh']);
});

Route::resource('carts', \App\Http\Controllers\CartController::class)->parameters([
    'carts' => 'productVariation'
]);

Route::middleware('auth')->get('token/invalidate-token', function (){
    auth()->invalidate(true);
});
