<?php
/**
 * Database Schema Verification for Task Management
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

echo "<h1>Task Table Structure Verification</h1>\n";

// Check if task table exists
$stmt = $pdo->query("SHOW TABLES LIKE 'task'");
$table_exists = $stmt->rowCount() > 0;
echo "<p><strong>Task table exists:</strong> " . ($table_exists ? '✓ YES' : '✗ NO') . "</p>\n";

if (!$table_exists) {
    echo "<p>Task table does not exist. Please run database schema first.</p>";
    exit;
}

// Get table structure
echo "<h2>Current Columns:</h2>\n";
$stmt = $pdo->query("DESCRIBE task");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<table border='1' cellpadding='5'>\n";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>\n";
foreach ($columns as $col) {
    echo "<tr>";
    echo "<td>{$col['Field']}</td>";
    echo "<td>{$col['Type']}</td>";
    echo "<td>{$col['Null']}</td>";
    echo "<td>{$col['Key']}</td>";
    echo "<td>{$col['Default']}</td>";
    echo "<td>{$col['Extra']}</td>";
    echo "</tr>\n";
}
echo "</table>\n";

// Check for specific columns needed
echo "<h2>Required Columns Check:</h2>\n";
$required_columns = [
    'task_id' => 'PRIMARY KEY',
    'project_id' => 'Foreign Key',
    'client_id' => 'Foreign Key',
    'freelancer_id' => 'Foreign Key',
    'meeting_title' => 'Task Title',
    'agenda' => 'Description',
    'status' => 'Status (pending/in_progress/completed/on_hold)',
    'scheduled_date' => 'Due Date',
    'priority' => 'Priority (high/medium/low)',
    'created_at' => 'Creation Timestamp',
    'updated_at' => 'Update Timestamp',
    'created_by' => 'Created By User',
];

$column_names = array_map(fn($c) => $c['Field'], $columns);

foreach ($required_columns as $col_name => $description) {
    $exists = in_array($col_name, $column_names);
    $status = $exists ? '✓' : '✗';
    echo "<p><strong>$status $col_name:</strong> $description</p>\n";
}

// Show sample task records
echo "<h2>Sample Task Data:</h2>\n";
$stmt = $pdo->query("SELECT * FROM task LIMIT 3");
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>" . json_encode($tasks, JSON_PRETTY_PRINT) . "</pre>\n";

// Show indexes
echo "<h2>Current Indexes:</h2>\n";
$stmt = $pdo->query("SHOW INDEX FROM task");
$indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<table border='1' cellpadding='5'>\n";
echo "<tr><th>Column</th><th>Index Name</th><th>Seq</th></tr>\n";
foreach ($indexes as $idx) {
    echo "<tr>";
    echo "<td>{$idx['Column_name']}</td>";
    echo "<td>{$idx['Key_name']}</td>";
    echo "<td>{$idx['Seq_in_index']}</td>";
    echo "</tr>\n";
}
echo "</table>\n";
?>
