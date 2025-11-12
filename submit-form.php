<?php
// Include configuration
require_once 'config.php';

// Error reporting for production - log errors, don't display them
error_reporting(E_ALL);
ini_set('display_errors', 0);  // Never show errors to users in production
ini_set('log_errors', 1);       // Log errors to file
ini_set('error_log', __DIR__ . '/error.log');

// Set content type
header('Content-Type: application/json');

// CORS - Restrict to your domain only (remove in production if not needed)
$allowed_origins = ['https://mechanicafrica.com', 'https://www.mechanicafrica.com'];
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// CSRF Protection
session_start();
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
    $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid security token']);
    exit;
}

// Database file path
$dbFile = DB_FILE;

// Create database connection
try {
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create table if it doesn't exist
    $createTableSQL = "
        CREATE TABLE IF NOT EXISTS contacts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            selected_package TEXT,
            car_information TEXT NOT NULL,
            submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            ip_address TEXT
        )
    ";
    $pdo->exec($createTableSQL);
    
    // Add selected_package column if it doesn't exist (for existing databases)
    try {
        $pdo->exec("ALTER TABLE contacts ADD COLUMN selected_package TEXT");
    } catch (PDOException $e) {
        // Column already exists, ignore error
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Service temporarily unavailable']);
    error_log('Database connection failed: ' . $e->getMessage());
    exit;
}

// Rate limiting check
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
try {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM contacts 
        WHERE ip_address = ? 
        AND submitted_at > datetime('now', '-1 hour')
    ");
    $stmt->execute([$ip]);
    $submissions = $stmt->fetch()['count'];
    
    if ($submissions >= MAX_SUBMISSIONS_PER_HOUR) {
        http_response_code(429);
        echo json_encode([
            'success' => false, 
            'message' => 'Too many submissions. Please try again later.'
        ]);
        exit;
    }
} catch (PDOException $e) {
    // Continue if rate limit check fails (don't block legitimate users)
    error_log('Rate limit check failed: ' . $e->getMessage());
}

// Get form data
$input = null;
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

if (strpos($contentType, 'application/json') !== false) {
    // Handle JSON data
    $input = json_decode(file_get_contents('php://input'), true);
} else {
    // Handle form data
    $input = $_POST;
}

// Sanitize and limit input lengths
$name = substr(trim($input['name'] ?? ''), 0, 100);
$email = substr(trim($input['email'] ?? ''), 0, 255);
$package = substr(trim($input['package'] ?? ''), 0, 50);
$carInfo = substr(trim($input['car'] ?? ''), 0, 200);
$captchaResponse = trim($input['g-recaptcha-response'] ?? '');

// reCAPTCHA configuration
$recaptchaSecretKey = RECAPTCHA_SECRET_KEY;

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($package)) {
    $errors[] = 'Service package is required';
}

if (empty($email)) {
    $errors[] = 'Email address is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address';
}

if (empty($carInfo)) {
    $errors[] = 'Car information is required';
}

// CAPTCHA validation
if (ENABLE_CAPTCHA) {
    if (empty($captchaResponse)) {
        $errors[] = 'Please complete the CAPTCHA verification';
    } else {
        // Verify CAPTCHA with Google
        $verifyURL = 'https://www.google.com/recaptcha/api/siteverify';
        $postData = http_build_query([
            'secret' => $recaptchaSecretKey,
            'response' => $captchaResponse,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
        ]);
        
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postData
            ]
        ]);
        
        $response = file_get_contents($verifyURL, false, $context);
        $responseData = json_decode($response, true);
        
        if (!$responseData || !$responseData['success']) {
            $errors[] = 'CAPTCHA verification failed. Please try again.';
            error_log('CAPTCHA verification failed: ' . print_r($responseData, true));
        }
    }
}

// Return validation errors
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'All fields are required',
        'errors' => $errors
    ]);
    exit;
}

// Get client IP address
$ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';

// Insert data into database
try {
    $stmt = $pdo->prepare("
        INSERT INTO contacts (name, email, selected_package, car_information, ip_address) 
        VALUES (?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([$name, $email, $package, $carInfo, $ipAddress]);
    
    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your information has been submitted successfully. We will contact you soon.'
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to save your information. Please try again.'
    ]);
}
?>