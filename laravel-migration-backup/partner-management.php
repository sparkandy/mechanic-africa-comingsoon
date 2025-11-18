<?php
/**
 * Partner Management Admin Panel
 * View and manage partner applications
 */

require_once 'auth-config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Get user info
$userInfo = getCurrentUser();
$canManage = in_array($userInfo['role'], ['super_admin', 'admin']);

// Database connection
try {
    $dbFile = __DIR__ . '/contacts.db';
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed');
}

$message = '';
$error = '';

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $canManage) {
    // CSRF validation
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        $error = 'Invalid security token';
    } else {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'update_status') {
            $partnerId = filter_var($_POST['partner_id'], FILTER_VALIDATE_INT);
            $status = $_POST['status'] ?? '';
            $notes = trim($_POST['notes'] ?? '');
            
            $allowedStatuses = ['pending', 'approved', 'rejected', 'contacted'];
            
            if ($partnerId && in_array($status, $allowedStatuses)) {
                try {
                    $stmt = $pdo->prepare("
                        UPDATE partners 
                        SET status = ?, 
                            notes = ?, 
                            reviewed_at = CURRENT_TIMESTAMP,
                            reviewed_by = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$status, $notes, $userInfo['id'], $partnerId]);
                    $message = 'Partner status updated successfully';
                } catch (PDOException $e) {
                    $error = 'Failed to update status';
                }
            }
        }
        
        if ($action === 'delete_partner') {
            $partnerId = filter_var($_POST['partner_id'], FILTER_VALIDATE_INT);
            
            if ($partnerId) {
                try {
                    $stmt = $pdo->prepare("DELETE FROM partners WHERE id = ?");
                    $stmt->execute([$partnerId]);
                    $message = 'Partner application deleted successfully';
                } catch (PDOException $e) {
                    $error = 'Failed to delete partner application';
                }
            }
        }
    }
}

// Generate CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Get filter
$filterStatus = $_GET['status'] ?? 'all';
$searchQuery = $_GET['search'] ?? '';

