<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

// API V1
Route::prefix('v1')->name('v1')->group(function () {

    // Register and Login routes
    Route::controller(App\Http\Api\V1\AuthController::class)->group(function(){
        Route::post('register', 'register');
        Route::post('login', 'login');
    });

    // Profile route
    Route::get('profile/{id}', [App\Http\Api\V1\ProfileController::class, 'show']);

    // Reviews routes
    Route::apiResource('reviews', App\Http\Api\V1\ReviewController::class);

    // Search route
    Route::get('search', [App\Http\Api\V1\SearchController::class, 'search']);

    /* Auth routes */
    Route::middleware('auth:sanctum')->group(function () {
        // Favourites routes
        Route::apiResource('favourites', App\Http\Api\V1\FavouriteController::class);
    });
});