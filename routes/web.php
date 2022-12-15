<?php

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
    $data = array(
        "data" => "Welcome to Assignment Test - PT. Xsis MItra Utama",
        "error_message" => null,
        "status" => 200
    );

    return response()->json($data, 200);
});

require __DIR__ . '/api.php';