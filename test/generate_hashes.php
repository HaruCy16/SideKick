<?php
/**
 * Generate Password Hashes for Test Users
 * Run this once to get the hashes, then update the database
 */

// Test passwords and their bcrypt hashes
$testUsers = [
    'admin@test.com' => 'AdminPass123',
    'manager@test.com' => 'ManagerPass123',
    'freelancer1@test.com' => 'Freelancer123',
    'freelancer2@test.com' => 'Freelancer123',
    'client@test.com' => 'ClientPass123'
];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Hash Generator</title>
    <link rel="stylesheet" href="/SideKick/assets/css/main.css">
</head>
<body>
    <div class="container" style="padding-top: var(--spacing-xl); padding-bottom: var(--spacing-xl);">
        <h1>Password Hash Generator</h1>
        <p>Copy these hashes and use them to update the users table</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: var(--spacing-lg);">
            <thead>
                <tr style="background-color: var(--bg-secondary);">
                    <th style="padding: var(--spacing-md); text-align: left; border: 1px solid var(--border-color);">Email</th>
                    <th style="padding: var(--spacing-md); text-align: left; border: 1px solid var(--border-color);">Password</th>
                    <th style="padding: var(--spacing-md); text-align: left; border: 1px solid var(--border-color);">Hash (bcrypt)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($testUsers as $email => $password): ?>
                <tr>
                    <td style="padding: var(--spacing-md); border: 1px solid var(--border-color);">
                        <code><?php echo $email; ?></code>
                    </td>
                    <td style="padding: var(--spacing-md); border: 1px solid var(--border-color);">
                        <code><?php echo $password; ?></code>
                    </td>
                    <td style="padding: var(--spacing-md); border: 1px solid var(--border-color); word-break: break-all;">
                        <code style="font-size: 0.85rem;"><?php echo password_hash($password, PASSWORD_DEFAULT); ?></code>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2 style="margin-top: var(--spacing-xl);">SQL Update Statements</h2>
        <p>Run these SQL queries to update the user passwords:</p>
        
        <pre style="background-color: var(--bg-secondary); padding: var(--spacing-lg); border-radius: var(--border-radius-md); overflow-x: auto;">
<?php
foreach ($testUsers as $email => $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    echo "UPDATE users SET password_hash = '{$hash}' WHERE email = '{$email}';" . PHP_EOL;
}
?>
        </pre>
    </div>
</body>
</html>
