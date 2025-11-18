<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Mechanic Africa</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f3f4f6; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        
        .login-container { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); max-width: 420px; width: 100%; padding: 48px 40px; border: 1px solid #e5e7eb; }
        .login-header { text-align: center; margin-bottom: 32px; }
        .login-title { font-size: 28px; font-weight: 800; color: #111827; margin-bottom: 8px; }
        .login-subtitle { font-size: 14px; color: #6B7280; }
        
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-weight: 500; color: #374151; margin-bottom: 8px; font-size: 14px; }
        .form-input { width: 100%; padding: 12px 16px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; font-family: 'Inter', sans-serif; transition: all 0.2s; }
        .form-input:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
        
        .login-btn { width: 100%; padding: 14px; background: #667eea; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .login-btn:hover { background: #5568d3; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); }
        
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .alert-error { background: #FEE2E2; color: #DC2626; border: 1px solid #FECACA; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-title">MECHANIC AFRICA</div>
            <div class="login-subtitle">Admin Panel Login</div>
        </div>

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="admin@mechanicafrica.com" required>
                @error('email')
                    <div style="color: #DC2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="Enter your password" required>
                @error('password')
                    <div style="color: #DC2626; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 12px; color: #6B7280;">
            © 2024 Mechanic Africa. All rights reserved.
        </div>
    </div>
</body>
</html>
