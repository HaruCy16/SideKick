<?php
/**
 * Application Configuration
 * Define application constants and settings
 */

// ===== SECURITY & GENERAL SETTINGS =====
define('APP_NAME', 'Freelance Management System');
define('BASE_URL', 'http://localhost/SideKick');
define('TIMEZONE', 'UTC');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

// Set default timezone
date_default_timezone_set(TIMEZONE);

// ===== SESSION CONFIGURATION =====
define('SESSION_TIMEOUT', 30); // minutes
define('SESSION_NAME', 'SIDEKICK_SESSION');

// Create sessions directory if it doesn't exist
$sessionPath = __DIR__ . '/../sessions/';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0755, true);
}

// Set session save path
ini_set('session.save_path', $sessionPath);

// Session settings
ini_set('session.gc_maxlifetime', SESSION_TIMEOUT * 60);
ini_set('session.cookie_lifetime', SESSION_TIMEOUT * 60);
session_name(SESSION_NAME);

// Configure session cookie security
$sessionOptions = [
    'lifetime' => SESSION_TIMEOUT * 60,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'httponly' => true,
    'samesite' => 'Lax'  // Changed from Strict to Lax
];

// PHP 7.3+ session options
if (PHP_VERSION_ID >= 70300) {
    session_set_cookie_params($sessionOptions);
} else {
    // Fallback for older PHP versions
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 1 : 0);
}

// ===== PASSWORD REQUIREMENTS =====
define('PASSWORD_MIN_LENGTH', 8);
define('PASSWORD_HASH_ALGO', PASSWORD_DEFAULT);

// ===== SECURITY LIMITS =====
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_ATTEMPT_TIMEOUT', 15); // minutes

// ===== FILE UPLOAD SETTINGS =====
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB in bytes
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);

// ===== PAGINATION =====
define('ITEMS_PER_PAGE', 20);
define('ITEMS_PER_PAGE_ADMIN', 50);

// ===== ERROR REPORTING =====
// Development environment - show errors
// Change to 0 for production
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}

// ===== LOGGING =====
define('LOG_DIR', __DIR__ . '/../logs/');
define('ERROR_LOG_FILE', LOG_DIR . 'error.log');
define('ACCESS_LOG_FILE', LOG_DIR . 'access.log');

// Set error logging
if (!file_exists(LOG_DIR)) {
    mkdir(LOG_DIR, 0755, true);
}
ini_set('error_log', ERROR_LOG_FILE);

// ===== CURRENCY AND FORMATTING =====
define('CURRENCY_SYMBOL', '$');
define('CURRENCY_CODE', 'USD');
define('DATE_FORMAT', 'Y-m-d');
define('DATETIME_FORMAT', 'Y-m-d H:i:s');

// ===== ROLE DEFINITIONS =====
define('ROLES', [
    'admin' => 'Administrator',
    'manager' => 'Project Manager',
    'freelancer' => 'Freelancer',
    'client' => 'Client'
]);

// ===== PROJECT STATUSES =====
define('PROJECT_STATUSES', ['active', 'completed', 'on_hold', 'cancelled']);
define('TASK_STATUSES', ['pending', 'in_progress', 'completed', 'cancelled']);
define('PAYMENT_STATUSES', ['pending', 'paid', 'overdue', 'refunded']);

// ===== API RESPONSE CODES =====
define('API_SUCCESS', 200);
define('API_CREATED', 201);
define('API_BAD_REQUEST', 400);
define('API_UNAUTHORIZED', 401);
define('API_FORBIDDEN', 403);
define('API_NOT_FOUND', 404);
define('API_CONFLICT', 409);
define('API_SERVER_ERROR', 500);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
