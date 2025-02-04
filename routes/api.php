<?php


use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::post('/register-vehicle', [VehicleController::class, 'register']);
Route::post('/get-user-refueling', [VehicleController::class, 'getUserRefuelingData']);


use App\Http\Controllers\RefuelingController;

Route::post('/refueling', [RefuelingController::class, 'recordRefueling']);

