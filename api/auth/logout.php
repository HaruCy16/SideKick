<?php
/**
 * User Logout Endpoint
 * POST /api/auth/logout
 * 
 * Destroys user session and logs out
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

try {
    // Check if user is authenticated before logging out
    $user = get_auth_user();
    
    if ($user) {
        log_error("User logout", ['user_id' => $user['user_id'], 'email' => $user['email']]);
    }
    
    // Destroy session
    destroy_session();
    
    respond_json('success', 'Logout successful. You have been logged out.', null, API_SUCCESS);
    
} catch (Exception $e) {
    log_error("Logout error", ['error' => $e->getMessage()]);
    respond_json('error', 'Logout failed', null, API_SERVER_ERROR);
}
