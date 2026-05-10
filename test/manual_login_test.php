<?php
/**
 * Manual login test via direct PHP call
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth_guard.php';

echo "<h1>Manual Login Test</h1>\n";

// Simulate login with test credentials
$email = 'freelancer1@test.com';
$password = 'Freelancer123';

// Find user
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password_hash'])) {
    echo "<p>✓ User found and password verified</p>\n";
    echo "<p>User: " . var_export($user, true) . "</p>\n";
    
    // Set session
    set_user_session($user);
    echo "<p>✓ Session set</p>\n";
    echo "<p>Session ID: " . session_id() . "</p>\n";
    echo "<p>Session Data: " . var_export($_SESSION, true) . "</p>\n";
    
    // Save session
    session_write_close();
    echo "<p>✓ Session written to disk</p>\n";
    
    // Verify write
    session_start();
    echo "<p>Session Data After Reopen: " . var_export($_SESSION, true) . "</p>\n";
    echo "<p>Is Authenticated: " . (is_authenticated() ? 'Yes' : 'No') . "</p>\n";
    
    // Check if function exists
    echo "<p>get_current_user exists: " . (function_exists('get_current_user') ? 'Yes' : 'No') . "</p>\n";
    
    // Get current user
    $current_user = get_current_user();
    echo "<p>Get Current User Type: " . gettype($current_user) . "</p>\n";
    echo "<p>Get Current User Value: " . var_export($current_user, true) . "</p>\n";
} else {
    echo "<p>✗ User not found or password incorrect</p>\n";
}
?>
