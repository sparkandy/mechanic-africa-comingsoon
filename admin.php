<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Submissions - Mechanic Africa</title>
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
        h1 {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 2rem;
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
        <a href="index.html" class="back-link">← Back to Website</a>
        
        <h1>Contact Submissions</h1>
        
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