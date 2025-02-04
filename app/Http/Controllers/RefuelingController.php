<?php

namespace App\Http\Controllers;

use App\Models\RefuelingRecord;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Helpers\SmsHelper;

class RefuelingController extends Controller
{
    public function recordRefueling(Request $request)
{
    // Validate the input
    $validated = $request->validate([
        'number_plate' => 'required|string',
        'liters' => 'required|numeric|min:1',
    ]);

    // Find the vehicle by number plate
    $vehicle = Vehicle::where('number_plate', $validated['number_plate'])->first();

    if (!$vehicle) {
        return response()->json(['error' => 'Vehicle not found'], 404);
    }

    // Create the refueling record
    $refuelingRecord = RefuelingRecord::create([
        'number_plate' => $validated['number_plate'],
        'liters' => $validated['liters'],
        'refueled_at' => now(),
    ]);

    // // Send SMS to the vehicle owner
    // $message = "Your vehicle has been refueled with {$validated['liters']} liters. Number Plate: {$vehicle->number_plate}";
    // SmsHelper::sendSms($vehicle->owner_phone, $message);

    return response()->json([
        'message' => 'Refueling record added successfully',
        'data' => $refuelingRecord,
    ], 201);
}
}
