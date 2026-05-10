<?php
/**
 * User Login Endpoint
 * POST /api/auth/login
 * 
 * Required fields:
 * - email: string
 * - password: string
 */

// Headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');

// Load configuration and helpers
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth_guard.php';

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond_json('error', 'Method not allowed', null, API_BAD_REQUEST);
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (empty($input['email']) || empty($input['password'])) {
    respond_json('error', 'Email and password are required', null, API_BAD_REQUEST);
}

// Extract and sanitize input
$email = sanitize_input($input['email']);
$password = $input['password'];

try {
    // Find user by email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    // Check if user exists
    if (!$user) {
        log_error("Failed login attempt - user not found", ['email' => $email]);
        respond_json('error', 'Invalid email or password', null, API_UNAUTHORIZED);
    }
    
    // Verify password
    if (!verify_password($password, $user['password_hash'])) {
        log_error("Failed login attempt - invalid password", ['email' => $email]);
        respond_json('error', 'Invalid email or password', null, API_UNAUTHORIZED);
    }
    
    // Check if user account is active
    if (!$user['is_active']) {
        log_error("Failed login attempt - account inactive", ['email' => $email, 'user_id' => $user['user_id']]);
        respond_json('error', 'Account is inactive. Please contact support.', null, API_UNAUTHORIZED);
    }
    
    // Update last_login timestamp
    $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
    $stmt->execute([$user['user_id']]);
    
    // Set session data
    set_user_session([
        'user_id' => $user['user_id'],
        'email' => $user['email'],
        'role' => $user['role'],
        'first_name' => $user['first_name'] ?? '',
        'last_name' => $user['last_name'] ?? ''
    ]);
    
    // Fetch profile data based on role
    $profile_data = null;
    if ($user['role'] === 'freelancer') {
        $stmt = $pdo->prepare("SELECT * FROM freelancers WHERE user_id = ?");
    } elseif ($user['role'] === 'client') {
        $stmt = $pdo->prepare("SELECT * FROM client WHERE user_id = ?");
    } elseif ($user['role'] === 'manager') {
        $stmt = $pdo->prepare("SELECT * FROM project_manager WHERE user_id = ?");
    }
    
    if ($profile_data === null && isset($stmt)) {
        $stmt->execute([$user['user_id']]);
        $profile_data = $stmt->fetch();
    }
    
    log_error("User login successful", ['user_id' => $user['user_id'], 'email' => $email, 'role' => $user['role']]);
    
    respond_json('success', 'Login successful', [
        'user_id' => $user['user_id'],
        'email' => $user['email'],
        'role' => $user['role'],
        'first_name' => $profile_data['first_name'] ?? '',
        'last_name' => $profile_data['last_name'] ?? '',
        'session_id' => session_id()
    ], API_SUCCESS);
    
} catch (Exception $e) {
    log_error("Login error", ['email' => $email, 'error' => $e->getMessage()]);
    respond_json('error', 'Login failed. Please try again.', null, API_SERVER_ERROR);
}
