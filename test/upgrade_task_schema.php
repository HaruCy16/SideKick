<?php
/**
 * Upgrade Task Table Schema - Add Missing Columns
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

echo "<h1>Upgrading Task Table Schema</h1>\n";

try {
    // Check and add priority column
    $stmt = $pdo->query("SHOW COLUMNS FROM task LIKE 'priority'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("ALTER TABLE task ADD COLUMN priority VARCHAR(50) DEFAULT 'medium' AFTER status");
        echo "<p>✓ Added priority column</p>\n";
    } else {
        echo "<p>✓ priority column already exists</p>\n";
    }

    // Check and add created_at column
    $stmt = $pdo->query("SHOW COLUMNS FROM task LIKE 'created_at'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("ALTER TABLE task ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP AFTER rescheduled_date");
        echo "<p>✓ Added created_at column</p>\n";
    } else {
        echo "<p>✓ created_at column already exists</p>\n";
    }

    // Check and add updated_at column
    $stmt = $pdo->query("SHOW COLUMNS FROM task LIKE 'updated_at'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("ALTER TABLE task ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at");
        echo "<p>✓ Added updated_at column</p>\n";
    } else {
        echo "<p>✓ updated_at column already exists</p>\n";
    }

    // Check and add created_by column
    $stmt = $pdo->query("SHOW COLUMNS FROM task LIKE 'created_by'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("ALTER TABLE task ADD COLUMN created_by INT AFTER updated_at");
        $pdo->exec("ALTER TABLE task ADD CONSTRAINT fk_task_created_by FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL");
        echo "<p>✓ Added created_by column with foreign key</p>\n";
    } else {
        echo "<p>✓ created_by column already exists</p>\n";
    }

    // Add indexes for better query performance
    $stmt = $pdo->query("SHOW INDEX FROM task WHERE Key_name = 'idx_created_by'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("CREATE INDEX idx_created_by ON task(created_by)");
        echo "<p>✓ Added index on created_by</p>\n";
    }

    $stmt = $pdo->query("SHOW INDEX FROM task WHERE Key_name = 'idx_project_status'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("CREATE INDEX idx_project_status ON task(project_id, status)");
        echo "<p>✓ Added composite index on project_id, status</p>\n";
    }

    // Create audit_log table if it doesn't exist
    $stmt = $pdo->query("SHOW TABLES LIKE 'audit_log'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("CREATE TABLE audit_log (
            id INT PRIMARY KEY AUTO_INCREMENT,
            task_id INT,
            user_id INT,
            action VARCHAR(50),
            old_value JSON,
            new_value JSON,
            timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (task_id) REFERENCES task(task_id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
            INDEX idx_task_id (task_id),
            INDEX idx_user_id (user_id),
            INDEX idx_timestamp (timestamp)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        echo "<p>✓ Created audit_log table</p>\n";
    } else {
        echo "<p>✓ audit_log table already exists</p>\n";
    }

    echo "<div style='color: green; font-weight: bold; margin-top: 20px;'>";
    echo "✓ Database schema upgrade complete!";
    echo "</div>\n";

} catch (PDOException $e) {
    echo "<div style='color: red; font-weight: bold;'>";
    echo "✗ Error: " . $e->getMessage();
    echo "</div>\n";
}
?>
