<?php
/**
 * Database Initialization Script
 * Creates the admin users table and sets up the database schema
 */

try {
    // Database connection
    $dbFile = 'contacts.db';
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create admin_users table
    $createAdminUsersTable = "
        CREATE TABLE IF NOT EXISTS admin_users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            role VARCHAR(20) DEFAULT 'admin',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            last_login DATETIME,
            is_active INTEGER DEFAULT 1,
            created_by INTEGER,
            FOREIGN KEY (created_by) REFERENCES admin_users(id)
        )
    ";
    
    $pdo->exec($createAdminUsersTable);
    
    // Create sessions table for better session management
    $createSessionsTable = "
        CREATE TABLE IF NOT EXISTS admin_sessions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            session_token VARCHAR(255) UNIQUE NOT NULL,
            expires_at DATETIME NOT NULL,
            ip_address VARCHAR(45),
            user_agent TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE CASCADE
        )
    ";
    
    $pdo->exec($createSessionsTable);
    
    // Create partners table
    $createPartnersTable = "
        CREATE TABLE IF NOT EXISTS partners (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            company_name VARCHAR(200) NOT NULL,
            registration_number VARCHAR(100) NOT NULL,
            phone_number VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL,
            technicians_count INTEGER,
            years_in_operation INTEGER,
            workshop_address TEXT NOT NULL,
            state_city VARCHAR(100) NOT NULL,
            services_offered TEXT NOT NULL,
            mobile_mechanic_service VARCHAR(10),
            ip_address VARCHAR(45),
            status VARCHAR(20) DEFAULT 'pending',
            notes TEXT,
            submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            reviewed_at DATETIME,
            reviewed_by INTEGER,
            FOREIGN KEY (reviewed_by) REFERENCES admin_users(id)
        )
    ";
    
    $pdo->exec($createPartnersTable);
    
    // Check if any admin users exist
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM admin_users");
    $adminCount = $stmt->fetch()['count'];
    
    // If no admin users exist, create a default admin
    if ($adminCount == 0) {
        $defaultUsername = 'admin';
        $defaultPassword = 'MechAdmin2025!'; // Change this in production
        $defaultEmail = 'admin@mechanic-africa.com';
        $passwordHash = password_hash($defaultPassword, PASSWORD_DEFAULT);
        
        $insertAdmin = "
            INSERT INTO admin_users (username, email, password_hash, role, is_active) 
            VALUES (?, ?, ?, 'super_admin', 1)
        ";
        
        $stmt = $pdo->prepare($insertAdmin);
        $stmt->execute([$defaultUsername, $defaultEmail, $passwordHash]);
        
        echo "✅ Database initialized successfully!<br>";
        echo "📊 Admin users table created<br>";
        echo "🔐 Sessions table created<br>";
        echo "🤝 Partners table created<br>";
        echo "👤 Default admin user created:<br>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;Username: <strong>$defaultUsername</strong><br>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;Password: <strong>$defaultPassword</strong><br>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;Email: <strong>$defaultEmail</strong><br>";
        echo "<br>⚠️ <strong>IMPORTANT:</strong> Change the default password after first login!<br>";
        echo "<br>🔗 <a href='login.php'>Go to Admin Login</a>";
    } else {
        echo "✅ Database already initialized with $adminCount admin user(s).<br>";
        echo "🔗 <a href='login.php'>Go to Admin Login</a>";
    }
    
} catch (PDOException $e) {
    echo "❌ Database error: " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - Mechanic Africa</title>
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
            text-align: center;
        }
        h1 {
            color: #e74c3c;
            margin-bottom: 2rem;
        }
        a {
            display: inline-block;
            background: #e74c3c;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        a:hover {
            background: #c0392b;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Mechanic Africa - Database Setup</h1>