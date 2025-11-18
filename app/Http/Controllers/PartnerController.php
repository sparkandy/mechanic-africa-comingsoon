<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PartnerController extends Controller
{
    public function submit(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:200',
            'registration_number' => 'required|string|max:100',
            'phone_number' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'technicians_count' => 'required|integer|min:0',
            'years_in_operation' => 'required|integer|min:0',
            'workshop_address' => 'required|string|max:500',
            'state_city' => 'required|string|max:100',
            'services_offered' => 'required|string|max:500',
            'mobile_mechanic_service' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Create partner application
            $partner = Partner::create([
                'company_name' => $request->company_name,
                'registration_number' => $request->registration_number,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'technicians_count' => $request->technicians_count,
                'years_in_operation' => $request->years_in_operation,
                'workshop_address' => $request->workshop_address,
                'state_city' => $request->state_city,
                'services_offered' => $request->services_offered,
                'mobile_mechanic_service' => $request->mobile_mechanic_service,
                'ip_address' => $request->ip(),
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for applying! We will review your application and contact you within 5 working days.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Partner submission error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
}
