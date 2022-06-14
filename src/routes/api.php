<?php

use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EquipmentsTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('me', 'me');

    Route::apiResource('equipments_type', EquipmentsTypeController::class)->middleware('auth');
    Route::apiResource('equipments', EquipmentController::class)->middleware('auth');
});
