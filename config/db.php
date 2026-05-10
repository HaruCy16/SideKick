<?php
/**
 * Database Configuration and Connection
 * PDO-based MySQL connection with error handling
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'sidekick_db');
define('DB_PORT', '3306');

try {
    // Create DSN (Data Source Name)
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    
    // PDO connection options
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    // Establish PDO connection
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
    
    // Set timezone
    $pdo->exec("SET time_zone = '+00:00'");
    
} catch (PDOException $e) {
    // Log error instead of displaying
    error_log("Database connection error: " . $e->getMessage(), 3, __DIR__ . '/../logs/db_error.log');
    
    // Display user-friendly error message
    http_response_code(500);
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed. Please try again later.'
    ]));
}

// Make $pdo available to other includes
$GLOBALS['pdo'] = $pdo;
