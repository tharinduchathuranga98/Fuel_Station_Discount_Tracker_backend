<?php


use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::post('/register-vehicle', [VehicleController::class, 'register']);
Route::put('/vehicles/{number_plate}', [VehicleController::class, 'updateVehicle']);
Route::delete('/vehicles/{number_plate}', [VehicleController::class, 'deleteVehicle']);

Route::post('/get-user-refueling', [VehicleController::class, 'getUserRefuelingData']);

Route::get('/vehicles/{id}/qr-code', [VehicleController::class, 'getQrCode']);

use App\Http\Controllers\RefuelingController;

Route::post('/refueling', [RefuelingController::class, 'recordRefueling']);

use App\Http\Controllers\FuelTypeController;

Route::prefix('fuel-types')->group(function () {
    Route::post('/', [FuelTypeController::class, 'store']); // Create
    Route::get('/', [FuelTypeController::class, 'index']); // Get all
    Route::get('/{id}', [FuelTypeController::class, 'show']); // Get one
    Route::put('/{id}', [FuelTypeController::class, 'update']); // Update
    Route::delete('/{id}', [FuelTypeController::class, 'destroy']); // Delete
});
