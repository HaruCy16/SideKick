<?php
/**
 * Database Connection Test
 * Run this file to verify database is properly configured
 * URL: http://localhost/SideKick/test/db_connection_test.php
 * 
 * DELETE THIS FILE AFTER TESTING
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Test</title>
    <link rel="stylesheet" href="/SideKick/assets/css/main.css">
</head>
<body>
    <div class="container" style="padding-top: var(--spacing-xl); padding-bottom: var(--spacing-xl);">
        <h1>Database Connection Test</h1>
        <hr>

        <?php
        try {
            // Test basic connection
            $stmt = $pdo->query("SELECT COUNT(*) as total_tables FROM information_schema.tables WHERE table_schema = 'sidekick_db'");
            $result = $stmt->fetch();
            
            echo '<div class="alert alert-success">';
            echo '<h2>✓ Database Connected Successfully!</h2>';
            echo '<p>Database: <strong>sidekick_db</strong></p>';
            echo '<p>Total Tables: <strong>' . $result['total_tables'] . '</strong></p>';
            echo '</div>';
            
            // Test each table
            echo '<h2>Table Status</h2>';
            $tables = ['users', 'client', 'freelancers', 'project_manager', 'project', 'task', 'time_log', 'invoice', 'invoice_line_item', 'payment', 'notification', 'report'];
            
            foreach ($tables as $table) {
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
                $count = $stmt->fetch()['count'];
                echo '<p>✓ <strong>' . $table . '</strong>: ' . $count . ' records</p>';
            }
            
            // Test seed data
            echo '<h2>Seed Data Verification</h2>';
            
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
            $users = $stmt->fetch()['count'];
            echo '<p>Users: <strong>' . $users . '</strong></p>';
            
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM client");
            $clients = $stmt->fetch()['count'];
            echo '<p>Clients: <strong>' . $clients . '</strong></p>';
            
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM freelancers");
            $freelancers = $stmt->fetch()['count'];
            echo '<p>Freelancers: <strong>' . $freelancers . '</strong></p>';
            
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM project");
            $projects = $stmt->fetch()['count'];
            echo '<p>Projects: <strong>' . $projects . '</strong></p>';
            
            // Test login with test user
            echo '<h2>Test User Login</h2>';
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute(['admin@test.com']);
            $user = $stmt->fetch();
            
            if ($user) {
                echo '<p>✓ Admin user exists</p>';
                echo '<p>Email: <strong>' . $user['email'] . '</strong></p>';
                echo '<p>Role: <strong>' . $user['role'] . '</strong></p>';
                
                // Test password verification
                $testPassword = 'AdminPass123';
                if (password_verify($testPassword, $user['password_hash'])) {
                    echo '<p style="color: green;">✓ Password verification works!</p>';
                } else {
                    echo '<p style="color: red;">✗ Password verification failed</p>';
                }
            }
            
            echo '<hr>';
            echo '<div class="alert alert-info">';
            echo '<h3>Test Credentials</h3>';
            echo '<p><strong>Admin:</strong> admin@test.com / AdminPass123</p>';
            echo '<p><strong>Freelancer 1:</strong> freelancer1@test.com / Freelancer123</p>';
            echo '<p><strong>Freelancer 2:</strong> freelancer2@test.com / Freelancer123</p>';
            echo '<p><strong>Client:</strong> client@test.com / ClientPass123</p>';
            echo '<p><strong>Manager:</strong> manager@test.com / ManagerPass123</p>';
            echo '</div>';
            
            echo '<p style="margin-top: var(--spacing-xl); color: var(--text-secondary);">';
            echo '<strong>NOTE:</strong> This is a test file. Delete it after verification.<br>';
            echo '<a href="/SideKick/public/login.php" class="btn btn-primary">Go to Login</a>';
            echo '</p>';
            
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">';
            echo '<h2>✗ Database Error</h2>';
            echo '<p>' . $e->getMessage() . '</p>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
