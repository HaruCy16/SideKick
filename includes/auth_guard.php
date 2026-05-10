<?php
/**
 * Authentication Guard Middleware
 * Checks if user is authenticated and session is valid
 */

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is authenticated
 * @param string $requiredRole Optional required role
 * @return array|bool User data if authenticated, false otherwise
 */
if (!function_exists('require_auth')) {
    function require_auth($requiredRole = null) {
        // Check if user is logged in
        if (empty($_SESSION['user_id']) || empty($_SESSION['user_role'])) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Unauthorized. Please log in.',
                'timestamp' => date('c')
            ]);
            exit;
        }
        
        // Check if session has expired
        if (!empty($_SESSION['session_created']) && (time() - $_SESSION['session_created']) > (SESSION_TIMEOUT * 60)) {
            session_destroy();
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Session expired. Please log in again.',
                'timestamp' => date('c')
            ]);
            exit;
        }
        
        // Check role requirement
        if ($requiredRole !== null && $_SESSION['user_role'] !== $requiredRole) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Forbidden. Insufficient permissions.',
                'timestamp' => date('c')
            ]);
            exit;
        }
        
        // Update session activity time
        $_SESSION['last_activity'] = time();
        
        // Return user data
        return [
            'user_id' => $_SESSION['user_id'],
            'email' => $_SESSION['email'] ?? null,
            'role' => $_SESSION['user_role'],
            'first_name' => $_SESSION['first_name'] ?? null,
            'last_name' => $_SESSION['last_name'] ?? null,
        ];
    }
}

/**
 * Check if user is authenticated (soft check, returns bool)
 * @return bool
 */
if (!function_exists('is_authenticated')) {
    function is_authenticated() {
        return !empty($_SESSION['user_id']) && !empty($_SESSION['user_role']);
    }
}

/**
 * Check if user has specific role
 * @param string $role Role to check
 * @return bool
 */
if (!function_exists('has_role')) {
    function has_role($role) {
        return !empty($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }
}

/**
 * Get logged-in user data
 * @return array|null
 */
if (!function_exists('get_auth_user')) {
    function get_auth_user() {
        if (!is_authenticated()) {
            return null;
        }
        
        return [
            'user_id' => $_SESSION['user_id'],
            'email' => $_SESSION['email'] ?? null,
            'role' => $_SESSION['user_role'],
            'first_name' => $_SESSION['first_name'] ?? null,
            'last_name' => $_SESSION['last_name'] ?? null,
        ];
    }
}

/**
 * Set user session data
 * @param array $userData User data to store in session
 */
if (!function_exists('set_user_session')) {
    function set_user_session($userData) {
        $_SESSION['user_id'] = $userData['user_id'] ?? null;
        $_SESSION['email'] = $userData['email'] ?? null;
        $_SESSION['user_role'] = $userData['role'] ?? null;
        $_SESSION['first_name'] = $userData['first_name'] ?? null;
        $_SESSION['last_name'] = $userData['last_name'] ?? null;
        $_SESSION['session_created'] = time();
        $_SESSION['last_activity'] = time();
        
        // Regenerate session ID for security
        session_regenerate_id(true);
    }
}

/**
 * Destroy user session
 */
if (!function_exists('destroy_session')) {
    function destroy_session() {
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
    }
}
