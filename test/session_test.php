<?php
/**
 * Session persistence test
 */

require_once __DIR__ . '/../config/config.php';

// Test session data
echo "<h1>Session Test</h1>\n";
echo "<p>Session ID: " . session_id() . "</p>\n";
echo "<p>Session Status: " . session_status() . "</p>\n";
echo "<p>Session Name: " . session_name() . "</p>\n";

// Check if sessions directory exists and is writable
$sessionPath = __DIR__ . '/../sessions/';
echo "<p>Session Path: " . $sessionPath . "</p>\n";
echo "<p>Session Dir Exists: " . (is_dir($sessionPath) ? 'Yes' : 'No') . "</p>\n";
echo "<p>Session Dir Writable: " . (is_writable($sessionPath) ? 'Yes' : 'No') . "</p>\n";

// Try to create a test session
$_SESSION['test_key'] = 'test_value_' . time();
session_write_close();

// Reopen session to verify write
session_start();
echo "<p>Session Data After Reopen: " . var_export($_SESSION, true) . "</p>\n";

// Check session file
$sessionFiles = glob($sessionPath . 'sess_*');
echo "<p>Session Files Count: " . count($sessionFiles) . "</p>\n";
if (!empty($sessionFiles)) {
    foreach ($sessionFiles as $file) {
        echo "<p>Session File: " . basename($file) . " (Size: " . filesize($file) . " bytes)</p>\n";
    }
}
?>
