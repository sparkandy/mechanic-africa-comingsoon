<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'package' => 'required|in:4-cylinders,7-cylinders,8-cylinders',
            'car' => 'required|string|max:200',
            'message' => 'nullable|string|max:1000',
            'g-recaptcha-response' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // TODO: Verify reCAPTCHA
            // For now, we'll skip reCAPTCHA verification in development
            
            // Create contact
            $contact = Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'message' => sprintf(
                    "Package: %s\nCar: %s\nMessage: %s",
                    $request->package,
                    $request->car,
                    $request->message ?? 'N/A'
                ),
                'ip_address' => $request->ip(),
                'status' => 'unread',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your request has been submitted successfully.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Contact submission error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
}
