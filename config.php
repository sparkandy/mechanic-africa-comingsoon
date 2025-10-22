<?php
/**
 * Configuration file for Mechanic Africa website
 * 
 * IMPORTANT: Replace the placeholder keys with your actual reCAPTCHA keys
 * Get your keys from: https://www.google.com/recaptcha/admin/create
 */

// reCAPTCHA Configuration
// Site Key (public key) - used in HTML forms
define('RECAPTCHA_SITE_KEY', '6LcXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');

// Secret Key (private key) - used for server-side verification
define('RECAPTCHA_SECRET_KEY', '6LcXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');

// Database configuration
define('DB_FILE', 'contacts.db');

// Security settings
define('ENABLE_CAPTCHA', true); // Set to false to disable CAPTCHA temporarily

// Rate limiting (optional - for future implementation)
define('MAX_SUBMISSIONS_PER_HOUR', 10);
define('MAX_SUBMISSIONS_PER_DAY', 50);

?>