<?php
/**
 * Update Task Endpoint
 * PUT or POST /api/tasks/update.php
 * 
 * Updates an existing task
 * Permissions: 
 *   - Status update: Any team member (collaborative)
 *   - Full update: Only creator or admin
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth_guard.php';

// Accept PUT or POST
if (!in_array($_SERVER['REQUEST_METHOD'], ['PUT', 'POST'])) {
    http_response_code(405);
    respond_json('error', 'Method not allowed', null, 405);
}

// Verify user is authenticated
if (!is_authenticated()) {
    http_response_code(401);
    respond_json('error', 'Unauthorized. Please log in.', null, 401);
}

try {
    // Parse JSON body
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if ($data === null) {
        http_response_code(400);
        respond_json('error', 'Invalid JSON payload', null, 400);
    }
    
    // Get task ID
    $task_id = $data['task_id'] ?? $_GET['id'] ?? null;
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
    
    // Determine if this is a status-only update
    $is_status_only = (count($data) === 2 && isset($data['task_id']) && isset($data['status'])) || 
                      (count($data) === 1 && isset($data['status']));
    
    if ($is_status_only) {
        // Status-only update: allow any team member
        if (empty($data['status'])) {
            http_response_code(400);
            respond_json('error', 'Status is required', null, 400);
        }
        
        // Validate status
        $allowed_statuses = ['pending', 'in_progress', 'completed', 'on_hold'];
        if (!in_array($data['status'], $allowed_statuses)) {
            http_response_code(400);
            respond_json('error', 'Invalid status value', null, 400);
        }
        
        // Update status
        $stmt = $pdo->prepare("UPDATE task SET status = ?, updated_at = NOW() WHERE task_id = ?");
        $stmt->execute([$data['status'], $task_id]);
        
        // Log audit
        log_task_audit($task_id, $user_id, 'update_status', $task['status'], $data['status'], $pdo);
        
    } else {
        // Full update: only creator or admin
        if (!can_user_action_on_task($task, $user_id, $user_role, 'update_full')) {
            http_response_code(403);
            respond_json('error', 'Permission denied. Only task creator or admin can update task details.', null, 403);
        }
        
        // Validate all fields
        $validation = validate_task_data($data, true);
        if (!$validation['valid']) {
            http_response_code(400);
            respond_json('error', 'Validation failed', ['errors' => $validation['errors']], 400);
        }
        
        // Build update query dynamically for partial updates
        $update_fields = [];
        $bind_values = [];
        $updates = [];
        
        if (!empty($data['meeting_title'])) {
            $updates['meeting_title'] = sanitize_input($data['meeting_title']);
            $update_fields[] = "meeting_title = ?";
            $bind_values[] = $updates['meeting_title'];
        }
        
        if (isset($data['agenda'])) {
            $updates['agenda'] = !empty($data['agenda']) ? sanitize_input($data['agenda']) : null;
            $update_fields[] = "agenda = ?";
            $bind_values[] = $updates['agenda'];
        }
        
        if (isset($data['status'])) {
            $allowed_statuses = ['pending', 'in_progress', 'completed', 'on_hold'];
            if (in_array($data['status'], $allowed_statuses)) {
                $updates['status'] = $data['status'];
                $update_fields[] = "status = ?";
                $bind_values[] = $updates['status'];
            }
        }
        
        if (!empty($data['priority'])) {
            $allowed_priorities = ['low', 'medium', 'high'];
            if (in_array($data['priority'], $allowed_priorities)) {
                $updates['priority'] = $data['priority'];
                $update_fields[] = "priority = ?";
                $bind_values[] = $updates['priority'];
            }
        }
        
        if (!empty($data['scheduled_date'])) {
            $updates['scheduled_date'] = $data['scheduled_date'];
            $update_fields[] = "scheduled_date = ?";
            $bind_values[] = $updates['scheduled_date'];
        }
        
        if (!empty($data['freelancer_id'])) {
            // Verify assignee exists
            $stmt = $pdo->prepare("SELECT freelancer_id FROM freelancers WHERE freelancer_id = ?");
            $stmt->execute([intval($data['freelancer_id'])]);
            if ($stmt->rowCount() > 0) {
                $updates['freelancer_id'] = intval($data['freelancer_id']);
                $update_fields[] = "freelancer_id = ?";
                $bind_values[] = $updates['freelancer_id'];
            }
        }
        
        if (isset($data['notes'])) {
            $updates['notes'] = !empty($data['notes']) ? sanitize_input($data['notes']) : null;
            $update_fields[] = "notes = ?";
            $bind_values[] = $updates['notes'];
        }
        
        if (isset($data['meeting_type'])) {
            $updates['meeting_type'] = !empty($data['meeting_type']) ? sanitize_input($data['meeting_type']) : null;
            $update_fields[] = "meeting_type = ?";
            $bind_values[] = $updates['meeting_type'];
        }
        
        if (isset($data['platform'])) {
            $updates['platform'] = !empty($data['platform']) ? sanitize_input($data['platform']) : null;
            $update_fields[] = "platform = ?";
            $bind_values[] = $updates['platform'];
        }
        
        if (isset($data['meeting_link'])) {
            $updates['meeting_link'] = !empty($data['meeting_link']) ? sanitize_input($data['meeting_link']) : null;
            $update_fields[] = "meeting_link = ?";
            $bind_values[] = $updates['meeting_link'];
        }
        
        // Add updated_at
        $update_fields[] = "updated_at = NOW()";
        
        if (empty($updates)) {
            http_response_code(400);
            respond_json('error', 'No valid fields provided for update', null, 400);
        }
        
        // Execute update
        $bind_values[] = $task_id;
        $sql = "UPDATE task SET " . implode(", ", $update_fields) . " WHERE task_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($bind_values);
        
        // Log audit
        log_task_audit($task_id, $user_id, 'update', $task, $updates, $pdo);
    }
    
    // Get updated task
    $updated_task = get_task_by_id($task_id, $pdo);
    
    // Log activity
    log_error("Task updated", [
        'task_id' => $task_id,
        'user_id' => $user_id,
        'is_status_only' => $is_status_only
    ]);
    
    http_response_code(200);
    respond_json('success', 'Task updated successfully', $updated_task, 200);
    
} catch (PDOException $e) {
    log_error("Task update error", [
        'error' => $e->getMessage(),
        'user_id' => $_SESSION['user_id'] ?? null
    ]);
    
    http_response_code(500);
    respond_json('error', 'An error occurred while updating the task', null, 500);
} catch (Exception $e) {
    log_error("Unexpected error in task update", ['error' => $e->getMessage()]);
    
    http_response_code(500);
    respond_json('error', 'An unexpected error occurred', null, 500);
}
