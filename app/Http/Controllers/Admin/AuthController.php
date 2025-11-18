<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Redirect to dashboard if already logged in
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $admin = AdminUser::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            // Set session
            session([
                'admin_logged_in' => true,
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'admin_email' => $admin->email
            ]);

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials provided.'
        ])->withInput();
    }

    public function logout()
    {
        session()->forget(['admin_logged_in', 'admin_id', 'admin_name', 'admin_email']);
        return redirect()->route('admin.login');
    }
}
