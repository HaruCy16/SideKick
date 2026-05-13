<?php
/**
 * User Logout Endpoint
 * GET /api/auth/logout (redirects to login page)
 * POST /api/auth/logout (returns JSON response)
 * 
 * Destroys user session and logs out
 */

// Headers for API requests
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

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

// Handle both GET (from logout link) and POST (from API)
if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json; charset=utf-8');
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
    
    // If GET request (from logout link), redirect to login page
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        header('Location: /SideKick/public/login.php');
        exit;
    }
    
    // If POST request, return JSON
    header('Content-Type: application/json; charset=utf-8');
    respond_json('success', 'Logout successful. You have been logged out.', null, API_SUCCESS);
    
} catch (Exception $e) {
    log_error("Logout error", ['error' => $e->getMessage()]);
    
    // Return JSON for API calls, redirect for browser links
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        header('Location: /SideKick/public/login.php');
        exit;
    }
    
    header('Content-Type: application/json; charset=utf-8');
    respond_json('error', 'Logout failed', null, API_SERVER_ERROR);
}
