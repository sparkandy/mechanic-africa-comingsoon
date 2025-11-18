<?php
/**
 * Authentication Configuration
 * Contains settings and constants for the admin authentication system
 */

// Start session with secure settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');

// Session configuration
define('SESSION_NAME', 'MECHANIC_AFRICA_ADMIN');
define('SESSION_LIFETIME', 3600 * 8); // 8 hours
define('REMEMBER_ME_LIFETIME', 3600 * 24 * 30); // 30 days

// Password requirements
define('MIN_PASSWORD_LENGTH', 8);
define('REQUIRE_UPPERCASE', true);
define('REQUIRE_LOWERCASE', true);
define('REQUIRE_NUMBERS', true);
define('REQUIRE_SPECIAL_CHARS', true);

// Security settings
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes
define('SESSION_REGENERATE_INTERVAL', 300); // 5 minutes

// User roles
define('ROLE_SUPER_ADMIN', 'super_admin');
define('ROLE_ADMIN', 'admin');
define('ROLE_VIEWER', 'viewer');

// Database file
define('DB_FILE', 'contacts.db');

/**
 * Initialize secure session
 */
function initSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_NAME);
        session_set_cookie_params([
            'lifetime' => SESSION_LIFETIME,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        session_start();
    }
    
    // Regenerate session ID periodically
    if (!isset($_SESSION['last_regeneration'])) {
        $_SESSION['last_regeneration'] = time();
    } elseif (time() - $_SESSION['last_regeneration'] > SESSION_REGENERATE_INTERVAL) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}

/**
 * Get database connection
 */
function getDBConnection() {
    try {
        $pdo = new PDO('sqlite:' . DB_FILE);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        throw new Exception("Database connection failed");
    }
}

/**
 * Check if user is authenticated
 */
function isAuthenticated() {
    initSecureSession();
    return isset($_SESSION['admin_user_id']) && isset($_SESSION['admin_username']);
}

/**
 * Check if user has required role
 */
function hasRole($requiredRole) {
    if (!isAuthenticated()) {
        return false;
    }
    
    $userRole = $_SESSION['admin_role'] ?? '';
    
    // Super admin can access everything
    if ($userRole === ROLE_SUPER_ADMIN) {
        return true;
    }
    
    // Admin can access admin and viewer functions
    if ($userRole === ROLE_ADMIN && in_array($requiredRole, [ROLE_ADMIN, ROLE_VIEWER])) {
        return true;
    }
    
    // Viewer can only access viewer functions
    if ($userRole === ROLE_VIEWER && $requiredRole === ROLE_VIEWER) {
        return true;
    }
    
    return false;
}

/**
 * Require authentication or redirect to login
 */
function requireAuth($role = ROLE_VIEWER) {
    if (!hasRole($role)) {
        $loginUrl = 'login.php';
        if (!isAuthenticated()) {
            // Not logged in - redirect to login
            $currentUrl = $_SERVER['REQUEST_URI'];
            header("Location: $loginUrl?redirect=" . urlencode($currentUrl));
        } else {
            // Logged in but insufficient permissions
            header("Location: $loginUrl?error=insufficient_permissions");
        }
        exit;
    }
}

/**
 * Log admin activity
 */
function logAdminActivity($action, $details = '') {
    if (!isAuthenticated()) {
        return;
    }
    
    try {
        $pdo = getDBConnection();
        
        // Create activity log table if it doesn't exist
        $createLogTable = "
            CREATE TABLE IF NOT EXISTS admin_activity_log (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                action VARCHAR(100) NOT NULL,
                details TEXT,
                ip_address VARCHAR(45),
                user_agent TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES admin_users(id)
            )
        ";
        $pdo->exec($createLogTable);
        
        // Insert log entry
        $stmt = $pdo->prepare("
            INSERT INTO admin_activity_log (user_id, action, details, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $_SESSION['admin_user_id'],
            $action,
            $details,
            $_SERVER['REMOTE_ADDR'] ?? '',
            $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
        
    } catch (Exception $e) {
        error_log("Failed to log admin activity: " . $e->getMessage());
    }
}

/**
 * Validate password strength
 */
function validatePassword($password) {
    $errors = [];
    
    if (strlen($password) < MIN_PASSWORD_LENGTH) {
        $errors[] = "Password must be at least " . MIN_PASSWORD_LENGTH . " characters long";
    }
    
    if (REQUIRE_UPPERCASE && !preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter";
    }
    
    if (REQUIRE_LOWERCASE && !preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain at least one lowercase letter";
    }
    
    if (REQUIRE_NUMBERS && !preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one number";
    }
    
    if (REQUIRE_SPECIAL_CHARS && !preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = "Password must contain at least one special character";
    }
    
    return $errors;
}

/**
 * Generate secure random token
 */
function generateSecureToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Clean old sessions
 */
function cleanOldSessions() {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("DELETE FROM admin_sessions WHERE expires_at < datetime('now')");
        $stmt->execute();
    } catch (Exception $e) {
        error_log("Failed to clean old sessions: " . $e->getMessage());
    }
}

/**
 * Get user information
 */
function getCurrentUser() {
    if (!isAuthenticated()) {
        return null;
    }
    
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE id = ? AND is_active = 1");
        $stmt->execute([$_SESSION['admin_user_id']]);
        return $stmt->fetch();
    } catch (Exception $e) {
        error_log("Failed to get current user: " . $e->getMessage());
        return null;
    }
}

// Initialize session when this file is included
initSecureSession();
?>