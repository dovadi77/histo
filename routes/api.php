<?php

use App\Http\Controllers\API\AchievementController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GameController;
use App\Http\Controllers\API\MaterialController;
use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\UserController;
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

Route::group(['prefix' => '/v1', 'as' => 'api.'], function () {
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/me', [UserController::class, 'index'])->name('detail');
        });
    });
    Route::group(['prefix' => 'achievement', 'as' => 'achievement.'], function () {
        Route::get('/{id}', [AchievementController::class, 'show'])->name('detail');
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::post('', [UserController::class, 'storeAchievement'])->name('store');
        });
    });
    Route::group(['prefix' => 'material', 'as' => 'material.'], function () {
        Route::get('', [MaterialController::class, 'index'])->name('index');
        Route::get('/{id}', [MaterialController::class, 'show'])->name('detail');
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/{material}/quiz', [QuizController::class, 'index'])->name('quiz.index');
            Route::post('/{material}/quiz', [QuizController::class, 'store'])->name('quiz.answer');
            Route::put('/{material}/quiz', [QuizController::class, 'update'])->name('quiz.answer.update');
        });
    });

    Route::group(['prefix' => 'game', 'as' => 'game.'], function () {
        Route::get('/', [GameController::class, 'index'])->name('index');
        Route::get('/{game}/leaderboard', [GameController::class, 'leaderboard'])->name('leaderboard');
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/{game}', [GameController::class, 'show'])->name('detail');
            Route::post('/{game}', [GameController::class, 'store'])->name('answer');
        });
    });

    // default unauthorized
    Route::get('unauthorized', function () {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    })->name('unauthorized');
});
