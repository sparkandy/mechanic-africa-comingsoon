<?php
require_once 'auth-config.php';

// Redirect if already logged in
if (isAuthenticated()) {
    $redirect = $_GET['redirect'] ?? 'admin.php';
    header("Location: " . $redirect);
    exit;
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        try {
            $pdo = getDBConnection();
            
            // Check for too many failed attempts
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as attempts 
                FROM admin_activity_log 
                WHERE action = 'failed_login' 
                AND ip_address = ? 
                AND created_at > datetime('now', '-15 minutes')
            ");
            $stmt->execute([$ip]);
            $failedAttempts = $stmt->fetch()['attempts'];
            
            if ($failedAttempts >= MAX_LOGIN_ATTEMPTS) {
                $error = 'Too many failed login attempts. Please try again in 15 minutes.';
            } else {
                // Check user credentials
                $stmt = $pdo->prepare("
                    SELECT id, username, email, password_hash, role 
                    FROM admin_users 
                    WHERE username = ? AND is_active = 1
                ");
                $stmt->execute([$username]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($password, $user['password_hash'])) {
                    // Prevent session fixation - regenerate session ID
                    session_regenerate_id(true);
                    
                    // Successful login
                    $_SESSION['admin_user_id'] = $user['id'];
                    $_SESSION['admin_username'] = $user['username'];
                    $_SESSION['admin_email'] = $user['email'];
                    $_SESSION['admin_role'] = $user['role'];
                    $_SESSION['login_time'] = time();
                    
                    // Update last login
                    $stmt = $pdo->prepare("UPDATE admin_users SET last_login = datetime('now') WHERE id = ?");
                    $stmt->execute([$user['id']]);
                    
                    // Log successful login
                    logAdminActivity('login', 'Successful login');
                    
                    // Handle remember me
                    if ($remember) {
                        $token = generateSecureToken();
                        $expires = date('Y-m-d H:i:s', time() + REMEMBER_ME_LIFETIME);
                        
                        // Store remember token
                        $stmt = $pdo->prepare("
                            INSERT INTO admin_sessions (user_id, session_token, expires_at, ip_address, user_agent) 
                            VALUES (?, ?, ?, ?, ?)
                        ");
                        $stmt->execute([
                            $user['id'], 
                            $token, 
                            $expires, 
                            $ip, 
                            $_SERVER['HTTP_USER_AGENT'] ?? ''
                        ]);
                        
                        // Set remember me cookie
                        setcookie('remember_token', $token, time() + REMEMBER_ME_LIFETIME, '/', '', isset($_SERVER['HTTPS']), true);
                    }
                    
                    // Redirect to intended page
                    $redirect = $_GET['redirect'] ?? 'admin.php';
                    header("Location: " . $redirect);
                    exit;
                    
                } else {
                    // Failed login
                    $error = 'Invalid username or password.';
                    
                    // Log failed attempt
                    try {
                        $stmt = $pdo->prepare("
                            INSERT INTO admin_activity_log (user_id, action, details, ip_address, user_agent) 
                            VALUES (NULL, 'failed_login', ?, ?, ?)
                        ");
                        $stmt->execute([
                            "Failed login attempt for username: $username",
                            $ip,
                            $_SERVER['HTTP_USER_AGENT'] ?? ''
                        ]);
                    } catch (Exception $e) {
                        error_log("Failed to log failed login attempt: " . $e->getMessage());
                    }
                }
            }
            
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $error = 'An error occurred during login. Please try again.';
        }
    }
}

// Handle GET parameters
if (isset($_GET['logout'])) {
    $success = 'You have been logged out successfully.';
}

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'insufficient_permissions':
            $error = 'You do not have permission to access that page.';
            break;
        case 'session_expired':
            $error = 'Your session has expired. Please log in again.';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Mechanic Africa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }
        
        .login-header {
            background: #e74c3c;
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            opacity: 0.9;
            font-size: 0.9rem;
        }
        
        .login-form {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e8ed;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #e74c3c;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
        }
        
        .checkbox-group label {
            margin-bottom: 0;
            font-weight: normal;
            cursor: pointer;
        }
        
        .btn-login {
            width: 100%;
            background: #e74c3c;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .btn-login:hover {
            background: #c0392b;
        }
        
        .btn-login:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        
        .alert-error {
            background: #fdf2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }
        
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }
        
        .login-footer {
            padding: 1.5rem 2rem;
            background: #f8f9fa;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        
        .login-footer a {
            color: #e74c3c;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .security-info {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            color: #1565c0;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
        }
        
        .password-requirements {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.5rem;
        }
        
        @media (max-width: 480px) {
            .login-container {
                margin: 0;
                border-radius: 0;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
            
            .login-form {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🔧 Admin Login</h1>
            <p>Mechanic Africa - Admin Panel</p>
        </div>
        
        <div class="login-form">
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <div class="security-info">
                🔒 This is a secure admin area. Your login activity is monitored and logged.
            </div>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-control" 
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                        required 
                        autocomplete="username"
                        placeholder="Enter your username"
                    >
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        required 
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    >
                    <div class="password-requirements">
                        Password must be at least 8 characters with uppercase, lowercase, numbers, and special characters.
                    </div>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me for 30 days</label>
                </div>
                
                <button type="submit" class="btn-login">
                    🔓 Login to Admin Panel
                </button>
            </form>
        </div>
        
        <div class="login-footer">
            <a href="index.php">← Back to Website</a>
            <br><br>
            <small>Need admin access? Contact your system administrator.</small>
        </div>
    </div>
    
    <script>
        // Auto-focus username field
        document.getElementById('username').focus();
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            if (!username || !password) {
                e.preventDefault();
                alert('Please enter both username and password.');
                return;
            }
            
            // Disable submit button to prevent double submission
            const submitBtn = document.querySelector('.btn-login');
            submitBtn.disabled = true;
            submitBtn.textContent = '🔄 Logging in...';
        });
        
        // Clear any existing alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
</body>
</html>