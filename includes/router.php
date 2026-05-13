<?php
/**
 * Role-Based Routing System
 * Handles redirection based on user role and authentication status
 */

// Load configuration first (config.php will start the session)
require_once __DIR__ . '/../config/config.php';

/**
 * Redirect to dashboard based on user role
 * @param string $role User role
 */
function redirectToDashboard($role) {
    $dashboards = [
        'admin' => '/SideKick/admin/dashboard.php',
        'manager' => '/SideKick/project_manager/dashboard.php',
        'freelancer' => '/SideKick/freelancer/dashboard.php',
        'client' => '/SideKick/client/dashboard.php'
    ];
    
    $dashboard = $dashboards[$role] ?? '/SideKick/public/dashboard.php';
    header("Location: " . $dashboard);
    exit;
}

/**
 * Check if user is authenticated
 * @return bool
 */
function isAuthenticated() {
    return !empty($_SESSION['user_id']) && !empty($_SESSION['user_role']);
}

/**
 * Get user role
 * @return string|null
 */
function getUserRole() {
    return $_SESSION['user_role'] ?? null;
}

/**
 * Require authentication
 * Redirect to login if not authenticated
 */
function requireAuth() {
    if (!isAuthenticated()) {
        header("Location: /SideKick/public/login.php");
        exit;
    }
}

/**
 * Require specific role
 * @param string $role Required role
 */
function requireRole($role) {
    requireAuth();
    
    if (getUserRole() !== $role) {
        http_response_code(403);
        header("Location: /SideKick/public/403.php");
        exit;
    }
}

/**
 * Redirect to appropriate dashboard
 * Used for auto-routing after login
 */
function routeByRole() {
    if (!isAuthenticated()) {
        header("Location: /SideKick/public/login.php");
        exit;
    }
    
    redirectToDashboard(getUserRole());
}
?>
