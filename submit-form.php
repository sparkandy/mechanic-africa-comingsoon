<?php
// Include configuration
require_once 'config.php';

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set content type
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
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
            car_information TEXT NOT NULL,
            submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            ip_address TEXT
        )
    ";
    $pdo->exec($createTableSQL);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
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

// Debug: Log the received data (remove in production)
error_log('Received data: ' . print_r($input, true));
error_log('Content-Type: ' . $contentType);

$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$carInfo = trim($input['car'] ?? '');
$captchaResponse = trim($input['g-recaptcha-response'] ?? '');

// reCAPTCHA configuration
$recaptchaSecretKey = RECAPTCHA_SECRET_KEY;

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
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
        INSERT INTO contacts (name, email, car_information, ip_address) 
        VALUES (?, ?, ?, ?)
    ");
    
    $stmt->execute([$name, $email, $carInfo, $ipAddress]);
    
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