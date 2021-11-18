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

Route::group(['prefix' => '/v1'], function () {
    Route::prefix('user')->group(function () {
        Route::post('/register', [\App\Http\Controllers\API\AuthController::class, 'register'])->name('api.user.register');
        Route::post('/login', [\App\Http\Controllers\API\AuthController::class, 'login'])->name('api.user.login');
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/all', [\App\Http\Controllers\API\UserController::class, 'index'])->name('api.user.all');
        });
    });
});

// default unauthorized
Route::get('unauthorized', function () {
    return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
})->name('api.unauthorized');
