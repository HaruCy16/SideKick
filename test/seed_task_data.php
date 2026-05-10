<?php
/**
 * Seed Additional Task Data
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

echo "<h1>Seeding Additional Task Data</h1>\n";

try {
    $pdo->beginTransaction();

    // Sample task data - varied priorities, statuses, dates, and assignees
    $additional_tasks = [
        // High priority tasks
        ['project_id' => 1, 'client_id' => 1, 'freelancer_id' => 1, 'meeting_title' => 'Urgent: Bug Fix Review', 'agenda' => 'Critical bug fixes needed for production', 'status' => 'in_progress', 'priority' => 'high', 'scheduled_date' => '2026-05-04 09:00:00', 'created_by' => 1],
        ['project_id' => 1, 'client_id' => 1, 'freelancer_id' => 2, 'meeting_title' => 'Performance Optimization', 'agenda' => 'Discuss page load optimization strategies', 'status' => 'pending', 'priority' => 'high', 'scheduled_date' => '2026-05-05 14:00:00', 'created_by' => 2],
        
        // Medium priority tasks  
        ['project_id' => 2, 'client_id' => 2, 'freelancer_id' => 1, 'meeting_title' => 'Feature Requirements Refinement', 'agenda' => 'Go through new feature requirements with team', 'status' => 'pending', 'priority' => 'medium', 'scheduled_date' => '2026-05-08 10:00:00', 'created_by' => 1],
        ['project_id' => 2, 'client_id' => 2, 'freelancer_id' => 2, 'meeting_title' => 'API Integration Planning', 'agenda' => 'Plan third-party API integrations', 'status' => 'in_progress', 'priority' => 'medium', 'scheduled_date' => '2026-05-06 15:00:00', 'created_by' => 2],
        ['project_id' => 3, 'client_id' => 3, 'freelancer_id' => 1, 'meeting_title' => 'Mobile App Design Review', 'agenda' => 'Review mobile app UI/UX design', 'status' => 'pending', 'priority' => 'medium', 'scheduled_date' => '2026-05-09 11:00:00', 'created_by' => 3],
        
        // Low priority tasks
        ['project_id' => 1, 'client_id' => 1, 'freelancer_id' => 2, 'meeting_title' => 'Code Documentation Update', 'agenda' => 'Update code documentation and README', 'status' => 'on_hold', 'priority' => 'low', 'scheduled_date' => '2026-05-12 10:00:00', 'created_by' => 1],
        ['project_id' => 4, 'client_id' => 4, 'freelancer_id' => 1, 'meeting_title' => 'Testing Strategy Discussion', 'agenda' => 'Plan testing strategy for new features', 'status' => 'pending', 'priority' => 'low', 'scheduled_date' => '2026-05-15 14:00:00', 'created_by' => 4],
        
        // Completed tasks
        ['project_id' => 1, 'client_id' => 1, 'freelancer_id' => 1, 'meeting_title' => 'Database Optimization Complete', 'agenda' => 'Review optimization results', 'status' => 'completed', 'priority' => 'high', 'scheduled_date' => '2026-05-03 10:00:00', 'created_by' => 1],
        ['project_id' => 2, 'client_id' => 2, 'freelancer_id' => 2, 'meeting_title' => 'Security Audit Completed', 'agenda' => 'Review security audit findings', 'status' => 'completed', 'priority' => 'high', 'scheduled_date' => '2026-05-02 16:00:00', 'created_by' => 2],
        
        // More varied tasks
        ['project_id' => 3, 'client_id' => 3, 'freelancer_id' => 1, 'meeting_title' => 'Deployment Planning', 'agenda' => 'Plan deployment to production environment', 'status' => 'pending', 'priority' => 'medium', 'scheduled_date' => '2026-05-20 10:00:00', 'created_by' => 3],
        ['project_id' => 4, 'client_id' => 4, 'freelancer_id' => 2, 'meeting_title' => 'Team Standup', 'agenda' => 'Daily standup with development team', 'status' => 'in_progress', 'priority' => 'medium', 'scheduled_date' => '2026-05-04 09:30:00', 'created_by' => 4],
        ['project_id' => 1, 'client_id' => 1, 'freelancer_id' => 1, 'meeting_title' => 'Client Feedback Session', 'agenda' => 'Gather feedback from client on current progress', 'status' => 'pending', 'priority' => 'high', 'scheduled_date' => '2026-05-10 14:00:00', 'created_by' => 2],
        ['project_id' => 2, 'client_id' => 2, 'freelancer_id' => 1, 'meeting_title' => 'Sprint Planning', 'agenda' => 'Plan tasks for next sprint', 'status' => 'pending', 'priority' => 'medium', 'scheduled_date' => '2026-05-11 10:00:00', 'created_by' => 2],
        ['project_id' => 3, 'client_id' => 3, 'freelancer_id' => 2, 'meeting_title' => 'Architecture Review', 'agenda' => 'Review system architecture and design patterns', 'status' => 'in_progress', 'priority' => 'high', 'scheduled_date' => '2026-05-07 15:00:00', 'created_by' => 3],
        ['project_id' => 4, 'client_id' => 4, 'freelancer_id' => 1, 'meeting_title' => 'Performance Metrics Review', 'agenda' => 'Review application performance metrics', 'status' => 'pending', 'priority' => 'medium', 'scheduled_date' => '2026-05-14 11:00:00', 'created_by' => 4],
        ['project_id' => 1, 'client_id' => 1, 'freelancer_id' => 2, 'meeting_title' => 'User Training Session', 'agenda' => 'Train client users on new system features', 'status' => 'pending', 'priority' => 'low', 'scheduled_date' => '2026-05-21 10:00:00', 'created_by' => 1],
        ['project_id' => 5, 'client_id' => 5, 'freelancer_id' => 1, 'meeting_title' => 'Infrastructure Setup', 'agenda' => 'Setup cloud infrastructure for new project', 'status' => 'in_progress', 'priority' => 'high', 'scheduled_date' => '2026-05-04 13:00:00', 'created_by' => 5],
        ['project_id' => 5, 'client_id' => 5, 'freelancer_id' => 2, 'meeting_title' => 'Backup Strategy Planning', 'agenda' => 'Plan backup and disaster recovery strategy', 'status' => 'pending', 'priority' => 'medium', 'scheduled_date' => '2026-05-16 10:00:00', 'created_by' => 5],
        ['project_id' => 2, 'client_id' => 2, 'freelancer_id' => 2, 'meeting_title' => 'License Agreement Review', 'agenda' => 'Review third-party library licenses', 'status' => 'on_hold', 'priority' => 'low', 'scheduled_date' => '2026-05-18 14:00:00', 'created_by' => 2],
        ['project_id' => 3, 'client_id' => 3, 'freelancer_id' => 1, 'meeting_title' => 'Final QA Review', 'agenda' => 'Final quality assurance review before launch', 'status' => 'pending', 'priority' => 'high', 'scheduled_date' => '2026-05-22 10:00:00', 'created_by' => 3],
    ];

    $stmt = $pdo->prepare("INSERT INTO task (project_id, client_id, freelancer_id, meeting_title, agenda, status, priority, scheduled_date, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $count = 0;
    foreach ($additional_tasks as $task) {
        $stmt->execute([
            $task['project_id'],
            $task['client_id'],
            $task['freelancer_id'],
            $task['meeting_title'],
            $task['agenda'],
            $task['status'],
            $task['priority'],
            $task['scheduled_date'],
            $task['created_by']
        ]);
        $count++;
    }

    $pdo->commit();

    echo "<div style='color: green; font-weight: bold;'>\n";
    echo "✓ Successfully seeded $count additional task records\n";
    echo "</div>\n";

    // Show statistics
    $stmt = $pdo->query("SELECT COUNT(*) as total, status FROM task GROUP BY status");
    $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Task Statistics by Status:</h2>\n";
    echo "<table border='1' cellpadding='5'>\n";
    echo "<tr><th>Status</th><th>Count</th></tr>\n";
    foreach ($stats as $stat) {
        echo "<tr><td>{$stat['status']}</td><td>{$stat['total']}</td></tr>\n";
    }
    echo "</table>\n";

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM task");
    $total = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p><strong>Total tasks in database: " . $total['total'] . "</strong></p>\n";

} catch (PDOException $e) {
    $pdo->rollBack();
    echo "<div style='color: red; font-weight: bold;'>\n";
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "</div>\n";
}
?>
