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
            'fuel_type' => 'required|exists:fuel_types,code',
            'category' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Generate QR Code in Base64
        $qrCodeBase64 = QrCodeHelper::generateQrCode($request->number_plate);

        // Store vehicle in database
        $vehicle = Vehicle::create([
            'number_plate' => $request->number_plate,
            'owner_name' => $request->owner_name,
            'owner_phone' => $request->owner_phone,
            'fuel_type' => $request->fuel_type,
            'category' => $request->category,
            'qr_code' => $qrCodeBase64  // Store Base64 QR code
        ]);

        // Generate QR Code Download Link
        $qrCodeUrl = url('/api/vehicles/' . $vehicle->id . '/qr-code');

        // Prepare SMS message
        $message = "Hello {$request->owner_name}, your vehicle ({$request->number_plate}) is registered. Download your QR code: $qrCodeUrl";

        // Send SMS
        SmsHelper::sendSms($vehicle->owner_phone, $message);

        return response()->json([
            'message' => 'Vehicle registered successfully. SMS sent.',
            'vehicle' => $vehicle,
            'qr_code_url' => $qrCodeUrl
        ], 201);

    }

    public function getQrCode($id)
    {
        $vehicle = Vehicle::find($id);

        if (!$vehicle || empty($vehicle->qr_code)) {
            return response()->json(['message' => 'QR code not found'], 404);
        }

        $qrCodeBase64 = $vehicle->qr_code;
        $qrCodeData = base64_decode($qrCodeBase64);

        return response($qrCodeData)
            ->header('Content-Type', 'image/png');
    }

    // public function register(Request $request)
    // {
    //     // Validate input
    //     $validator = Validator::make($request->all(), [
    //         'number_plate' => 'required|unique:vehicles,number_plate',
    //         'owner_name' => 'required|string|max:255',
    //         'owner_phone' => 'required|string|max:20',
    //         'fuel_type' => 'required|string|max:50',
    //         'category' => 'required|string|max:50',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 400);
    //     }

    //     // Generate QR Code
    //     $qrCodePath = QrCodeHelper::generateQrCode($request->number_plate);

    //     // Store vehicle in database
    //     $vehicle = Vehicle::create([
    //         'number_plate' => $request->number_plate,
    //         'owner_name' => $request->owner_name,
    //         'owner_phone' => $request->owner_phone,
    //         'qr_code' => $qrCodePath,
    //         'fuel_type' => $request->fuel_type,
    //         'category' => $request->category
    //     ]);

    //     // Send SMS to the vehicle owner
    //     $message = "Dear {$vehicle->owner_name},Your vehicle has been successfully registered. Number Plate: {$vehicle->number_plate}";
    //     SmsHelper::sendSms($vehicle->owner_phone, $message);

    //     return response()->json([
    //         'message' => 'Vehicle registered successfully',
    //         'vehicle' => $vehicle,
    //         'qr_code_url' => asset('storage/' . $qrCodePath)
    //     ], 201);
    // }
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

    public function updateVehicle(Request $request, $number_plate)
    {
        // Find the vehicle
        $vehicle = Vehicle::where('number_plate', $number_plate)->first();

        if (!$vehicle) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }


        // Validate input
        $validator = Validator::make($request->all(), [
            'owner_name' => 'sometimes|string|max:255',
            'owner_phone' => 'sometimes|string|max:20',
            'fuel_type' => 'sometimes|string|max:50',
            'category' => 'sometimes|string|max:50',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Update vehicle details
        $vehicle->update($request->only(['owner_name', 'owner_phone', 'fuel_type', 'category']));

        $message = "Dear {$vehicle->owner_name},Your vehicle profile has been successfully updated. Number Plate: {$vehicle->number_plate}";
        SmsHelper::sendSms($vehicle->owner_phone, $message);

        return response()->json([
            'message' => 'Vehicle updated successfully',
            'vehicle' => $vehicle
        ]);
    }
    public function deleteVehicle($number_plate)
    {
        // Find the vehicle
        $vehicle = Vehicle::where('number_plate', $number_plate)->first();

        if (!$vehicle) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }

        // Delete vehicle
        $vehicle->delete();

        return response()->json(['message' => 'Vehicle deleted successfully']);
    }


}
