<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\RefuelingRecord;
use Illuminate\Http\Request;
use App\Helpers\QrCodeHelper;
use Illuminate\Support\Facades\Validator;
use App\Helpers\SmsHelper;
use Carbon\Carbon;

class VehicleController extends Controller
{
    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'number_plate' => 'required|unique:vehicles,number_plate',
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Generate QR Code
        $qrCodePath = QrCodeHelper::generateQrCode($request->number_plate);

        // Store vehicle in database
        $vehicle = Vehicle::create([
            'number_plate' => $request->number_plate,
            'owner_name' => $request->owner_name,
            'owner_phone' => $request->owner_phone,
            'qr_code' => $qrCodePath
        ]);

        // Send SMS to the vehicle owner
        $message = "Your vehicle has been successfully registered. Number Plate: {$vehicle->number_plate}";
        SmsHelper::sendSms($vehicle->owner_phone, $message);

        return response()->json([
            'message' => 'Vehicle registered successfully',
            'vehicle' => $vehicle,
            'qr_code_url' => asset('storage/' . $qrCodePath)
        ], 201);
    }
    public function getUserRefuelingData(Request $request)
    {
        // Validate the number plate from the request
        $request->validate([
            'number_plate' => 'required|string',
        ]);

        // Get the vehicle based on number plate
        $vehicle = Vehicle::where('number_plate', $request->number_plate)->first();

        if (!$vehicle) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }

        // Get the total refueling for the current month
        $totalRefueled = RefuelingRecord::where('number_plate', $request->number_plate)
            ->whereMonth('refueled_at', Carbon::now()->month)
            ->sum('liters');

        // Prepare the data to return
        $data = [
            'vehicle' => $vehicle,
            'total_refueling_for_month' => $totalRefueled
        ];

        return response()->json($data, 200);
    }

}
