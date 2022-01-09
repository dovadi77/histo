<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MaterialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('frontpage');

Route::name('dash.')->group(function () {
    Route::group(['middleware' => ["auth"], "prefix" => "historian"], function () {
        Route::get('/', [AuthController::class, 'index'])->name('dashboard');
        Route::group(["prefix" => "material", 'as' => 'material.'], function () {
            Route::get('', [MaterialController::class, 'index'])->name('index');
            Route::get('/add', [MaterialController::class, 'create'])->name('create');
            Route::post('/add', [MaterialController::class, 'store'])->name('add');
            Route::get('/edit/{material}', [MaterialController::class, 'edit'])->name('edit');
            Route::post('/edit/{material}', [MaterialController::class, 'update'])->name('update');
            Route::post('/delete/{material}', [MaterialController::class, 'destroy'])->name('delete');
        });
        Route::group(["prefix" => "game", 'as' => 'game.'], function () {
            Route::get('', [GameController::class, 'index'])->name('index');
            Route::get('/add', [GameController::class, 'create'])->name('create');
            Route::post('/add', [GameController::class, 'store'])->name('add');
            Route::get('/edit/{game}', [GameController::class, 'edit'])->name('edit');
            Route::post('/edit/{game}', [GameController::class, 'update'])->name('update');
            Route::post('/delete/{game}', [GameController::class, 'destroy'])->name('delete');
        });
    });
});

Route::name('auth.')->group(function () {
    Route::get('login', function () {
        return view('auth.login');
    })->name('login.page');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});
