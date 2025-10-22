<?php
/**
 * Add Super Admin User Script
 * Creates a new super admin user in the database
 */

require_once 'auth-config.php';

$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'All fields are required.';
    } else {
        try {
            $pdo = getDBConnection();
            
            // Check if username or email already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM admin_users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()['count'] > 0) {
                $error = 'Username or email already exists.';
            } else {
                // Validate password
                $passwordErrors = validatePassword($password);
                if (!empty($passwordErrors)) {
                    $error = implode('<br>', $passwordErrors);
                } else {
                    // Create super admin user
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("
                        INSERT INTO admin_users (username, email, password_hash, role, is_active, created_by) 
                        VALUES (?, ?, ?, ?, 1, NULL)
                    ");
                    $stmt->execute([$username, $email, $passwordHash, ROLE_SUPER_ADMIN]);
                    
                    $message = "✅ Super Admin user '$username' created successfully!";
                }
            }
            
        } catch (Exception $e) {
            error_log("Error creating super admin: " . $e->getMessage());
            $error = 'Error creating user. Please try again.';
        }
    }
}

// Generate secure random password
function generateSecurePassword($length = 12) {
    $chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%&*';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $password;
}

// Auto-generate credentials for quick setup
$suggestedUsername = 'superadmin';
$suggestedEmail = 'superadmin@mechanic-africa.com';
$suggestedPassword = generateSecurePassword(12);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Super Admin - Mechanic Africa</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 2rem;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .alert-error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }
        .form-control {
            width: 100%;
            padding: 12px;
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
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .btn-primary {
            background: #e74c3c;
            color: white;
        }
        .btn-primary:hover {
            background: #c0392b;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .suggested-credentials {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            color: #1565c0;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 2rem;
        }
        .suggested-credentials h3 {
            margin-top: 0;
            color: #1565c0;
        }
        .credential-item {
            margin: 10px 0;
            padding: 8px;
            background: white;
            border-radius: 4px;
            font-family: monospace;
        }
        .copy-btn {
            background: none;
            border: 1px solid #1565c0;
            color: #1565c0;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            margin-left: 10px;
        }
        .copy-btn:hover {
            background: #1565c0;
            color: white;
        }
        .password-requirements {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.5rem;
        }
        .actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>👤 Add Super Admin User</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <div class="suggested-credentials">
            <h3>🎯 Suggested Credentials (Auto-Generated)</h3>
            <p>Click "Use Suggested" to fill the form with these secure credentials:</p>
            
            <div class="credential-item">
                <strong>Username:</strong> <span id="suggested-username"><?php echo $suggestedUsername; ?></span>
                <button class="copy-btn" onclick="copyToClipboard('suggested-username')">Copy</button>
            </div>
            
            <div class="credential-item">
                <strong>Email:</strong> <span id="suggested-email"><?php echo $suggestedEmail; ?></span>
                <button class="copy-btn" onclick="copyToClipboard('suggested-email')">Copy</button>
            </div>
            
            <div class="credential-item">
                <strong>Password:</strong> <span id="suggested-password"><?php echo $suggestedPassword; ?></span>
                <button class="copy-btn" onclick="copyToClipboard('suggested-password')">Copy</button>
            </div>
            
            <button type="button" class="btn btn-secondary" onclick="useSuggested()" style="margin-top: 10px;">
                📋 Use Suggested Credentials
            </button>
        </div>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <div class="password-requirements">
                    Password must be at least 8 characters with uppercase, lowercase, numbers, and special characters.
                </div>
            </div>
            
            <div class="actions">
                <button type="submit" class="btn btn-primary">
                    ➕ Create Super Admin
                </button>
                <a href="login.php" class="btn btn-secondary">
                    🔓 Go to Login
                </a>
                <a href="admin.php" class="btn btn-secondary">
                    📊 Admin Dashboard
                </a>
            </div>
        </form>
    </div>
    
    <script>
        function useSuggested() {
            document.getElementById('username').value = '<?php echo $suggestedUsername; ?>';
            document.getElementById('email').value = '<?php echo $suggestedEmail; ?>';
            document.getElementById('password').value = '<?php echo $suggestedPassword; ?>';
        }
        
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent;
            navigator.clipboard.writeText(text).then(function() {
                const btn = element.nextElementSibling;
                const originalText = btn.textContent;
                btn.textContent = 'Copied!';
                btn.style.background = '#28a745';
                btn.style.color = 'white';
                setTimeout(function() {
                    btn.textContent = originalText;
                    btn.style.background = '';
                    btn.style.color = '';
                }, 2000);
            });
        }
        
        // Auto-focus username field
        document.getElementById('username').focus();
    </script>
</body>
</html>