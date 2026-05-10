<?php
/**
 * Read Tasks Endpoint
 * GET /api/tasks/read.php
 * 
 * Retrieves all tasks with optional filters, sorting, and pagination
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
    // Get database connection
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) {
        throw new Exception('Database connection unavailable');
    }
    
    // Get filter parameters
    $filters = [];
    
    // Status filter
    if (!empty($_GET['status'])) {
        $allowed_statuses = ['pending', 'in_progress', 'completed', 'on_hold'];
        if (in_array($_GET['status'], $allowed_statuses)) {
            $filters['status'] = $_GET['status'];
        } else {
            http_response_code(400);
            respond_json('error', 'Invalid status filter', null, 400);
        }
    }
    
    // Project filter
    if (!empty($_GET['project'])) {
        if (is_numeric($_GET['project'])) {
            $filters['project_id'] = intval($_GET['project']);
        } else {
            http_response_code(400);
            respond_json('error', 'Invalid project ID', null, 400);
        }
    }
    
    // Assignee filter
    if (!empty($_GET['assignee'])) {
        if ($_GET['assignee'] === 'me') {
            $filters['assignee'] = 'me';
            $filters['assignee_id'] = $_SESSION['user_id'];
        } else if (is_numeric($_GET['assignee'])) {
            $filters['assignee'] = intval($_GET['assignee']);
        } else {
            http_response_code(400);
            respond_json('error', 'Invalid assignee filter', null, 400);
        }
    }
    
    // Priority filter
    if (!empty($_GET['priority'])) {
        $allowed_priorities = ['low', 'medium', 'high'];
        if (in_array($_GET['priority'], $allowed_priorities)) {
            $filters['priority'] = $_GET['priority'];
        } else {
            http_response_code(400);
            respond_json('error', 'Invalid priority filter', null, 400);
        }
    }
    
    // Sort parameter
    if (!empty($_GET['sort'])) {
        $allowed_sorts = ['date', 'date_desc', 'priority', 'status'];
        if (in_array($_GET['sort'], $allowed_sorts)) {
            $filters['sort'] = $_GET['sort'];
        }
    }
    
    // Pagination
    $page = intval($_GET['page'] ?? 1);
    if ($page < 1) {
        $page = 1;
    }
    
    // Get tasks with filters
    $result = get_tasks_with_filters($pdo, $filters, $page);
    
    // Format response
    $response_data = [
        'tasks' => $result['tasks'],
        'metadata' => $result['metadata']
    ];
    
    http_response_code(200);
    respond_json('success', 'Tasks retrieved successfully', $response_data, 200);
    
} catch (PDOException $e) {
    log_error("Task read error", [
        'error' => $e->getMessage(),
        'user_id' => $_SESSION['user_id'] ?? null
    ]);
    
    http_response_code(500);
    respond_json('error', 'Database error: ' . $e->getMessage(), null, 500);
} catch (Exception $e) {
    log_error("Unexpected error in task read", ['error' => $e->getMessage()]);
    
    http_response_code(500);
    respond_json('error', 'An unexpected error occurred: ' . $e->getMessage(), null, 500);
}
