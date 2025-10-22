<?php
/**
 * Authentication Handler
 * Handles login, logout, and session management operations
 */

require_once 'auth-config.php';

// Get the action from URL parameter
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'logout':
        handleLogout();
        break;
        
    case 'check_session':
        handleSessionCheck();
        break;
        
    case 'refresh_session':
        handleSessionRefresh();
        break;
        
    case 'check_remember':
        handleRememberMeCheck();
        break;
        
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
        break;
}

/**
 * Handle user logout
 */
function handleLogout() {
    initSecureSession();
    
    if (isAuthenticated()) {
        try {
            $pdo = getDBConnection();
            
            // Log logout activity
            logAdminActivity('logout', 'User logged out');
            
            // Remove remember me token if it exists
            if (isset($_COOKIE['remember_token'])) {
                $stmt = $pdo->prepare("DELETE FROM admin_sessions WHERE session_token = ?");
                $stmt->execute([$_COOKIE['remember_token']]);
                
                // Clear the cookie
                setcookie('remember_token', '', time() - 3600, '/', '', isset($_SERVER['HTTPS']), true);
            }
            
            // Clean up expired sessions
            cleanOldSessions();
            
        } catch (Exception $e) {
            error_log("Logout error: " . $e->getMessage());
        }
    }
    
    // Destroy session
    session_destroy();
    
    // Redirect to login page with logout message
    header("Location: login.php?logout=1");
    exit;
}

/**
 * Check if current session is valid (AJAX endpoint)
 */
function handleSessionCheck() {
    header('Content-Type: application/json');
    
    $response = [
        'authenticated' => isAuthenticated(),
        'time_remaining' => 0,
        'user' => null
    ];
    
    if (isAuthenticated()) {
        $loginTime = $_SESSION['login_time'] ?? time();
        $timeRemaining = SESSION_LIFETIME - (time() - $loginTime);
        
        if ($timeRemaining <= 0) {
            // Session expired
            session_destroy();
            $response['authenticated'] = false;
            $response['expired'] = true;
        } else {
            $response['time_remaining'] = $timeRemaining;
            $response['user'] = [
                'id' => $_SESSION['admin_user_id'],
                'username' => $_SESSION['admin_username'],
                'email' => $_SESSION['admin_email'],
                'role' => $_SESSION['admin_role']
            ];
        }
    }
    
    echo json_encode($response);
    exit;
}

/**
 * Refresh session timeout (AJAX endpoint)
 */
function handleSessionRefresh() {
    header('Content-Type: application/json');
    
    if (!isAuthenticated()) {
        echo json_encode(['success' => false, 'error' => 'Not authenticated']);
        exit;
    }
    
    // Update session login time
    $_SESSION['login_time'] = time();
    
    // Log session refresh
    logAdminActivity('session_refresh', 'Session timeout refreshed');
    
    echo json_encode([
        'success' => true,
        'time_remaining' => SESSION_LIFETIME
    ]);
    exit;
}

/**
 * Check and validate remember me token
 */
function handleRememberMeCheck() {
    if (isAuthenticated()) {
        // Already logged in
        header("Location: admin.php");
        exit;
    }
    
    if (!isset($_COOKIE['remember_token'])) {
        // No remember token
        header("Location: login.php");
        exit;
    }
    
    try {
        $pdo = getDBConnection();
        
        // Check if remember token is valid
        $stmt = $pdo->prepare("
            SELECT s.*, u.id, u.username, u.email, u.role 
            FROM admin_sessions s
            JOIN admin_users u ON s.user_id = u.id
            WHERE s.session_token = ? 
            AND s.expires_at > datetime('now')
            AND u.is_active = 1
        ");
        $stmt->execute([$_COOKIE['remember_token']]);
        $session = $stmt->fetch();
        
        if ($session) {
            // Valid remember token - log user in
            $_SESSION['admin_user_id'] = $session['id'];
            $_SESSION['admin_username'] = $session['username'];
            $_SESSION['admin_email'] = $session['email'];
            $_SESSION['admin_role'] = $session['role'];
            $_SESSION['login_time'] = time();
            $_SESSION['remembered_login'] = true;
            
            // Update last login
            $stmt = $pdo->prepare("UPDATE admin_users SET last_login = datetime('now') WHERE id = ?");
            $stmt->execute([$session['id']]);
            
            // Log remembered login
            logAdminActivity('remembered_login', 'Automatic login via remember token');
            
            // Update session expiry
            $newExpiry = date('Y-m-d H:i:s', time() + REMEMBER_ME_LIFETIME);
            $stmt = $pdo->prepare("UPDATE admin_sessions SET expires_at = ? WHERE id = ?");
            $stmt->execute([$newExpiry, $session['id']]);
            
            // Redirect to admin panel
            header("Location: admin.php");
            exit;
            
        } else {
            // Invalid or expired token - clear cookie
            setcookie('remember_token', '', time() - 3600, '/', '', isset($_SERVER['HTTPS']), true);
            header("Location: login.php");
            exit;
        }
        
    } catch (Exception $e) {
        error_log("Remember me check error: " . $e->getMessage());
        header("Location: login.php?error=session_error");
        exit;
    }
}

/**
 * Get login attempts for an IP address
 */
function getLoginAttempts($ip) {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as attempts 
            FROM admin_activity_log 
            WHERE action = 'failed_login' 
            AND ip_address = ? 
            AND created_at > datetime('now', '-" . (LOGIN_LOCKOUT_TIME / 60) . " minutes')
        ");
        $stmt->execute([$ip]);
        return $stmt->fetch()['attempts'];
    } catch (Exception $e) {
        error_log("Error getting login attempts: " . $e->getMessage());
        return 0;
    }
}

/**
 * Check if IP is temporarily locked out
 */
function isIPLockedOut($ip) {
    return getLoginAttempts($ip) >= MAX_LOGIN_ATTEMPTS;
}

/**
 * Get user's active sessions
 */
function getUserActiveSessions($userId) {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("
            SELECT * FROM admin_sessions 
            WHERE user_id = ? AND expires_at > datetime('now')
            ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Error getting user sessions: " . $e->getMessage());
        return [];
    }
}

/**
 * Revoke a specific session
 */
function revokeSession($sessionId, $userId) {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("
            DELETE FROM admin_sessions 
            WHERE id = ? AND user_id = ?
        ");
        return $stmt->execute([$sessionId, $userId]);
    } catch (Exception $e) {
        error_log("Error revoking session: " . $e->getMessage());
        return false;
    }
}

/**
 * Revoke all sessions for a user except current
 */
function revokeAllOtherSessions($userId, $currentToken = null) {
    try {
        $pdo = getDBConnection();
        
        if ($currentToken) {
            $stmt = $pdo->prepare("
                DELETE FROM admin_sessions 
                WHERE user_id = ? AND session_token != ?
            ");
            $stmt->execute([$userId, $currentToken]);
        } else {
            $stmt = $pdo->prepare("DELETE FROM admin_sessions WHERE user_id = ?");
            $stmt->execute([$userId]);
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Error revoking sessions: " . $e->getMessage());
        return false;
    }
}
?>