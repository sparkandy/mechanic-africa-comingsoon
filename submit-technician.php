<?php
/**
 * Technician Application Submission Handler
 * Processes technician registration form submissions with validation and security
 */

// Start session for CSRF token validation
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
    exit;
}

// CSRF Token validation
$csrfToken = $_POST['csrf_token'] ?? '';
if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrfToken)) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid security token. Please refresh the page and try again.'
    ]);
    exit;
}

// Get client IP address securely
function getClientIP() {
    // Always use REMOTE_ADDR as it cannot be spoofed
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    
    // Validate IP format
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
        return $ip;
    }
    
    return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
}

// Rate limiting - 5 submissions per hour per IP
$ipAddress = getClientIP();
$rateLimit = 5;
$timeWindow = 3600; // 1 hour

try {
    $dbFile = __DIR__ . '/contacts.db';
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check rate limit
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM technicians 
        WHERE ip_address = ? 
        AND submitted_at > datetime('now', '-1 hour')
    ");
    $stmt->execute([$ipAddress]);
    $recentSubmissions = $stmt->fetch()['count'];
    
    if ($recentSubmissions >= $rateLimit) {
        http_response_code(429);
        echo json_encode([
            'success' => false,
            'message' => 'Too many submissions. Please try again later.'
        ]);
        exit;
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed. Please try again.'
    ]);
    exit;
}

// Get and sanitize form inputs with length limits
$fullName = substr(trim($_POST['full_name'] ?? ''), 0, 200);
$phoneNumber = substr(trim($_POST['phone_number'] ?? ''), 0, 50);
$email = substr(trim($_POST['email'] ?? ''), 0, 100);
$stateCity = substr(trim($_POST['state_city'] ?? ''), 0, 100);
$areaOfSpecialization = substr(trim($_POST['area_of_specialization'] ?? ''), 0, 200);
$yearsInOperation = filter_var($_POST['years_in_operation'] ?? 0, FILTER_VALIDATE_INT);
$workType = substr(trim($_POST['work_type'] ?? ''), 0, 100);
$certificationTraining = substr(trim($_POST['certification_training'] ?? ''), 0, 500);

// Validation
$errors = [];

if (empty($fullName)) {
    $errors[] = 'Full name is required';
}

if (empty($phoneNumber)) {
    $errors[] = 'Phone number is required';
}

if (empty($email)) {
    $errors[] = 'Email address is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address';
}

if (empty($stateCity)) {
    $errors[] = 'State/City is required';
}

if (empty($areaOfSpecialization)) {
    $errors[] = 'Area of specialization is required';
}

if ($yearsInOperation === false || $yearsInOperation < 0) {
    $errors[] = 'Valid years in operation is required';
}

if (empty($workType)) {
    $errors[] = 'Work type (independent or workshop) is required';
}

if (empty($certificationTraining)) {
    $errors[] = 'Certification/Training background is required';
}

// Return validation errors
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => implode(', ', $errors)
    ]);
    exit;
}

// Insert data into database
try {
    $stmt = $pdo->prepare("
        INSERT INTO technicians (
            full_name, 
            phone_number, 
            email, 
            state_city, 
            area_of_specialization, 
            years_in_operation, 
            work_type, 
            certification_training, 
            ip_address,
            status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    
    $stmt->execute([
        $fullName,
        $phoneNumber,
        $email,
        $stateCity,
        $areaOfSpecialization,
        $yearsInOperation,
        $workType,
        $certificationTraining,
        $ipAddress
    ]);
    
    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your interest in joining Mechanic Africa as a technician! Your application has been submitted successfully. Our team will review your information and contact you within 2-3 business days.'
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to save your application. Please try again.'
    ]);
}
?>
