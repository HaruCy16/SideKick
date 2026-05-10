<?php
/**
 * Read Single Task Endpoint
 * GET /api/tasks/read_single.php?id=X
 * 
 * Retrieves a single task by ID
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth_guard.php';

// Only accept GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    respond_json('error', 'Method not allowed', null, 405);
}

// Verify user is authenticated
if (!is_authenticated()) {
    http_response_code(401);
    respond_json('error', 'Unauthorized. Please log in.', null, 401);
}

try {
    // Get task ID from query parameter
    $task_id = $_GET['id'] ?? $_GET['task_id'] ?? null;
    
    if (empty($task_id)) {
        http_response_code(400);
        respond_json('error', 'Task ID is required', null, 400);
    }
    
    if (!is_numeric($task_id)) {
        http_response_code(400);
        respond_json('error', 'Invalid task ID format', null, 400);
    }
    
    // Get database connection
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) {
        throw new Exception('Database connection unavailable');
    }
    
    // Get task from database
    $task = get_task_by_id(intval($task_id), $pdo);
    
    if (!$task) {
        http_response_code(404);
        respond_json('error', 'Task not found', null, 404);
    }
    
    http_response_code(200);
    respond_json('success', 'Task retrieved successfully', $task, 200);
    
} catch (PDOException $e) {
    log_error("Task read error", [
        'error' => $e->getMessage(),
        'user_id' => $_SESSION['user_id'] ?? null,
        'task_id' => $task_id ?? null
    ]);
    
    http_response_code(500);
    respond_json('error', 'An error occurred while retrieving the task', null, 500);
} catch (Exception $e) {
    log_error("Unexpected error in task read", ['error' => $e->getMessage()]);
    
    http_response_code(500);
    respond_json('error', 'An unexpected error occurred', null, 500);
}