// Fetch partners
try {
    $sql = "SELECT p.*, u.username as reviewed_by_name 
            FROM partners p 
            LEFT JOIN admin_users u ON p.reviewed_by = u.id 
            WHERE 1=1";
    $params = [];
    
    if ($filterStatus !== 'all') {
        $sql .= " AND p.status = ?";
        $params[] = $filterStatus;
    }
    
    if (!empty($searchQuery)) {
        $sql .= " AND (p.company_name LIKE ? OR p.email LIKE ? OR p.phone_number LIKE ?)";
        $searchTerm = "%$searchQuery%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    $sql .= " ORDER BY p.submitted_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $partners = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get statistics
    $stats = [
        'total' => 0,
        'pending' => 0,
        'approved' => 0,
        'rejected' => 0,
        'contacted' => 0
    ];
    
    $statsStmt = $pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
            SUM(CASE WHEN status = 'contacted' THEN 1 ELSE 0 END) as contacted
        FROM partners
    ");
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $partners = [];
    $error = 'Failed to fetch partner applications';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Applications - Mechanic Africa Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f5f5f5;
            color: #333;
        }
        .header {
            background: #e74c3c;
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header h1 {
            font-size: 1.5rem;
        }
        .nav-links {
            display: flex;
            gap: 1rem;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background 0.2s;
        }
        .nav-links a:hover {
            background: rgba(255,255,255,0.2);
        }
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .stat-card .number {
            font-size: 2rem;
            font-weight: 700;
            color: #e74c3c;
        }
        .filters {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .filter-row {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .filter-group {
            display: flex;
            gap: 0.5rem;
        }
        .filter-group a {
            padding: 0.5rem 1rem;
            border: 2px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #666;
            transition: all 0.2s;
        }
        .filter-group a.active {
            background: #e74c3c;
            color: white;
            border-color: #e74c3c;
        }
        .filter-group a:hover {
            border-color: #e74c3c;
        }
        .search-box {
            flex: 1;
            min-width: 250px;
        }
        .search-box input {
            width: 100%;
            padding: 0.5rem 1rem;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 0.95rem;
        }
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .partners-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #f8f9fa;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #dee2e6;
        }
        td {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        .status-contacted {
            background: #d1ecf1;
            color: #0c5460;
        }
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
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
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .btn-small {
            padding: 0.35rem 0.75rem;
            font-size: 0.85rem;
        }
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 2rem;
            border-radius: 8px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .modal-header h3 {
            color: #333;
        }
        .close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #999;
        }
        .close:hover {
            color: #333;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 0.95rem;
        }
        .form-control:focus {
            outline: none;
            border-color: #e74c3c;
        }
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        .detail-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 1rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #666;
        }
        .detail-value {
            color: #333;
        }
        .no-data {
            text-align: center;
            padding: 3rem;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🤝 Partner Applications</h1>
        <div class="nav-links">
            <a href="admin.php">📊 Dashboard</a>
            <a href="technician-management.php">👨‍🔧 Technicians</a>
            <a href="user-management.php">👥 Users</a>
            <a href="logout.php">🚪 Logout</a>
        </div>
    </div>

    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card">
                <h3>Total Applications</h3>
                <div class="number"><?php echo $stats['total']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Pending Review</h3>
                <div class="number"><?php echo $stats['pending']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Approved</h3>
                <div class="number"><?php echo $stats['approved']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Contacted</h3>
                <div class="number"><?php echo $stats['contacted']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Rejected</h3>
                <div class="number"><?php echo $stats['rejected']; ?></div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters">
            <div class="filter-row">
                <div class="filter-group">
                    <a href="?status=all" class="<?php echo $filterStatus === 'all' ? 'active' : ''; ?>">All</a>
                    <a href="?status=pending" class="<?php echo $filterStatus === 'pending' ? 'active' : ''; ?>">Pending</a>
                    <a href="?status=contacted" class="<?php echo $filterStatus === 'contacted' ? 'active' : ''; ?>">Contacted</a>
                    <a href="?status=approved" class="<?php echo $filterStatus === 'approved' ? 'active' : ''; ?>">Approved</a>
                    <a href="?status=rejected" class="<?php echo $filterStatus === 'rejected' ? 'active' : ''; ?>">Rejected</a>
                </div>
                <div class="search-box">
                    <form method="GET">
                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($filterStatus); ?>">
                        <input type="text" name="search" placeholder="Search by company, email, or phone..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                    </form>
                </div>
            </div>
        </div>

        <!-- Partners Table -->
        <div class="partners-table">
            <?php if (count($partners) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>Contact</th>
                            <th>Location</th>
                            <th>Technicians</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($partners as $partner): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($partner['company_name']); ?></strong><br>
                                    <small>Reg: <?php echo htmlspecialchars($partner['registration_number']); ?></small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($partner['email']); ?><br>
                                    <small><?php echo htmlspecialchars($partner['phone_number']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($partner['state_city']); ?></td>
                                <td><?php echo htmlspecialchars($partner['technicians_count']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo htmlspecialchars($partner['status']); ?>">
                                        <?php echo ucfirst(htmlspecialchars($partner['status'])); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($partner['submitted_at'])); ?></td>
                                <td>
                                    <div class="actions">
                                        <button onclick="viewPartner(<?php echo $partner['id']; ?>)" class="btn btn-primary btn-small">View</button>
                                        <?php if ($canManage): ?>
                                            <button onclick="updateStatus(<?php echo $partner['id']; ?>)" class="btn btn-secondary btn-small">Update</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p>No partner applications found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- View Partner Modal -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Partner Application Details</h3>
                <button class="close" onclick="closeModal('viewModal')">&times;</button>
            </div>
            <div id="partnerDetails"></div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Update Partner Status</h3>
                <button class="close" onclick="closeModal('statusModal')">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" id="status_partner_id" name="partner_id">
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="pending">Pending</option>
                        <option value="contacted">Contacted</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" class="form-control" placeholder="Add any notes about this partner..."></textarea>
                </div>
                
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Update Status</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('statusModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const partners = <?php echo json_encode($partners); ?>;

        function viewPartner(id) {
            const partner = partners.find(p => p.id === id);
            if (!partner) return;

            const html = `
                <div class="detail-row">
                    <div class="detail-label">Company Name:</div>
                    <div class="detail-value">${escapeHtml(partner.company_name)}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Registration No:</div>
                    <div class="detail-value">${escapeHtml(partner.registration_number)}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email:</div>
                    <div class="detail-value"><a href="mailto:${escapeHtml(partner.email)}">${escapeHtml(partner.email)}</a></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Phone:</div>
                    <div class="detail-value"><a href="tel:${escapeHtml(partner.phone_number)}">${escapeHtml(partner.phone_number)}</a></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Workshop Address:</div>
                    <div class="detail-value">${escapeHtml(partner.workshop_address)}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">State/City:</div>
                    <div class="detail-value">${escapeHtml(partner.state_city)}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Technicians:</div>
                    <div class="detail-value">${escapeHtml(partner.technicians_count)}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Years in Operation:</div>
                    <div class="detail-value">${escapeHtml(partner.years_in_operation)}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Services Offered:</div>
                    <div class="detail-value">${escapeHtml(partner.services_offered)}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Mobile Service:</div>
                    <div class="detail-value">${escapeHtml(partner.mobile_mechanic_service)}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status:</div>
                    <div class="detail-value"><span class="status-badge status-${escapeHtml(partner.status)}">${escapeHtml(partner.status)}</span></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Submitted:</div>
                    <div class="detail-value">${new Date(partner.submitted_at).toLocaleString()}</div>
                </div>
                ${partner.notes ? `
                <div class="detail-row">
                    <div class="detail-label">Notes:</div>
                    <div class="detail-value">${escapeHtml(partner.notes)}</div>
                </div>
                ` : ''}
                ${partner.reviewed_by_name ? `
                <div class="detail-row">
                    <div class="detail-label">Reviewed By:</div>
                    <div class="detail-value">${escapeHtml(partner.reviewed_by_name)}</div>
                </div>
                ` : ''}
            `;

            document.getElementById('partnerDetails').innerHTML = html;
            document.getElementById('viewModal').style.display = 'block';
        }

        function updateStatus(id) {
            const partner = partners.find(p => p.id === id);
            if (!partner) return;

            document.getElementById('status_partner_id').value = id;
            document.getElementById('status').value = partner.status;
            document.getElementById('notes').value = partner.notes || '';
            document.getElementById('statusModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Close modal on outside click
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>
