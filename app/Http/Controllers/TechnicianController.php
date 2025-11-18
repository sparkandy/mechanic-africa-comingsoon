<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TechnicianController extends Controller
{
    public function submit(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:200',
            'phone_number' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'state_city' => 'required|string|max:100',
            'area_of_specialization' => 'required|string|max:200',
            'years_in_operation' => 'required|integer|min:0',
            'work_type' => 'required|string|max:100',
            'certification_training' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Create technician application
            $technician = Technician::create([
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'state_city' => $request->state_city,
                'area_of_specialization' => $request->area_of_specialization,
                'years_in_operation' => $request->years_in_operation,
                'work_type' => $request->work_type,
                'certification_training' => $request->certification_training,
                'ip_address' => $request->ip(),
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for applying! We will review your application and contact you within 5 working days.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Technician submission error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
}
