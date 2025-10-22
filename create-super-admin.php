<?php
/**
 * Quick Super Admin Creation Script
 * Creates a new super admin user directly in the database
 */

require_once 'auth-config.php';

// Generate secure credentials
$newUsername = 'mechanic_admin_' . date('md'); // mechanic_admin_1022
$newPassword = 'MechAdmin' . date('Y') . '!$#' . rand(100, 999); // MechAdmin2025!$#XXX
$newEmail = 'admin' . date('md') . '@mechanic-africa.com'; // admin1022@mechanic-africa.com

try {
    $pdo = getDBConnection();
    
    // Check if username already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM admin_users WHERE username = ?");
    $stmt->execute([$newUsername]);
    
    if ($stmt->fetch()['count'] > 0) {
        echo "❌ Username '$newUsername' already exists!<br>";
        echo "Existing super admin users:<br>";
        
        $stmt = $pdo->query("SELECT username, email, created_at FROM admin_users WHERE role = 'super_admin' AND is_active = 1");
        while ($user = $stmt->fetch()) {
            echo "• {$user['username']} ({$user['email']}) - Created: " . date('M j, Y', strtotime($user['created_at'])) . "<br>";
        }
        exit;
    }
    
    // Create the super admin user
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("
        INSERT INTO admin_users (username, email, password_hash, role, is_active, created_by) 
        VALUES (?, ?, ?, 'super_admin', 1, 1)
    ");
    
    $stmt->execute([$newUsername, $newEmail, $passwordHash]);
    
    echo "✅ Super Admin Created Successfully!<br><br>";
    echo "<div style='background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>🔑 New Super Admin Login Credentials:</h3>";
    echo "<strong>Username:</strong> $newUsername<br>";
    echo "<strong>Password:</strong> $newPassword<br>";
    echo "<strong>Email:</strong> $newEmail<br>";
    echo "<strong>Role:</strong> Super Admin<br>";
    echo "</div>";
    
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "⚠️ <strong>IMPORTANT:</strong><br>";
    echo "1. Save these credentials securely<br>";
    echo "2. Change the password after first login<br>";
    echo "3. Delete this file after use for security<br>";
    echo "</div>";
    
    echo "<div style='margin: 20px 0;'>";
    echo "🔗 <a href='login.php' style='background: #e74c3c; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Login Now</a>";
    echo "</div>";
    
    // Log the creation
    try {
        $stmt = $pdo->prepare("
            INSERT INTO admin_activity_log (user_id, action, details, ip_address, created_at) 
            VALUES (1, 'super_admin_created', ?, ?, datetime('now'))
        ");
        $stmt->execute([
            "Created super admin user: $newUsername",
            $_SERVER['REMOTE_ADDR'] ?? 'localhost'
        ]);
    } catch (Exception $e) {
        // Ignore logging errors
    }
    
} catch (Exception $e) {
    echo "❌ Error creating super admin: " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Created - Mechanic Africa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f8f9fa;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #e74c3c;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Mechanic Africa - Super Admin Creation</h1>