<?php
/**
 * Task API Endpoint Test Suite
 * Tests all CRUD endpoints
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

echo "<h1>Task API Endpoint Test Suite</h1>\n";
echo "<p>This page shows test URLs for all task endpoints. Use Postman or curl to test them.</p>\n";

$base_url = "http://localhost/SideKick";
$task_id_sample = 1;

// First, get a sample task from the database
$stmt = $pdo->query("SELECT task_id FROM task LIMIT 1");
$task = $stmt->fetch(PDO::FETCH_ASSOC);
if ($task) {
    $task_id_sample = $task['task_id'];
}

echo "<h2>Available Endpoints</h2>\n";

// CREATE endpoint
echo "<h3>1. CREATE Task (POST)</h3>\n";
echo "<pre><code>";
echo "POST $base_url/api/tasks/create.php\n";
echo "Content-Type: application/json\n\n";
echo json_encode([
    'meeting_title' => 'New Test Task',
    'agenda' => 'Test agenda for the new task',
    'project_id' => 1,
    'client_id' => 1,
    'freelancer_id' => 1,
    'scheduled_date' => '2026-05-25 14:00:00',
    'status' => 'pending',
    'priority' => 'medium',
    'meeting_type' => 'meeting',
    'platform' => 'Zoom',
    'meeting_link' => 'https://zoom.us/j/123456',
    'notes' => 'Test notes'
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
echo "</code></pre>\n";

// READ (list) endpoint
echo "<h3>2. READ Tasks (GET) - With Various Filters</h3>\n";
echo "<ul>\n";
echo "<li><code>GET $base_url/api/tasks/read.php</code> - Get all tasks</li>\n";
echo "<li><code>GET $base_url/api/tasks/read.php?status=pending</code> - Filter by status</li>\n";
echo "<li><code>GET $base_url/api/tasks/read.php?assignee=me</code> - Get current user's tasks</li>\n";
echo "<li><code>GET $base_url/api/tasks/read.php?project=1</code> - Get tasks for project 1</li>\n";
echo "<li><code>GET $base_url/api/tasks/read.php?priority=high</code> - Get high priority tasks</li>\n";
echo "<li><code>GET $base_url/api/tasks/read.php?status=pending&priority=high</code> - Combined filters</li>\n";
echo "<li><code>GET $base_url/api/tasks/read.php?sort=date_desc</code> - Sort by date (desc)</li>\n";
echo "<li><code>GET $base_url/api/tasks/read.php?page=1</code> - Pagination</li>\n";
echo "</ul>\n";

// READ single endpoint
echo "<h3>3. READ Single Task (GET)</h3>\n";
echo "<pre><code>";
echo "GET $base_url/api/tasks/read_single.php?id=$task_id_sample\n";
echo "</code></pre>\n";

// UPDATE endpoint - Status only
echo "<h3>4. UPDATE Task - Status Only (POST/PUT)</h3>\n";
echo "<p><strong>Allowed for any team member (collaborative)</strong></p>\n";
echo "<pre><code>";
echo "POST $base_url/api/tasks/update.php\n";
echo "Content-Type: application/json\n\n";
echo json_encode([
    'task_id' => $task_id_sample,
    'status' => 'in_progress'
], JSON_PRETTY_PRINT);
echo "</code></pre>\n";

// UPDATE endpoint - Full update
echo "<h3>5. UPDATE Task - Full Details (POST/PUT)</h3>\n";
echo "<p><strong>Allowed only for task creator or admin</strong></p>\n";
echo "<pre><code>";
echo "PUT $base_url/api/tasks/update.php\n";
echo "Content-Type: application/json\n\n";
echo json_encode([
    'task_id' => $task_id_sample,
    'meeting_title' => 'Updated Task Title',
    'agenda' => 'Updated agenda',
    'priority' => 'high',
    'scheduled_date' => '2026-05-26 15:00:00',
    'notes' => 'Updated notes'
], JSON_PRETTY_PRINT);
echo "</code></pre>\n";

// DELETE endpoint
echo "<h3>6. DELETE Task (DELETE/POST)</h3>\n";
echo "<p><strong>Allowed only for task creator or admin</strong></p>\n";
echo "<pre><code>";
echo "DELETE $base_url/api/tasks/delete.php?id=$task_id_sample\n";
echo "</code></pre>\n";

echo "<h2>Testing Instructions</h2>\n";
echo "<ol>\n";
echo "<li>Open Postman or use curl</li>\n";
echo "<li>Make sure you're logged in (use the login endpoint to get session cookie)</li>\n";
echo "<li>Test CREATE endpoint with valid data</li>\n";
echo "<li>Test READ (list) endpoint with different filters</li>\n";
echo "<li>Test READ_SINGLE endpoint with a valid task ID</li>\n";
echo "<li>Test UPDATE endpoint (status-only as any user, full update as creator)</li>\n";
echo "<li>Test DELETE endpoint (only as creator or admin)</li>\n";
echo "</ol>\n";

echo "<h2>Expected Response Format</h2>\n";
echo "<pre><code>";
echo json_encode([
    'success' => true,
    'message' => 'Operation successful',
    'data' => ['task' => 'object or array'],
    'timestamp' => '2026-05-04T12:00:00Z'
], JSON_PRETTY_PRINT);
echo "</code></pre>\n";

echo "<h2>Current Database Stats</h2>\n";
$stmt = $pdo->query("SELECT COUNT(*) as total_tasks, COUNT(CASE WHEN status='pending' THEN 1 END) as pending, COUNT(CASE WHEN status='in_progress' THEN 1 END) as in_progress, COUNT(CASE WHEN status='completed' THEN 1 END) as completed FROM task");
$stats = $stmt->fetch(PDO::FETCH_ASSOC);
echo "<table border='1' cellpadding='5'>\n";
foreach ($stats as $key => $val) {
    echo "<tr><th>$key</th><td>$val</td></tr>\n";
}
echo "</table>\n";
?>
