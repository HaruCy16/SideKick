<?php
/**
 * Delete Task Endpoint
 * DELETE or POST /api/tasks/delete.php?id=X
 * 
 * Deletes a task
 * Permissions: Only creator or admin can delete
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth_guard.php';

// Accept DELETE or POST
if (!in_array($_SERVER['REQUEST_METHOD'], ['DELETE', 'POST'])) {
    http_response_code(405);
    respond_json('error', 'Method not allowed', null, 405);
}

// Verify user is authenticated
if (!is_authenticated()) {
    http_response_code(401);
    respond_json('error', 'Unauthorized. Please log in.', null, 401);
}

try {
    // Parse body if POST, else get from URL
    $task_id = null;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $task_id = $data['task_id'] ?? null;
    }
    
    // Try GET parameter if not found in body
    if (empty($task_id)) {
        $task_id = $_GET['id'] ?? $_GET['task_id'] ?? null;
    }
    
    if (empty($task_id) || !is_numeric($task_id)) {
        http_response_code(400);
        respond_json('error', 'Task ID is required and must be numeric', null, 400);
    }
    
    $task_id = intval($task_id);
    
    // Get database connection
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) {
        throw new Exception('Database connection unavailable');
    }
    
    // Get current task
    $task = get_task_by_id($task_id, $pdo);
    if (!$task) {
        http_response_code(404);
        respond_json('error', 'Task not found', null, 404);
    }
    
    // Get current user info
    $user_id = $_SESSION['user_id'];
    $user_role = $_SESSION['user_role'];
    
    // Check permission
    if (!can_user_action_on_task($task, $user_id, $user_role, 'delete')) {
        http_response_code(403);
        respond_json('error', 'Permission denied. Only task creator or admin can delete this task.', null, 403);
    }
    
    // Use transaction for safe deletion
    $pdo->beginTransaction();
    
    try {
        // Check for dependent time log records
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM time_log WHERE task_id = ?");
        $stmt->execute([$task_id]);
        $has_logs = $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
        
        // If has time logs, delete them first (cascade)
        if ($has_logs) {
            $stmt = $pdo->prepare("DELETE FROM time_log WHERE task_id = ?");
            $stmt->execute([$task_id]);
        }
        
        // Delete the task
        $stmt = $pdo->prepare("DELETE FROM task WHERE task_id = ?");
        $stmt->execute([$task_id]);
        
        $pdo->commit();
        
        // Log audit - store the entire task for recovery
        log_task_audit($task_id, $user_id, 'delete', $task, null, $pdo);
        
        // Log activity
        log_error("Task deleted", [
            'task_id' => $task_id,
            'user_id' => $user_id,
            'task_title' => $task['meeting_title'],
            'had_time_logs' => $has_logs
        ]);
        
        http_response_code(200);
        respond_json('success', 'Task deleted successfully', ['task_id' => $task_id], 200);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
    
} catch (PDOException $e) {
    log_error("Task delete error", [
        'error' => $e->getMessage(),
        'user_id' => $_SESSION['user_id'] ?? null,
        'task_id' => $task_id ?? null
    ]);
    
    http_response_code(500);
    respond_json('error', 'An error occurred while deleting the task', null, 500);
} catch (Exception $e) {
    log_error("Unexpected error in task delete", ['error' => $e->getMessage()]);
    
    http_response_code(500);
    respond_json('error', 'An unexpected error occurred', null, 500);
}
