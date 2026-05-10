<?php
/**
 * Helper Functions
 * Utility functions for common operations
 */

/**
 * Sanitize user input
 * @param string $input The input to sanitize
 * @return string Sanitized input
 */
function sanitize_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Send JSON response
 * @param string $status success or error
 * @param string $message Response message
 * @param mixed $data Optional data to include
 * @param int $httpCode HTTP status code
 */
function respond_json($status, $message, $data = null, $httpCode = 200) {
    http_response_code($httpCode);
    header('Content-Type: application/json; charset=utf-8');
    
    $response = [
        'success' => $status === 'success' ? true : false,
        'message' => $message,
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    $response['timestamp'] = date('c');
    
    echo json_encode($response);
    exit;
}

/**
 * Validate email address
 * @param string $email Email to validate
 * @return bool
 */
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Generate CSRF token
 * @return string CSRF token
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * @param string $token Token to verify
 * @return bool
 */
function verify_csrf_token($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Hash password using bcrypt
 * @param string $password Password to hash
 * @return string|false Hashed password or false on failure
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password against hash
 * @param string $password Plain password
 * @param string $hash Password hash
 * @return bool
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * HTTP redirect
 * @param string $url URL to redirect to
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

/**
 * Log error to file
 * @param string $message Error message
 * @param array $context Additional context
 */
function log_error($message, $context = []) {
    $timestamp = date(DATETIME_FORMAT);
    $logMessage = "[$timestamp] $message";
    
    if (!empty($context)) {
        $logMessage .= " | Context: " . json_encode($context);
    }
    
    error_log($logMessage . PHP_EOL, 3, ERROR_LOG_FILE);
}

/**
 * Get user by ID from database
 * @param int $id User ID
 * @param PDO $pdo Database connection
 * @return array|false User data or false
 */
function get_user_by_id($id, $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        log_error("Error fetching user by ID", ['user_id' => $id, 'error' => $e->getMessage()]);
        return false;
    }
}

/**
 * Check if user is admin
 * @return bool
 */
function is_admin() {
    return !empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Check if user is freelancer
 * @return bool
 */
function is_freelancer() {
    return !empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'freelancer';
}

/**
 * Check if user is client
 * @return bool
 */
function is_client() {
    return !empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'client';
}

/**
 * Check if user is manager
 * @return bool
 */
function is_manager() {
    return !empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'manager';
}

/**
 * Format currency value
 * @param float $amount Amount to format
 * @return string Formatted currency
 */
function format_currency($amount) {
    return CURRENCY_SYMBOL . number_format($amount, 2);
}

/**
 * Format date
 * @param string $date Date string
 * @param string $format Format pattern
 * @return string Formatted date
 */
function format_date($date, $format = DATE_FORMAT) {
    return date($format, strtotime($date));
}

/**
 * Validate password strength
 * @param string $password Password to validate
 * @return array Validation result with 'valid' bool and 'errors' array
 */
function validate_password_strength($password) {
    $errors = [];
    
    if (strlen($password) < PASSWORD_MIN_LENGTH) {
        $errors[] = "Password must be at least " . PASSWORD_MIN_LENGTH . " characters long";
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter";
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain at least one lowercase letter";
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one number";
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

/**
 * Generate unique invoice number
 * @return string Invoice number
 */
function generate_invoice_number() {
    return 'INV-' . date('Y-m-d-His') . '-' . strtoupper(uniqid());
}

/**
 * Get user profile data
 * @param int $userId User ID
 * @param PDO $pdo Database connection
 * @return array|false Profile data or false
 */
function get_user_profile($userId, $pdo) {
    try {
        $user = get_user_by_id($userId, $pdo);
        if (!$user) {
            return false;
        }
        
        // Get role-specific profile
        $role = $user['role'];
        
        if ($role === 'freelancer') {
            $stmt = $pdo->prepare("SELECT * FROM freelancers WHERE user_id = ?");
        } elseif ($role === 'client') {
            $stmt = $pdo->prepare("SELECT * FROM client WHERE user_id = ?");
        } elseif ($role === 'manager') {
            $stmt = $pdo->prepare("SELECT * FROM project_manager WHERE user_id = ?");
        } else {
            return $user;
        }
        
        $stmt->execute([$userId]);
        $profile = $stmt->fetch();
        
        return array_merge($user, $profile ?? []);
    } catch (Exception $e) {
        log_error("Error fetching user profile", ['user_id' => $userId, 'error' => $e->getMessage()]);
        return false;
    }
}

/**
 * Validate task creation/update data
 * @param array $data Task data to validate
 * @param bool $is_update Whether this is an update operation
 * @return array ['valid' => bool, 'errors' => array]
 */
function validate_task_data($data, $is_update = false) {
    $errors = [];
    
    // For create operation, check required fields
    if (!$is_update) {
        if (empty($data['meeting_title'])) {
            $errors[] = "Task title (meeting_title) is required";
        }
        if (empty($data['project_id'])) {
            $errors[] = "Project ID is required";
        }
        if (empty($data['client_id'])) {
            $errors[] = "Client ID is required";
        }
        if (empty($data['freelancer_id'])) {
            $errors[] = "Assignee (freelancer_id) is required";
        }
        if (empty($data['scheduled_date'])) {
            $errors[] = "Scheduled date is required";
        }
    }
    
    // Validate meeting_title if provided
    if (!empty($data['meeting_title'])) {
        $title = trim($data['meeting_title']);
        if (strlen($title) < 3) {
            $errors[] = "Task title must be at least 3 characters";
        }
        if (strlen($title) > 255) {
            $errors[] = "Task title must not exceed 255 characters";
        }
    }
    
    // Validate status if provided
    if (!empty($data['status'])) {
        $allowed_statuses = ['pending', 'in_progress', 'completed', 'on_hold'];
        if (!in_array($data['status'], $allowed_statuses)) {
            $errors[] = "Invalid status. Must be one of: " . implode(', ', $allowed_statuses);
        }
    }
    
    // Validate priority if provided
    if (!empty($data['priority'])) {
        $allowed_priorities = ['low', 'medium', 'high'];
        if (!in_array($data['priority'], $allowed_priorities)) {
            $errors[] = "Invalid priority. Must be one of: " . implode(', ', $allowed_priorities);
        }
    }
    
    // Validate numeric IDs if provided
    if (!empty($data['project_id']) && !is_numeric($data['project_id'])) {
        $errors[] = "Project ID must be numeric";
    }
    if (!empty($data['client_id']) && !is_numeric($data['client_id'])) {
        $errors[] = "Client ID must be numeric";
    }
    if (!empty($data['freelancer_id']) && !is_numeric($data['freelancer_id'])) {
        $errors[] = "Assignee ID must be numeric";
    }
    
    // Validate scheduled_date if provided
    if (!empty($data['scheduled_date'])) {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $data['scheduled_date']);
        if ($date === false) {
            $errors[] = "Invalid scheduled date format. Use: YYYY-MM-DD HH:MM:SS";
        }
    }
    
    // Validate text fields length if provided
    if (!empty($data['agenda']) && strlen($data['agenda']) > 2000) {
        $errors[] = "Agenda must not exceed 2000 characters";
    }
    if (!empty($data['notes']) && strlen($data['notes']) > 2000) {
        $errors[] = "Notes must not exceed 2000 characters";
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

/**
 * Get task by ID with enriched data (JOINs)
 * @param int $task_id Task ID
 * @param PDO $pdo Database connection
 * @return array|false Task data or false
 */
function get_task_by_id($task_id, $pdo) {
    if (!is_numeric($task_id)) {
        return false;
    }
    
    $sql = "SELECT 
        t.*,
        f.first_name as assignee_first_name,
        f.last_name as assignee_last_name,
        SUBSTR(f.first_name, 1, 1) as assignee_avatar_initial,
        p.project_name,
        p.freelancer_id as project_creator_id,
        COALESCE(fl.first_name, c.first_name, pm.first_name, '') as creator_first_name,
        COALESCE(fl.last_name, c.last_name, pm.last_name, '') as creator_last_name
    FROM task t
    LEFT JOIN freelancers f ON t.freelancer_id = f.freelancer_id
    LEFT JOIN project p ON t.project_id = p.project_id
    LEFT JOIN users u ON t.created_by = u.user_id
    LEFT JOIN freelancers fl ON u.user_id = fl.user_id
    LEFT JOIN client c ON u.user_id = c.user_id
    LEFT JOIN project_manager pm ON u.user_id = pm.user_id
    WHERE t.task_id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$task_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get all tasks with filters, sorting, and pagination
 * @param PDO $pdo Database connection
 * @param array $filters Filter parameters
 * @param int $page Page number
 * @return array ['tasks' => [...], 'metadata' => {...}]
 */
function get_tasks_with_filters($pdo, $filters = [], $page = 1) {
    // Build WHERE clauses
    $where_clauses = [];
    $bind_values = [];
    
    if (!empty($filters['status'])) {
        $where_clauses[] = "t.status = ?";
        $bind_values[] = $filters['status'];
    }
    
    if (!empty($filters['project_id'])) {
        $where_clauses[] = "t.project_id = ?";
        $bind_values[] = $filters['project_id'];
    }
    
    if (!empty($filters['assignee'])) {
        if ($filters['assignee'] === 'me') {
            // This will be set by the API endpoint with actual user_id
            $where_clauses[] = "t.freelancer_id = ?";
            $bind_values[] = $filters['assignee_id'] ?? $_SESSION['user_id'] ?? 0;
        } else if (is_numeric($filters['assignee'])) {
            $where_clauses[] = "t.freelancer_id = ?";
            $bind_values[] = $filters['assignee'];
        }
    }
    
    if (!empty($filters['priority'])) {
        $where_clauses[] = "t.priority = ?";
        $bind_values[] = $filters['priority'];
    }
    
    $where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";
    
    // Get total count
    $count_sql = "SELECT COUNT(*) as total FROM task t $where_sql";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute($bind_values);
    $total_count = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Determine sort order
    $sort_by = $filters['sort'] ?? 'date';
    $order_sql = match($sort_by) {
        'date_desc' => "ORDER BY t.scheduled_date DESC",
        'priority' => "ORDER BY FIELD(t.priority, 'high', 'medium', 'low')",
        'status' => "ORDER BY t.status ASC",
        default => "ORDER BY t.scheduled_date ASC"
    };
    
    // Pagination
    $page = max(1, intval($page));
    $per_page = 10;
    $offset = ($page - 1) * $per_page;
    
    // Get tasks
    $sql = "SELECT 
        t.*,
        f.first_name as assignee_first_name,
        f.last_name as assignee_last_name,
        SUBSTR(f.first_name, 1, 1) as assignee_avatar_initial,
        p.project_name,
        COALESCE(fl.first_name, c.first_name, pm.first_name, '') as creator_first_name,
        COALESCE(fl.last_name, c.last_name, pm.last_name, '') as creator_last_name
    FROM task t
    LEFT JOIN freelancers f ON t.freelancer_id = f.freelancer_id
    LEFT JOIN project p ON t.project_id = p.project_id
    LEFT JOIN users u ON t.created_by = u.user_id
    LEFT JOIN freelancers fl ON u.user_id = fl.user_id
    LEFT JOIN client c ON u.user_id = c.user_id
    LEFT JOIN project_manager pm ON u.user_id = pm.user_id
    $where_sql
    $order_sql
    LIMIT ? OFFSET ?";
    
    $stmt = $pdo->prepare($sql);
    $bind_values[] = $per_page;
    $bind_values[] = $offset;
    $stmt->execute($bind_values);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate pagination metadata
    $total_pages = ceil($total_count / $per_page);
    
    return [
        'tasks' => $tasks,
        'metadata' => [
            'total_count' => intval($total_count),
            'current_page' => $page,
            'per_page' => $per_page,
            'total_pages' => $total_pages,
            'has_next_page' => $page < $total_pages,
            'has_prev_page' => $page > 1
        ]
    ];
}

/**
 * Check if user can perform action on task
 * @param array $task Task data
 * @param int $user_id Current user ID
 * @param string $user_role Current user role
 * @param string $action The action: 'create', 'update_full', 'update_status', 'delete'
 * @return bool
 */
function can_user_action_on_task($task, $user_id, $user_role, $action = 'update_full') {
    if ($user_role === 'admin') {
        return true; // Admins can do everything
    }
    
    switch ($action) {
        case 'update_status':
            return true; // Anyone can update status (collaborative)
            
        case 'update_full':
        case 'delete':
            // Only creator or admin
            return $task['created_by'] == $user_id;
            
        case 'create':
            // Check project ownership
            return true; // Will be checked in endpoint
            
        default:
            return false;
    }
}

/**
 * Log audit event for task operations
 * @param int $task_id Task ID
 * @param int $user_id User ID
 * @param string $action Action (create, update, delete)
 * @param mixed $old_value Previous value (for updates)
 * @param mixed $new_value New value
 * @param PDO $pdo Database connection
 */
function log_task_audit($task_id, $user_id, $action, $old_value, $new_value, $pdo) {
    try {
        $stmt = $pdo->prepare("INSERT INTO audit_log (task_id, user_id, action, old_value, new_value) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $task_id,
            $user_id,
            $action,
            is_array($old_value) ? json_encode($old_value) : $old_value,
            is_array($new_value) ? json_encode($new_value) : $new_value
        ]);
    } catch (Exception $e) {
        log_error("Audit logging failed", ['error' => $e->getMessage()]);
    }
}

