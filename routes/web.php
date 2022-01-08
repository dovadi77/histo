<?php

use App\Http\Controllers\AuthController;
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
        Route::get('/material', [MaterialController::class, 'index'])->name('material.index');
        Route::get('/material/add', [MaterialController::class, 'create'])->name('material.create');
        Route::post('/material/add', [MaterialController::class, 'store'])->name('material.add');
        Route::get('/material/edit/{material}', [MaterialController::class, 'edit'])->name('material.edit');
        Route::post('/material/edit/{material}', [MaterialController::class, 'update'])->name('material.update');
        Route::post('/material/delete/{material}', [MaterialController::class, 'destroy'])->name('material.delete');
    });
});

Route::name('auth.')->group(function () {
    Route::get('login', function () {
        return view('auth.login');
    })->name('login.page');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});
