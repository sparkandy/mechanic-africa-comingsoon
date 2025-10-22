<?php
require_once 'auth-config.php';

// Require authentication - viewers can access this page
requireAuth(ROLE_VIEWER);

// Log page access
logAdminActivity('admin_dashboard_access', 'Accessed admin dashboard');

// Get current user info
$currentUser = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Mechanic Africa</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .user-badge {
            background: #e74c3c;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-links {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .nav-links a {
            padding: 8px 16px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-links a:hover {
            background: #5a6268;
        }
        
        .nav-links a.primary {
            background: #28a745;
        }
        
        .nav-links a.primary:hover {
            background: #218838;
        }
        
        .nav-links a.danger {
            background: #dc3545;
        }
        
        .nav-links a.danger:hover {
            background: #c82333;
        }
        
        h1 {
            color: #e74c3c;
            margin: 0;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #e74c3c;
            text-decoration: none;
            margin-bottom: 1rem;
            padding: 8px 0;
            transition: color 0.3s;
        }
        .back-link:hover {
            color: #c0392b;
        }
        
        .welcome-message {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            color: #1565c0;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 2rem;
        }
        
        .session-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 10px;
            border-radius: 4px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            .header {
                flex-direction: column;
                align-items: stretch;
            }
            .user-info {
                justify-content: center;
            }
            .nav-links {
                justify-content: center;
            }
        }
        .stats {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .stat-card {
            background: #e74c3c;
            color: white;
            padding: 1rem;
            border-radius: 6px;
            flex: 1;
            min-width: 200px;
            text-align: center;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            display: block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        tr:hover {
            background-color: #f8f9fa;
        }
        .no-data {
            text-align: center;
            padding: 3rem;
            color: #666;
            font-style: italic;
        }
        .email {
            color: #007bff;
        }
        .date {
            font-size: 0.9rem;
            color: #666;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 1rem;
            color: #e74c3c;
            text-decoration: none;
            font-weight: 500;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            table {
                font-size: 0.9rem;
            }
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Back to Website</a>
        
        <div class="header">
            <div>
                <h1>📊 Admin Dashboard</h1>
                <div class="user-info">
                    <div class="user-badge">
                        👤 <?php echo htmlspecialchars($currentUser['username']); ?>
                        <span style="opacity: 0.8;">(<?php echo ucwords(str_replace('_', ' ', $currentUser['role'])); ?>)</span>
                    </div>
                </div>
            </div>
            
            <div class="nav-links">
                <?php if (hasRole(ROLE_ADMIN)): ?>
                    <a href="user-management.php" class="primary">👥 User Management</a>
                <?php endif; ?>
                <a href="auth.php?action=logout" class="danger">🔓 Logout</a>
            </div>
        </div>
        
        <div class="welcome-message">
            🎉 Welcome back, <strong><?php echo htmlspecialchars($currentUser['username']); ?></strong>! 
            Here's an overview of your contact form submissions.
        </div>
        
        <?php if (isset($_SESSION['remembered_login'])): ?>
            <div class="session-info">
                🔐 You were automatically logged in using a remembered session. Your session is secure and will expire in <?php echo round(SESSION_LIFETIME / 3600); ?> hours.
            </div>
            <?php unset($_SESSION['remembered_login']); ?>
        <?php endif; ?>
        
        <h2>📋 Contact Submissions</h2>
        
        <?php
        try {
            // Database connection
            $dbFile = 'contacts.db';
            $pdo = new PDO('sqlite:' . $dbFile);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Get statistics
            $totalQuery = $pdo->query("SELECT COUNT(*) as total FROM contacts");
            $total = $totalQuery->fetch()['total'];
            
            $todayQuery = $pdo->query("SELECT COUNT(*) as today FROM contacts WHERE DATE(submitted_at) = DATE('now')");
            $today = $todayQuery->fetch()['today'];
            
            $weekQuery = $pdo->query("SELECT COUNT(*) as week FROM contacts WHERE submitted_at >= datetime('now', '-7 days')");
            $week = $weekQuery->fetch()['week'];
            
            echo "<div class='stats'>";
            echo "<div class='stat-card'><span class='stat-number'>$total</span>Total Submissions</div>";
            echo "<div class='stat-card'><span class='stat-number'>$today</span>Today</div>";
            echo "<div class='stat-card'><span class='stat-number'>$week</span>This Week</div>";
            echo "</div>";
            
            // Get all submissions
            $stmt = $pdo->query("SELECT * FROM contacts ORDER BY submitted_at DESC");
            $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($contacts) > 0) {
                echo "<table>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Name</th>";
                echo "<th>Email</th>";
                echo "<th>Car Information</th>";
                echo "<th>Submitted</th>";
                echo "<th>IP Address</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                
                foreach ($contacts as $contact) {
                    $submittedDate = date('M j, Y g:i A', strtotime($contact['submitted_at']));
                    echo "<tr>";
                    echo "<td><strong>" . htmlspecialchars($contact['name']) . "</strong></td>";
                    echo "<td class='email'>" . htmlspecialchars($contact['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($contact['car_information']) . "</td>";
                    echo "<td class='date'>" . $submittedDate . "</td>";
                    echo "<td>" . htmlspecialchars($contact['ip_address']) . "</td>";
                    echo "</tr>";
                }
                
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<div class='no-data'>No submissions yet. When customers submit the contact form, their information will appear here.</div>";
            }
            
        } catch (PDOException $e) {
            echo "<div class='no-data'>Database error: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
        ?>
    </div>
</body>
</html>