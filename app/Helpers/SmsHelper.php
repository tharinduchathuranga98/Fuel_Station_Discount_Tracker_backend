<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SmsHelper
{
    public static function sendSms($phone, $message)
    {
        $client = new Client();

        // Get the API key and sender name from the .env file
        $userid = env('SMS_USER_ID');
        $apiKey = env('SMS_API_KEY');
        $senderId = env('SMS_SENDER_NAME'); // This would be your 'sender_id'
        $url = 'https://app.notify.lk/api/v1/send'; // Ensure this is the correct API URL

        try {
            // Make the request to the API
            $response = $client->post($url, [
                'form_params' => [
                    'user_id' => $userid,   // Your Notify.lk User ID
                    'api_key' => $apiKey,   // The API Key
                    'sender_id' => $senderId, // The sender ID from Notify.lk
                    'to' => $phone,        // Recipient's phone number
                    'message' => $message, // The message to send
                ],
            ]);

            // Log the response for debugging
            Log::info('Notify.lk API Response:', [
                'response' => $response->getBody()->getContents(),
            ]);

            $responseBody = json_decode($response->getBody(), true);

            // Check for success in the response
            if (isset($responseBody['status']) && $responseBody['status'] == 'success') {
                return true;
            } else {
                // Log the error if any
                Log::error('Notify.lk API Error:', [
                    'response' => $responseBody,
                ]);
                return false;
            }
        } catch (\Exception $e) {
            // Log any exception if it occurs
            Log::error('Notify.lk API Exception:', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
