<?php
/**
 * Update User Passwords
 * This script updates the password hashes for test users
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';

// Test passwords
$updates = [
    'admin@test.com' => password_hash('AdminPass123', PASSWORD_DEFAULT),
    'manager@test.com' => password_hash('ManagerPass123', PASSWORD_DEFAULT),
    'freelancer1@test.com' => password_hash('Freelancer123', PASSWORD_DEFAULT),
    'freelancer2@test.com' => password_hash('Freelancer123', PASSWORD_DEFAULT),
    'client@test.com' => password_hash('ClientPass123', PASSWORD_DEFAULT),
];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Update User Passwords</title>
    <link rel="stylesheet" href="/SideKick/assets/css/main.css">
</head>
<body>
    <div class="container" style="padding-top: var(--spacing-xl); padding-bottom: var(--spacing-xl);">
        <h1>Updating User Passwords</h1>
        
        <?php
        try {
            $updated = 0;
            foreach ($updates as $email => $hash) {
                $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
                $stmt->execute([$hash, $email]);
                
                if ($stmt->rowCount() > 0) {
                    echo '<p style="color: green;">✓ Updated: ' . $email . '</p>';
                    $updated++;
                } else {
                    echo '<p style="color: orange;">⚠ No user found: ' . $email . '</p>';
                }
            }
            
            echo '<div class="alert alert-success" style="margin-top: var(--spacing-lg);">';
            echo '<h2>✓ Password Update Complete</h2>';
            echo '<p>Updated ' . $updated . ' users successfully</p>';
            echo '<a href="/SideKick/public/login.php" class="btn btn-primary">Go to Login</a>';
            echo '</div>';
            
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">';
            echo '<h2>Error</h2>';
            echo '<p>' . $e->getMessage() . '</p>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
