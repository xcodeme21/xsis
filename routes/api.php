<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoviesController;

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

Route::group(['prefix' => 'movies'], function () {
    Route::get('/', [MoviesController::class, 'findAll']);
    Route::post('/', [MoviesController::class, 'store'])->name('movies-create');
    Route::get('/{id}', [MoviesController::class, 'detail']);
    Route::post('/{id}', [MoviesController::class, 'update']);
    Route::delete('/{id}', [MoviesController::class, 'delete']);
});
