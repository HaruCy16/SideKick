<?php
/**
 * Create Task Endpoint
 * POST /api/tasks/create.php
 * 
 * Creates a new task/meeting
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth_guard.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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
    
    // Validate input data
    $validation = validate_task_data($data, false);
    if (!$validation['valid']) {
        http_response_code(400);
        respond_json('error', 'Validation failed', ['errors' => $validation['errors']], 400);
    }
    
    // Get database connection
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) {
        throw new Exception('Database connection unavailable');
    }
    
    // Get current user
    $user_id = $_SESSION['user_id'];
    $user_role = $_SESSION['user_role'];
    
    // Verify project exists and user has permission to create tasks
    $project_id = intval($data['project_id']);
    $stmt = $pdo->prepare("SELECT * FROM project WHERE project_id = ?");
    $stmt->execute([$project_id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$project) {
        http_response_code(404);
        respond_json('error', 'Project not found', null, 404);
    }
    
    // Check permission: user must be project creator, admin, or client
    $can_create = (
        $user_role === 'admin' ||
        $project['freelancer_id'] == $user_id ||
        $project['client_id'] == $user_id
    );
    
    if (!$can_create) {
        http_response_code(403);
        respond_json('error', 'Permission denied. You cannot create tasks for this project.', null, 403);
    }
    
    // Verify assignee exists
    $assignee_id = intval($data['freelancer_id']);
    $stmt = $pdo->prepare("SELECT freelancer_id FROM freelancers WHERE freelancer_id = ?");
    $stmt->execute([$assignee_id]);
    if ($stmt->rowCount() === 0) {
        http_response_code(400);
        respond_json('error', 'Invalid assignee. Freelancer not found.', null, 400);
    }
    
    // Verify client exists
    $client_id = intval($data['client_id']);
    $stmt = $pdo->prepare("SELECT client_id FROM client WHERE client_id = ?");
    $stmt->execute([$client_id]);
    if ($stmt->rowCount() === 0) {
        http_response_code(400);
        respond_json('error', 'Invalid client. Client not found.', null, 400);
    }
    
    // Prepare task data
    $meeting_title = sanitize_input($data['meeting_title']);
    $agenda = !empty($data['agenda']) ? sanitize_input($data['agenda']) : null;
    $status = $data['status'] ?? 'pending';
    $priority = $data['priority'] ?? 'medium';
    $scheduled_date = $data['scheduled_date'];
    $meeting_type = !empty($data['meeting_type']) ? sanitize_input($data['meeting_type']) : null;
    $platform = !empty($data['platform']) ? sanitize_input($data['platform']) : null;
    $meeting_link = !empty($data['meeting_link']) ? sanitize_input($data['meeting_link']) : null;
    $notes = !empty($data['notes']) ? sanitize_input($data['notes']) : null;
    
    // Create task
    $stmt = $pdo->prepare("
        INSERT INTO task 
        (project_id, client_id, freelancer_id, meeting_title, agenda, status, priority, 
         scheduled_date, meeting_type, platform, meeting_link, notes, created_by, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    
    $stmt->execute([
        $project_id,
        $client_id,
        $assignee_id,
        $meeting_title,
        $agenda,
        $status,
        $priority,
        $scheduled_date,
        $meeting_type,
        $platform,
        $meeting_link,
        $notes,
        $user_id
    ]);
    
    // Get created task
    $task_id = $pdo->lastInsertId();
    $created_task = get_task_by_id($task_id, $pdo);
    
    // Log audit event
    log_task_audit($task_id, $user_id, 'create', null, $created_task, $pdo);
    
    // Log activity
    log_error("Task created", [
        'task_id' => $task_id,
        'user_id' => $user_id,
        'project_id' => $project_id,
        'title' => $meeting_title
    ]);
    
    http_response_code(201);
    respond_json('success', 'Task created successfully', $created_task, 201);
    
} catch (PDOException $e) {
    log_error("Task creation error", [
        'error' => $e->getMessage(),
        'user_id' => $_SESSION['user_id'] ?? null
    ]);
    
    http_response_code(500);
    respond_json('error', 'An error occurred while creating the task', null, 500);
} catch (Exception $e) {
    log_error("Unexpected error in task creation", ['error' => $e->getMessage()]);
    
    http_response_code(500);
    respond_json('error', 'An unexpected error occurred', null, 500);
}
