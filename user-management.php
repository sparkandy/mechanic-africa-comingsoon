<?php
require_once 'auth-config.php';

// Require admin or super admin access
requireAuth(ROLE_ADMIN);

$message = '';
$error = '';

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Invalid security token. Please refresh the page and try again.';
    } else {
        $action = $_POST['action'] ?? '';
        
        switch ($action) {
        case 'add_user':
            $result = addUser($_POST);
            if ($result['success']) {
                $message = $result['message'];
            } else {
                $error = $result['message'];
            }
            break;
            
        case 'edit_user':
            $result = editUser($_POST);
            if ($result['success']) {
                $message = $result['message'];
            } else {
                $error = $result['message'];
            }
            break;
            
        case 'delete_user':
            $result = deleteUser($_POST['user_id']);
            if ($result['success']) {
                $message = $result['message'];
            } else {
                $error = $result['message'];
            }
            break;
            
        case 'change_password':
            $result = changePassword($_POST);
            if ($result['success']) {
                $message = $result['message'];
            } else {
                $error = $result['message'];
            }
            break;
            
        case 'toggle_user_status':
            $result = toggleUserStatus($_POST['user_id']);
            if ($result['success']) {
                $message = $result['message'];
            } else {
                $error = $result['message'];
            }
            break;
        }
    }
}

// Get all users
$users = getAllUsers();

/**
 * Add new user
 */
function addUser($data) {
    $username = trim($data['username'] ?? '');
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';
    $role = $data['role'] ?? ROLE_VIEWER;
    
    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'All fields are required.'];
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Please enter a valid email address.'];
    }
    
    if (!in_array($role, [ROLE_SUPER_ADMIN, ROLE_ADMIN, ROLE_VIEWER])) {
        return ['success' => false, 'message' => 'Invalid role selected.'];
    }
    
    // Check if user can create this role
    $currentUserRole = $_SESSION['admin_role'];
    if ($currentUserRole !== ROLE_SUPER_ADMIN && $role === ROLE_SUPER_ADMIN) {
        return ['success' => false, 'message' => 'Only super admins can create other super admins.'];
    }
    
    // Password validation
    $passwordErrors = validatePassword($password);
    if (!empty($passwordErrors)) {
        return ['success' => false, 'message' => implode('<br>', $passwordErrors)];
    }
    
    try {
        $pdo = getDBConnection();
        
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM admin_users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()['count'] > 0) {
            return ['success' => false, 'message' => 'Username or email already exists.'];
        }
        
        // Create user
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO admin_users (username, email, password_hash, role, created_by, is_active) 
            VALUES (?, ?, ?, ?, ?, 1)
        ");
        $stmt->execute([$username, $email, $passwordHash, $role, $_SESSION['admin_user_id']]);
        
        // Log activity
        logAdminActivity('user_created', "Created user: $username with role: $role");
        
        return ['success' => true, 'message' => "User '$username' created successfully."];
        
    } catch (Exception $e) {
        error_log("Error creating user: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error creating user. Please try again.'];
    }
}

/**
 * Edit user
 */
