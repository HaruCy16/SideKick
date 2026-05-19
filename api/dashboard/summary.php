<?php
require_once '../../config/config.php';
require_once '../../config/db.php';
require_once '../../includes/auth_guard.php';

// Use PDO connection - access from globals if needed
$db = $GLOBALS['pdo'] ?? null;

// Verify database connection exists
if (!$db) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Ensure user is authenticated
if (!is_authenticated()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

try {
    // Initialize response data
    $response = [
        'success' => true,
        'stats' => [],
        'projects' => [],
        'my_tasks' => [],
        'recent_activity' => []
    ];

    // STATS - Role specific
    if ($user_role === 'admin') {
        // Admin stats: total users, projects, tasks, revenue
        $response['stats'] = [
            'total_users' => (int) $db->query("SELECT COUNT(*) as count FROM users")->fetch(PDO::FETCH_ASSOC)['count'],
            'total_projects' => (int) $db->query("SELECT COUNT(*) as count FROM project")->fetch(PDO::FETCH_ASSOC)['count'],
            'total_tasks' => (int) $db->query("SELECT COUNT(*) as count FROM task")->fetch(PDO::FETCH_ASSOC)['count'],
            'total_revenue' => 125500, // Placeholder - would aggregate from invoices
        ];
    } elseif ($user_role === 'manager') {
        // Project Manager stats: projects, tasks, team members, hours
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM project WHERE manager_id = ?");
        $stmt->execute([$user_id]);
        $total_projects = (int) $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        $total_tasks = 150; // Placeholder
        $total_team = 12;   // Placeholder
        $total_hours = 1233; // Placeholder

        $response['stats'] = [
            'total_projects' => $total_projects,
            'total_tasks' => $total_tasks,
            'total_team' => $total_team,
            'total_hours' => $total_hours,
        ];
    } elseif ($user_role === 'freelancer') {
        // Freelancer stats: active tasks, completed, hours this week, earnings
        $response['stats'] = [
            'active_tasks' => 5,
            'completed_tasks' => 24,
            'hours_this_week' => 40,
            'earnings' => 2450,
        ];
    } elseif ($user_role === 'client') {
        // Client stats: posted projects, active, pending tasks, budget used
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM project WHERE client_id = ?");
        $stmt->execute([$user_id]);
        $posted_projects = (int) $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        $response['stats'] = [
            'posted_projects' => $posted_projects,
            'active_projects' => 3,
            'pending_tasks' => 34,
            'budget_used' => 12450,
        ];
    }

    // RECENT PROJECTS - Limit to 4
    $stmt = $db->prepare("
        SELECT p.project_id as id, p.project_name as name, p.status, p.priority 
        FROM project p 
        WHERE p.status IN ('active', 'pending')
        ORDER BY p.date_created DESC 
        LIMIT 4
    ");
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $project_colors = ['#8B5CF6', '#14B8A6', '#EC4899', '#F59E0B'];
    foreach ($projects as $index => $project) {
        $response['projects'][] = [
            'id' => $project['id'],
            'name' => $project['name'],
            'status' => $project['status'],
            'progress_percent' => 65,
            'color' => $project_colors[$index % 4]
        ];
    }

    // MY TASKS - User's assigned tasks, limit to 5
    $stmt = $db->prepare("
        SELECT t.task_id as id, t.meeting_title as title, t.status, t.scheduled_date as due_date, p.project_name
        FROM task t
        LEFT JOIN project p ON t.project_id = p.project_id
        WHERE (t.freelancer_id = ? OR t.client_id = ?) AND t.status != 'completed'
        ORDER BY t.scheduled_date ASC
        LIMIT 5
    ");
    $stmt->execute([$user_id, $user_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($tasks as $task) {
        if (!$task['due_date']) continue;
        
        $due_date = new DateTime($task['due_date']);
        $today = new DateTime();
        $tomorrow = (new DateTime())->modify('+1 day');
        
        if ($due_date->format('Y-m-d') === $today->format('Y-m-d')) {
            $due_label = 'Today';
        } elseif ($due_date->format('Y-m-d') === $tomorrow->format('Y-m-d')) {
            $due_label = 'Tomorrow';
        } else {
            $due_label = $due_date->format('M d');
        }

        $response['my_tasks'][] = [
            'id' => $task['id'],
            'name' => $task['title'],
            'priority' => 'Medium',
            'due_date' => $due_label,
            'project_name' => $task['project_name'] ?? 'Unassigned'
        ];
    }

    // RECENT ACTIVITY - Last 10 activities, limit to 5 for display
    $stmt = $db->prepare("
        SELECT u.id, u.first_name, u.last_name, n.type as action, n.subject, n.created_at
        FROM notification n
        JOIN users u ON n.user_id = u.id
        ORDER BY n.created_at DESC
        LIMIT 10
    ");
    $stmt->execute();
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $avatar_colors = ['#8B5CF6', '#EC4899', '#14B8A6', '#F59E0B', '#3B82F6', '#EF4444'];
    $color_index = 0;

    foreach (array_slice($activities, 0, 5) as $activity) {
        $initials = strtoupper($activity['first_name'][0] . $activity['last_name'][0]);
        $time_ago = get_time_ago($activity['created_at']);

        $response['recent_activity'][] = [
            'user_id' => $activity['id'],
            'user_name' => $activity['first_name'] . ' ' . $activity['last_name'],
            'initials' => $initials,
            'avatar_color' => $avatar_colors[$color_index++ % 6],
            'action' => $activity['action'],
            'target' => $activity['subject'],
            'timestamp' => $time_ago
        ];
    }

    http_response_code(200);
    echo json_encode($response);

} catch (PDOException $e) {
    error_log("Dashboard Summary Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching dashboard data',
        'error' => defined('DEBUG_MODE') && DEBUG_MODE ? $e->getMessage() : null
    ]);
} catch (Exception $e) {
    error_log("Dashboard Summary Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching dashboard data',
        'error' => defined('DEBUG_MODE') && DEBUG_MODE ? $e->getMessage() : null
    ]);
}

// Helper function to format time ago
function get_time_ago($date_string) {
    $time = strtotime($date_string);
    $now = time();
    $diff = $now - $time;

    if ($diff < 60) {
        return 'just now';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . 'm ago';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . 'h ago';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . 'd ago';
    } else {
        return date('M d', $time);
    }
}
