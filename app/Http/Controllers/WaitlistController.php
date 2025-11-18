<?php

namespace App\Http\Controllers;

use App\Models\Waitlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WaitlistController extends Controller
{
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:waitlists,email',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('waitlist_error', 'Please check the form and try again.');
        }

        try {
            Waitlist::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'city' => $request->city,
                'ip_address' => $request->ip(),
            ]);

            return back()->with('waitlist_success', 'Thank you for joining our waitlist! We\'ll be in touch soon.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('waitlist_error', 'Something went wrong. Please try again.');
        }
    }
}
