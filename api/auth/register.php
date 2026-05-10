<?php
/**
 * User Registration Endpoint
 * POST /api/auth/register
 * 
 * Required fields:
 * - first_name: string
 * - last_name: string
 * - email: string (unique)
 * - password: string (min 8 chars)
 * - password_confirm: string (must match password)
 * - role: string (admin, manager, freelancer, client)
 */

// Headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');

// Load configuration and helpers
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/helpers.php';

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
$required_fields = ['first_name', 'last_name', 'email', 'password', 'password_confirm', 'role'];
foreach ($required_fields as $field) {
    if (empty($input[$field])) {
        respond_json('error', "Missing required field: $field", null, API_BAD_REQUEST);
    }
}

// Extract and sanitize input
$first_name = sanitize_input($input['first_name']);
$last_name = sanitize_input($input['last_name']);
$email = sanitize_input($input['email']);
$password = $input['password'];
$password_confirm = $input['password_confirm'];
$role = sanitize_input($input['role']);

// Validate email format
if (!is_valid_email($email)) {
    respond_json('error', 'Invalid email format', null, API_BAD_REQUEST);
}

// Validate password length
if (strlen($password) < PASSWORD_MIN_LENGTH) {
    respond_json('error', "Password must be at least " . PASSWORD_MIN_LENGTH . " characters long", null, API_BAD_REQUEST);
}

// Validate password match
if ($password !== $password_confirm) {
    respond_json('error', 'Passwords do not match', null, API_BAD_REQUEST);
}

// Validate role
if (!in_array($role, array_keys(ROLES))) {
    respond_json('error', 'Invalid role selected', null, API_BAD_REQUEST);
}

try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        respond_json('error', 'Email already registered', null, API_CONFLICT);
    }
    
    // Hash password
    $password_hash = hash_password($password);
    if ($password_hash === false) {
        throw new Exception('Password hashing failed');
    }
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Insert user
    $stmt = $pdo->prepare("
        INSERT INTO users (role, is_verified, is_active, email, password_hash, date_registered)
        VALUES (?, FALSE, TRUE, ?, ?, NOW())
    ");
    $stmt->execute([$role, $email, $password_hash]);
    $user_id = $pdo->lastInsertId();
    
    // Create profile based on role
    if ($role === 'freelancer') {
        $stmt = $pdo->prepare("
            INSERT INTO freelancers (user_id, first_name, last_name, account_status, date_registered)
            VALUES (?, ?, ?, 'active', NOW())
        ");
        $stmt->execute([$user_id, $first_name, $last_name]);
    } elseif ($role === 'client') {
        $stmt = $pdo->prepare("
            INSERT INTO client (user_id, first_name, last_name, email, client_status, date_added)
            VALUES (?, ?, ?, ?, 'active', NOW())
        ");
        $stmt->execute([$user_id, $first_name, $last_name, $email]);
    } elseif ($role === 'manager') {
        $stmt = $pdo->prepare("
            INSERT INTO project_manager (user_id, first_name, last_name, account_status)
            VALUES (?, ?, ?, 'active')
        ");
        $stmt->execute([$user_id, $first_name, $last_name]);
    }
    
    // Commit transaction
    $pdo->commit();
    
    // Log the registration
    log_error("User registered successfully", ['user_id' => $user_id, 'email' => $email, 'role' => $role]);
    
    respond_json('success', 'Registration successful. Please log in with your credentials.', [
        'user_id' => $user_id,
        'email' => $email,
        'role' => $role,
        'first_name' => $first_name,
        'last_name' => $last_name
    ], API_CREATED);
    
} catch (Exception $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    log_error("Registration error", ['email' => $email, 'error' => $e->getMessage()]);
    respond_json('error', 'Registration failed. Please try again.', null, API_SERVER_ERROR);
}
