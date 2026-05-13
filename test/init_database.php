<?php
/**
 * Database Initialization Script
 * Creates the database and imports schema
 * Run: http://localhost/SideKick/test/init_database.php
 */

ob_start();

echo "================================================================================\n";
echo "SIDEKICK DATABASE INITIALIZATION\n";
echo "================================================================================\n\n";

// Database credentials
$dbHost = 'localhost';
$dbUser = 'root';
$dbPassword = '';
$dbName = 'sidekick_db';

echo "[STEP 1] Creating Database Connection\n";
echo "-----------------------------------\n";

try {
    // Connect to MySQL without selecting database
    $pdo = new PDO(
        "mysql:host=$dbHost",
        $dbUser,
        $dbPassword,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    
    echo "✅ Connected to MySQL server\n";
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
    echo "\nPlease verify:\n";
    echo "- MySQL service is running\n";
    echo "- Database host is: $dbHost\n";
    echo "- Database user is: $dbUser\n";
    exit;
}

echo "\n[STEP 2] Creating Database\n";
echo "-----------------------------------\n";

try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName`");
    echo "✅ Database '$dbName' ready\n";
} catch (PDOException $e) {
    echo "❌ Failed to create database: " . $e->getMessage() . "\n";
    exit;
}

echo "\n[STEP 3] Selecting Database\n";
echo "-----------------------------------\n";

try {
    $pdo->exec("USE `$dbName`");
    echo "✅ Database '$dbName' selected\n";
} catch (PDOException $e) {
    echo "❌ Failed to select database: " . $e->getMessage() . "\n";
    exit;
}

echo "\n[STEP 4] Creating Tables\n";
echo "-----------------------------------\n";

// Read schema file
$schemaFile = __DIR__ . '/../database/schema.sql';

if (!file_exists($schemaFile)) {
    echo "❌ Schema file not found: $schemaFile\n";
    exit;
}

$schema = file_get_contents($schemaFile);

// Split SQL statements by semicolon
$statements = array_filter(array_map('trim', explode(';', $schema)));

$tableCount = 0;
foreach ($statements as $statement) {
    if (empty($statement)) continue;
    
    try {
        $pdo->exec($statement);
        
        // Count CREATE TABLE statements
        if (stripos($statement, 'CREATE TABLE') === 0) {
            preg_match('/CREATE TABLE[S\s]+(?:IF NOT EXISTS\s+)?`?(\w+)`?/i', $statement, $matches);
            if (!empty($matches[1])) {
                echo "✅ Created table: " . $matches[1] . "\n";
                $tableCount++;
            }
        }
    } catch (PDOException $e) {
        // Ignore if tables already exist
        if (stripos($e->getMessage(), 'already exists') === false) {
            echo "⚠️  Warning: " . $e->getMessage() . "\n";
        }
    }
}

echo "\n[STEP 5] Verifying Tables\n";
echo "-----------------------------------\n";

try {
    $result = $pdo->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$dbName'")->fetchAll();
    
    echo "✅ Database has " . count($result) . " tables:\n";
    foreach ($result as $table) {
        echo "   - " . $table['TABLE_NAME'] . "\n";
    }
} catch (PDOException $e) {
    echo "❌ Error verifying tables: " . $e->getMessage() . "\n";
}

echo "\n[STEP 6] Creating Test Admin User (Optional)\n";
echo "-----------------------------------\n";

try {
    // Check if admin user exists
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'admin'");
    $stmt->execute();
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        $adminEmail = 'admin@test.com';
        $adminPassword = 'AdminPass123!';
        $adminHash = password_hash($adminPassword, PASSWORD_DEFAULT);
        
        // Create admin user
        $stmt = $pdo->prepare("
            INSERT INTO users (role, is_verified, is_active, email, password_hash, date_registered)
            VALUES ('admin', TRUE, TRUE, ?, ?, NOW())
        ");
        $stmt->execute([$adminEmail, $adminHash]);
        $adminId = $pdo->lastInsertId();
        
        // Create admin profile
        $stmt = $pdo->prepare("
            INSERT INTO project_manager (user_id, first_name, last_name, account_status)
            VALUES (?, 'Admin', 'User', 'active')
        ");
        $stmt->execute([$adminId]);
        
        echo "✅ Admin user created\n";
        echo "   Email: $adminEmail\n";
        echo "   Password: $adminPassword\n";
    } else {
        echo "✅ Admin user already exists\n";
    }
} catch (PDOException $e) {
    echo "⚠️  Could not create admin user: " . $e->getMessage() . "\n";
}

echo "\n";
echo "================================================================================\n";
echo "DATABASE INITIALIZATION COMPLETE\n";
echo "================================================================================\n\n";

echo "✅ Database is ready for testing!\n\n";

echo "Next Steps:\n";
echo "1. Run: http://localhost/SideKick/test/auth_test_suite.php\n";
echo "2. Run: http://localhost/SideKick/test/api_auth_test.php\n";
echo "3. Test login at: http://localhost/SideKick/public/login.php\n";
echo "4. Read: test/TESTING_GUIDE.md\n\n";

ob_end_flush();
?>
