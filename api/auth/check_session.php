<?php
/**
 * Check Session Endpoint
 * GET /api/auth/check_session
 * 
 * Verifies if user is logged in and returns current user data
 */

// Headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');

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

// Only accept GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    respond_json('error', 'Method not allowed', null, API_BAD_REQUEST);
}

try {
    // Check if user is authenticated
    $user = get_current_user();
    
    if (!$user) {
        respond_json('error', 'Not authenticated', null, API_UNAUTHORIZED);
    }
    
    // Return user data
    respond_json('success', 'Session is active', $user, API_SUCCESS);
    
} catch (Exception $e) {
    log_error("Check session error", ['error' => $e->getMessage()]);
    respond_json('error', 'Session check failed', null, API_SERVER_ERROR);
}
