<?php
/**
 * Partner Application Submission Handler
 * Processes partner registration form submissions with validation and security
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
        FROM partners 
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
$companyName = substr(trim($_POST['company_name'] ?? ''), 0, 200);
$registrationNumber = substr(trim($_POST['registration_number'] ?? ''), 0, 100);
$phoneNumber = substr(trim($_POST['phone_number'] ?? ''), 0, 50);
$email = substr(trim($_POST['email'] ?? ''), 0, 100);
$techniciansCount = filter_var($_POST['technicians_count'] ?? 0, FILTER_VALIDATE_INT);
$yearsInOperation = filter_var($_POST['years_in_operation'] ?? 0, FILTER_VALIDATE_INT);
$workshopAddress = substr(trim($_POST['workshop_address'] ?? ''), 0, 500);
$stateCity = substr(trim($_POST['state_city'] ?? ''), 0, 100);
$servicesOffered = substr(trim($_POST['services_offered'] ?? ''), 0, 500);
$mobileMechanicService = substr(trim($_POST['mobile_mechanic_service'] ?? ''), 0, 10);

// Validation
$errors = [];

if (empty($companyName)) {
    $errors[] = 'Company name is required';
}

if (empty($registrationNumber)) {
    $errors[] = 'Company registration number is required';
}

if (empty($phoneNumber)) {
    $errors[] = 'Phone number is required';
}

if (empty($email)) {
    $errors[] = 'Email address is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address';
}

if ($techniciansCount === false || $techniciansCount < 0) {
    $errors[] = 'Valid number of technicians is required';
}

if ($yearsInOperation === false || $yearsInOperation < 0) {
    $errors[] = 'Valid years in operation is required';
}

if (empty($workshopAddress)) {
    $errors[] = 'Workshop address is required';
}

if (empty($stateCity)) {
    $errors[] = 'State/City is required';
}

if (empty($servicesOffered)) {
    $errors[] = 'Type of services offered is required';
}

if (empty($mobileMechanicService)) {
    $errors[] = 'Mobile mechanic service preference is required';
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
        INSERT INTO partners (
            company_name, 
            registration_number, 
            phone_number, 
            email, 
            technicians_count, 
            years_in_operation, 
            workshop_address, 
            state_city, 
            services_offered, 
            mobile_mechanic_service, 
            ip_address,
            status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    
    $stmt->execute([
        $companyName,
        $registrationNumber,
        $phoneNumber,
        $email,
        $techniciansCount,
        $yearsInOperation,
        $workshopAddress,
        $stateCity,
        $servicesOffered,
        $mobileMechanicService,
        $ipAddress
    ]);
    
    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your interest in partnering with Mechanic Africa! Your application has been submitted successfully. Our team will review your information and contact you within 2-3 business days.'
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to save your application. Please try again.'
    ]);
}
?>