function editUser($data) {
    $userId = $data['user_id'] ?? '';
    $username = trim($data['username'] ?? '');
    $email = trim($data['email'] ?? '');
    $role = $data['role'] ?? '';
    
    if (empty($userId) || empty($username) || empty($email) || empty($role)) {
        return ['success' => false, 'message' => 'All fields are required.'];
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Please enter a valid email address.'];
    }
    
    // Check permissions
    $currentUserRole = $_SESSION['admin_role'];
    if ($currentUserRole !== ROLE_SUPER_ADMIN && $role === ROLE_SUPER_ADMIN) {
        return ['success' => false, 'message' => 'Only super admins can assign super admin role.'];
    }
    
    // Prevent editing own super admin role
    if ($userId == $_SESSION['admin_user_id'] && $currentUserRole === ROLE_SUPER_ADMIN && $role !== ROLE_SUPER_ADMIN) {
        return ['success' => false, 'message' => 'You cannot change your own super admin role.'];
    }
    
    try {
        $pdo = getDBConnection();
        
        // Check if username or email conflicts with other users
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM admin_users WHERE (username = ? OR email = ?) AND id != ?");
        $stmt->execute([$username, $email, $userId]);
        if ($stmt->fetch()['count'] > 0) {
            return ['success' => false, 'message' => 'Username or email already exists.'];
        }
        
        // Update user
        $stmt = $pdo->prepare("
            UPDATE admin_users 
            SET username = ?, email = ?, role = ? 
            WHERE id = ?
        ");
        $stmt->execute([$username, $email, $role, $userId]);
        
        // Log activity
        logAdminActivity('user_updated', "Updated user ID: $userId");
        
        return ['success' => true, 'message' => "User updated successfully."];
        
    } catch (Exception $e) {
        error_log("Error updating user: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error updating user. Please try again.'];
    }
}

/**
 * Change user password
 */
function changePassword($data) {
    $userId = $data['user_id'] ?? '';
    $newPassword = $data['new_password'] ?? '';
    
    if (empty($userId) || empty($newPassword)) {
        return ['success' => false, 'message' => 'User ID and new password are required.'];
    }
    
    // Password validation
    $passwordErrors = validatePassword($newPassword);
    if (!empty($passwordErrors)) {
        return ['success' => false, 'message' => implode('<br>', $passwordErrors)];
    }
    
    try {
        $pdo = getDBConnection();
        
        // Update password
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE id = ?");
        $stmt->execute([$passwordHash, $userId]);
        
        // Revoke all sessions for this user to force re-login
        revokeAllOtherSessions($userId);
        
        // Log activity
        logAdminActivity('password_changed', "Changed password for user ID: $userId");
        
        return ['success' => true, 'message' => "Password changed successfully. User must log in again."];
        
    } catch (Exception $e) {
        error_log("Error changing password: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error changing password. Please try again.'];
    }
}

/**
 * Delete user
 */
function deleteUser($userId) {
    if (empty($userId)) {
        return ['success' => false, 'message' => 'User ID is required.'];
    }
    
    // Prevent deleting own account
    if ($userId == $_SESSION['admin_user_id']) {
        return ['success' => false, 'message' => 'You cannot delete your own account.'];
    }
    
    try {
        $pdo = getDBConnection();
        
        // Get user info before deletion
        $stmt = $pdo->prepare("SELECT username, role FROM admin_users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'message' => 'User not found.'];
        }
        
        // Check if trying to delete the last super admin
        if ($user['role'] === ROLE_SUPER_ADMIN) {
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM admin_users WHERE role = ? AND is_active = 1");
            $stmt->execute([ROLE_SUPER_ADMIN]);
            $superAdminCount = $stmt->fetch()['count'];
            
            if ($superAdminCount <= 1) {
                return ['success' => false, 'message' => 'Cannot delete the last super admin.'];
            }
        }
        
        // Delete user (soft delete by setting is_active = 0)
        $stmt = $pdo->prepare("UPDATE admin_users SET is_active = 0 WHERE id = ?");
        $stmt->execute([$userId]);
        
        // Revoke all sessions for this user
        revokeAllOtherSessions($userId);
        
        // Log activity
        logAdminActivity('user_deleted', "Deleted user: {$user['username']}");
        
        return ['success' => true, 'message' => "User '{$user['username']}' deleted successfully."];
        
    } catch (Exception $e) {
        error_log("Error deleting user: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error deleting user. Please try again.'];
    }
}

/**
 * Toggle user active status
 */
function toggleUserStatus($userId) {
    if (empty($userId)) {
        return ['success' => false, 'message' => 'User ID is required.'];
    }
    
    // Prevent disabling own account
    if ($userId == $_SESSION['admin_user_id']) {
        return ['success' => false, 'message' => 'You cannot disable your own account.'];
    }
    
    try {
        $pdo = getDBConnection();
        
        // Get current status
        $stmt = $pdo->prepare("SELECT username, is_active FROM admin_users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'message' => 'User not found.'];
        }
        
        // Toggle status
        $newStatus = $user['is_active'] ? 0 : 1;
        $stmt = $pdo->prepare("UPDATE admin_users SET is_active = ? WHERE id = ?");
        $stmt->execute([$newStatus, $userId]);
        
        // If disabling, revoke all sessions
        if ($newStatus === 0) {
            revokeAllOtherSessions($userId);
        }
        
        $statusText = $newStatus ? 'enabled' : 'disabled';
        logAdminActivity('user_status_changed', "User {$user['username']} $statusText");
        
        return ['success' => true, 'message' => "User '{$user['username']}' $statusText successfully."];
        
    } catch (Exception $e) {
        error_log("Error toggling user status: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error updating user status. Please try again.'];
    }
}

/**
 * Get all users
 */
function getAllUsers() {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->query("
            SELECT u.*, creator.username as created_by_username
            FROM admin_users u
            LEFT JOIN admin_users creator ON u.created_by = creator.id
            ORDER BY u.created_at DESC
        ");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Error getting users: " . $e->getMessage());
        return [];
    }
}

require 'auth.php'; // Include session management functions
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Mechanic Africa</title>
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
        h1 {
            color: #e74c3c;
            margin: 0;
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
        }
        .nav-links a:hover {
            background: #5a6268;
        }
        .nav-links a.primary {
            background: #e74c3c;
        }
        .nav-links a.primary:hover {
            background: #c0392b;
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
        .user-form {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 6px;
            margin-bottom: 2rem;
        }
        .form-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }
        .form-group {
            flex: 1;
            min-width: 200px;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .form-control:focus {
            outline: none;
            border-color: #e74c3c;
            box-shadow: 0 0 0 2px rgba(231, 76, 60, 0.2);
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 1rem;
            transition: background-color 0.3s;
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
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-success:hover {
            background: #218838;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.9rem;
        }
        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .users-table th,
        .users-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .users-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        .users-table tr:hover {
            background-color: #f8f9fa;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        .role-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .role-super-admin {
            background: #e74c3c;
            color: white;
        }
        .role-admin {
            background: #17a2b8;
            color: white;
        }
        .role-viewer {
            background: #6c757d;
            color: white;
        }
        .actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
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
            max-width: 500px;
            width: 90%;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .modal-header h3 {
            margin: 0;
            color: #e74c3c;
        }
        .close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }
        .close:hover {
            color: #000;
        }
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            .header {
                flex-direction: column;
                align-items: stretch;
            }
            .users-table {
                font-size: 0.9rem;
            }
            .users-table th,
            .users-table td {
                padding: 8px;
            }
            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👥 User Management</h1>
            <div class="nav-links">
                <a href="admin.php" class="primary">📊 Dashboard</a>
                <a href="partner-management.php">🤝 Partners</a>
                <a href="auth.php?action=logout">🔓 Logout</a>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Add User Form -->
        <div class="user-form">
            <h3>➕ Add New User</h3>
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <input type="hidden" name="action" value="add_user">
                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="<?php echo ROLE_VIEWER; ?>">Viewer</option>
                            <option value="<?php echo ROLE_ADMIN; ?>">Admin</option>
                            <?php if ($_SESSION['admin_role'] === ROLE_SUPER_ADMIN): ?>
                                <option value="<?php echo ROLE_SUPER_ADMIN; ?>">Super Admin</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Create User</button>
            </form>
        </div>

        <!-- Users Table -->
        <h3>📋 Existing Users</h3>
        <table class="users-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td>
                        <strong><?php echo htmlspecialchars($user['username']); ?></strong><br>
                        <small><?php echo htmlspecialchars($user['email']); ?></small>
                    </td>
                    <td>
                        <span class="role-badge role-<?php echo str_replace('_', '-', $user['role']); ?>">
                            <?php echo ucwords(str_replace('_', ' ', $user['role'])); ?>
                        </span>
                    </td>
                    <td>
                        <span class="status-badge status-<?php echo $user['is_active'] ? 'active' : 'inactive'; ?>">
                            <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </td>
                    <td>
                        <?php echo date('M j, Y', strtotime($user['created_at'])); ?><br>
                        <small>by <?php echo $user['created_by_username'] ? htmlspecialchars($user['created_by_username']) : 'System'; ?></small>
                    </td>
                    <td>
                        <?php echo $user['last_login'] ? date('M j, Y g:i A', strtotime($user['last_login'])) : 'Never'; ?>
                    </td>
                    <td>
                        <div class="actions">
                            <?php if ($user['id'] != $_SESSION['admin_user_id']): ?>
                                <button class="btn btn-secondary btn-sm" onclick="editUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>', '<?php echo htmlspecialchars($user['email']); ?>', '<?php echo $user['role']; ?>')">Edit</button>
                                <button class="btn btn-secondary btn-sm" onclick="changePassword(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')">Reset Password</button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    <input type="hidden" name="action" value="toggle_user_status">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="btn <?php echo $user['is_active'] ? 'btn-danger' : 'btn-success'; ?> btn-sm">
                                        <?php echo $user['is_active'] ? 'Disable' : 'Enable'; ?>
                                    </button>
                                </form>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    <input type="hidden" name="action" value="delete_user">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            <?php else: ?>
                                <span class="status-badge status-active">Current User</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (empty($users)): ?>
            <p>No users found.</p>
        <?php endif; ?>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>✏️ Edit User</h3>
                <button class="close" onclick="closeModal('editUserModal')">&times;</button>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <input type="hidden" name="action" value="edit_user">
                <input type="hidden" id="edit_user_id" name="user_id">
                <div class="form-group">
                    <label for="edit_username">Username</label>
                    <input type="text" id="edit_username" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_email">Email</label>
                    <input type="email" id="edit_email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_role">Role</label>
                    <select id="edit_role" name="role" class="form-control" required>
                        <option value="<?php echo ROLE_VIEWER; ?>">Viewer</option>
                        <option value="<?php echo ROLE_ADMIN; ?>">Admin</option>
                        <?php if ($_SESSION['admin_role'] === ROLE_SUPER_ADMIN): ?>
                            <option value="<?php echo ROLE_SUPER_ADMIN; ?>">Super Admin</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" class="btn btn-primary">Update User</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editUserModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>🔑 Change Password</h3>
                <button class="close" onclick="closeModal('changePasswordModal')">&times;</button>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <input type="hidden" name="action" value="change_password">
                <input type="hidden" id="password_user_id" name="user_id">
                <p>Changing password for: <strong id="password_username"></strong></p>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                    <small>Password must be at least 8 characters with uppercase, lowercase, numbers, and special characters.</small>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('changePasswordModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editUser(id, username, email, role) {
            document.getElementById('edit_user_id').value = id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            document.getElementById('editUserModal').style.display = 'block';
        }

        function changePassword(id, username) {
            document.getElementById('password_user_id').value = id;
            document.getElementById('password_username').textContent = username;
            document.getElementById('new_password').value = '';
            document.getElementById('changePasswordModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }

        // Auto-hide alerts after 5 seconds
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