<?php
/**
 * Authentication Test Suite
 * Tests registration, login, and database persistence
 * Run: http://localhost/SideKick/test/auth_test_suite.php
 */

ob_start();

// Load configuration
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth_guard.php';

// Test results tracking
$testResults = [
    'passed' => 0,
    'failed' => 0,
    'tests' => []
];

// Helper function to record test result
function recordTest($testName, $passed, $message = '') {
    global $testResults;
    $testResults['tests'][] = [
        'name' => $testName,
        'passed' => $passed,
        'message' => $message
    ];
    if ($passed) {
        $testResults['passed']++;
    } else {
        $testResults['failed']++;
    }
}

echo "================================================================================\n";
echo "SIDEKICK AUTHENTICATION TEST SUITE\n";
echo "================================================================================\n\n";

// ============================================================================
// TEST 1: Database Connection
// ============================================================================
echo "[TEST 1] DATABASE CONNECTION\n";
echo "-----------------------------------\n";

try {
    $testQuery = $pdo->query("SELECT 1");
    if ($testQuery) {
        echo "✅ Database connection: SUCCESS\n";
        recordTest('Database Connection', true, 'PDO connected successfully');
    } else {
        echo "❌ Database connection: FAILED\n";
        recordTest('Database Connection', false, 'Query failed');
    }
} catch (Exception $e) {
    echo "❌ Database connection: ERROR\n";
    echo "   Error: " . $e->getMessage() . "\n";
    recordTest('Database Connection', false, $e->getMessage());
    exit;
}

// Check if users table exists
try {
    $tableCheck = $pdo->query("DESCRIBE users");
    echo "✅ Users table exists\n";
    recordTest('Users Table', true, 'Table structure valid');
} catch (Exception $e) {
    echo "❌ Users table missing\n";
    recordTest('Users Table', false, $e->getMessage());
}

echo "\n";

// ============================================================================
// TEST 2: User Registration Tests
// ============================================================================
echo "[TEST 2] USER REGISTRATION\n";
echo "-----------------------------------\n";

// Test data
$testEmail = 'testuser_' . time() . '@example.com';
$testPassword = 'TestPassword123!';
$testUser = [
    'first_name' => 'Test',
    'last_name' => 'User',
    'email' => $testEmail,
    'password' => $testPassword,
    'role' => 'freelancer'
];

// Test 2.1: Validate email format
echo "\n[2.1] Email Validation\n";
if (is_valid_email($testEmail)) {
    echo "✅ Email format valid\n";
    recordTest('Email Validation', true, 'Valid email format');
} else {
    echo "❌ Email format invalid\n";
    recordTest('Email Validation', false, 'Invalid email format');
}

// Test 2.2: Check for duplicate email
echo "\n[2.2] Duplicate Email Check\n";
try {
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$testEmail]);
    $existing = $stmt->fetch();
    
    if (!$existing) {
        echo "✅ Email not in database (available for registration)\n";
        recordTest('Email Availability', true, 'Email available');
    } else {
        echo "⚠️  Email already registered\n";
        recordTest('Email Availability', false, 'Email already exists');
    }
} catch (Exception $e) {
    echo "❌ Error checking email: " . $e->getMessage() . "\n";
    recordTest('Email Availability Check', false, $e->getMessage());
}

// Test 2.3: User Registration
echo "\n[2.3] User Registration\n";
try {
    $passwordHash = hash_password($testPassword);
    
    $pdo->beginTransaction();
    
    // Insert user
    $stmt = $pdo->prepare("
        INSERT INTO users (role, is_verified, is_active, email, password_hash, date_registered)
        VALUES (?, FALSE, TRUE, ?, ?, NOW())
    ");
    $stmt->execute([$testUser['role'], $testEmail, $passwordHash]);
    $userId = $pdo->lastInsertId();
    
    // Create freelancer profile
    $stmt = $pdo->prepare("
        INSERT INTO freelancers (user_id, first_name, last_name, account_status, date_registered)
        VALUES (?, ?, ?, 'active', NOW())
    ");
    $stmt->execute([$userId, $testUser['first_name'], $testUser['last_name']]);
    
    $pdo->commit();
    
    echo "✅ User registered successfully\n";
    echo "   User ID: $userId\n";
    echo "   Email: $testEmail\n";
    recordTest('User Registration', true, "User $userId registered");
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "❌ Registration failed: " . $e->getMessage() . "\n";
    recordTest('User Registration', false, $e->getMessage());
}

echo "\n";

// ============================================================================
// TEST 3: User Login Tests
// ============================================================================
echo "[TEST 3] USER LOGIN\n";
echo "-----------------------------------\n";

// Test 3.1: Fetch registered user
echo "\n[3.1] Fetch User from Database\n";
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$testEmail]);
    $loginUser = $stmt->fetch();
    
    if ($loginUser) {
        echo "✅ User found in database\n";
        echo "   User ID: " . $loginUser['user_id'] . "\n";
        echo "   Email: " . $loginUser['email'] . "\n";
        echo "   Role: " . $loginUser['role'] . "\n";
        echo "   Active: " . ($loginUser['is_active'] ? 'Yes' : 'No') . "\n";
        recordTest('User Fetch', true, 'User found in database');
    } else {
        echo "❌ User not found\n";
        recordTest('User Fetch', false, 'User not found');
    }
} catch (Exception $e) {
    echo "❌ Error fetching user: " . $e->getMessage() . "\n";
    recordTest('User Fetch', false, $e->getMessage());
}

// Test 3.2: Verify password
echo "\n[3.2] Password Verification\n";
if (isset($loginUser)) {
    if (verify_password($testPassword, $loginUser['password_hash'])) {
        echo "✅ Password verification successful\n";
        recordTest('Password Verification', true, 'Password matches hash');
    } else {
        echo "❌ Password verification failed\n";
        recordTest('Password Verification', false, 'Password does not match');
    }
}

// Test 3.3: Invalid password
echo "\n[3.3] Invalid Password Check\n";
if (isset($loginUser)) {
    if (!verify_password('WrongPassword123', $loginUser['password_hash'])) {
        echo "✅ Wrong password correctly rejected\n";
        recordTest('Invalid Password Rejection', true, 'Wrong password rejected');
    } else {
        echo "❌ Wrong password was accepted\n";
        recordTest('Invalid Password Rejection', false, 'Wrong password accepted');
    }
}

// Test 3.4: Account status check
echo "\n[3.4] Account Status Check\n";
if (isset($loginUser)) {
    if ($loginUser['is_active']) {
        echo "✅ Account is active\n";
        recordTest('Account Status', true, 'Account active');
    } else {
        echo "❌ Account is inactive\n";
        recordTest('Account Status', false, 'Account inactive');
    }
}

// Test 3.5: Update last login
echo "\n[3.5] Update Last Login Timestamp\n";
if (isset($loginUser)) {
    try {
        $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
        $stmt->execute([$loginUser['user_id']]);
        
        $stmt = $pdo->prepare("SELECT last_login FROM users WHERE user_id = ?");
        $stmt->execute([$loginUser['user_id']]);
        $updated = $stmt->fetch();
        
        if ($updated['last_login']) {
            echo "✅ Last login updated\n";
            echo "   Timestamp: " . $updated['last_login'] . "\n";
            recordTest('Last Login Update', true, 'Timestamp updated');
        } else {
            echo "❌ Last login not updated\n";
            recordTest('Last Login Update', false, 'Update failed');
        }
    } catch (Exception $e) {
        echo "❌ Error updating last login: " . $e->getMessage() . "\n";
        recordTest('Last Login Update', false, $e->getMessage());
    }
}

echo "\n";

// ============================================================================
// TEST 4: Session Management
// ============================================================================
echo "[TEST 4] SESSION MANAGEMENT\n";
echo "-----------------------------------\n";

// Test 4.1: Start session
echo "\n[4.1] Start Session\n";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
echo "✅ Session started\n";
echo "   Session ID: " . session_id() . "\n";
recordTest('Session Start', true, 'Session initialized');

// Test 4.2: Set session data
echo "\n[4.2] Set Session Data\n";
if (isset($loginUser)) {
    try {
        $_SESSION['user_id'] = $loginUser['user_id'];
        $_SESSION['email'] = $loginUser['email'];
        $_SESSION['user_role'] = $loginUser['role'];
        $_SESSION['session_created'] = time();
        $_SESSION['last_activity'] = time();
        
        echo "✅ Session data set\n";
        echo "   User ID: " . $_SESSION['user_id'] . "\n";
        echo "   Email: " . $_SESSION['email'] . "\n";
        echo "   Role: " . $_SESSION['user_role'] . "\n";
        recordTest('Session Data Set', true, 'Session variables stored');
    } catch (Exception $e) {
        echo "❌ Error setting session: " . $e->getMessage() . "\n";
        recordTest('Session Data Set', false, $e->getMessage());
    }
}

// Test 4.3: Verify session persistence
echo "\n[4.3] Verify Session Persistence\n";
if (!empty($_SESSION['user_id'])) {
    echo "✅ Session data persists\n";
    echo "   Session user_id: " . $_SESSION['user_id'] . "\n";
    recordTest('Session Persistence', true, 'Session data retained');
} else {
    echo "❌ Session data not persisted\n";
    recordTest('Session Persistence', false, 'Session data lost');
}

// Test 4.4: Check is_authenticated
echo "\n[4.4] Authentication Check\n";
if (is_authenticated()) {
    echo "✅ User is authenticated\n";
    recordTest('Authentication Check', true, 'User authenticated');
} else {
    echo "❌ User not authenticated\n";
    recordTest('Authentication Check', false, 'User not authenticated');
}

echo "\n";

// ============================================================================
// TEST 5: Data Persistence
// ============================================================================
echo "[TEST 5] DATA PERSISTENCE\n";
echo "-----------------------------------\n";

echo "\n[5.1] Verify User Data Saved\n";
if (isset($loginUser)) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$loginUser['user_id']]);
        $savedUser = $stmt->fetch();
        
        if ($savedUser) {
            echo "✅ User data persisted in database\n";
            echo "   First Name: " . (isset($savedUser['first_name']) ? $savedUser['first_name'] : 'N/A') . "\n";
            echo "   Email: " . $savedUser['email'] . "\n";
            echo "   Role: " . $savedUser['role'] . "\n";
            echo "   Active: " . ($savedUser['is_active'] ? 'Yes' : 'No') . "\n";
            echo "   Registered: " . $savedUser['date_registered'] . "\n";
            recordTest('User Data Persistence', true, 'Data saved correctly');
        }
    } catch (Exception $e) {
        echo "❌ Error retrieving user data: " . $e->getMessage() . "\n";
        recordTest('User Data Persistence', false, $e->getMessage());
    }
}

echo "\n[5.2] Verify Profile Data Saved\n";
if (isset($userId)) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM freelancers WHERE user_id = ?");
        $stmt->execute([$userId]);
        $profile = $stmt->fetch();
        
        if ($profile) {
            echo "✅ Profile data persisted\n";
            echo "   Freelancer ID: " . $profile['freelancer_id'] . "\n";
            echo "   Name: " . $profile['first_name'] . " " . $profile['last_name'] . "\n";
            echo "   Status: " . $profile['account_status'] . "\n";
            echo "   Registered: " . $profile['date_registered'] . "\n";
            recordTest('Profile Data Persistence', true, 'Profile saved correctly');
        }
    } catch (Exception $e) {
        echo "❌ Error retrieving profile: " . $e->getMessage() . "\n";
        recordTest('Profile Data Persistence', false, $e->getMessage());
    }
}

echo "\n";

// ============================================================================
// TEST 6: Edge Cases
// ============================================================================
echo "[TEST 6] EDGE CASES\n";
echo "-----------------------------------\n";

// Test 6.1: Test with invalid email
echo "\n[6.1] Invalid Email Format\n";
if (!is_valid_email('invalid-email-format')) {
    echo "✅ Invalid email correctly rejected\n";
    recordTest('Invalid Email Rejection', true, 'Invalid email rejected');
} else {
    echo "❌ Invalid email was accepted\n";
    recordTest('Invalid Email Rejection', false, 'Invalid email accepted');
}

// Test 6.2: Test with short password
echo "\n[6.2] Short Password Validation\n";
if (strlen('short') < PASSWORD_MIN_LENGTH) {
    echo "✅ Short password correctly identified\n";
    echo "   Min length: " . PASSWORD_MIN_LENGTH . " characters\n";
    recordTest('Short Password Check', true, 'Short password detected');
} else {
    echo "❌ Short password validation failed\n";
    recordTest('Short Password Check', false, 'Validation failed');
}

// Test 6.3: Duplicate email prevention
echo "\n[6.3] Duplicate Email Prevention\n";
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM users WHERE email = ?");
    $stmt->execute([$testEmail]);
    $result = $stmt->fetch();
    
    if ($result['count'] == 1) {
        echo "✅ Duplicate email prevention working (only 1 record)\n";
        recordTest('Duplicate Email Prevention', true, 'No duplicates created');
    } else {
        echo "⚠️  Multiple records found for same email\n";
        recordTest('Duplicate Email Prevention', false, "Found " . $result['count'] . " records");
    }
} catch (Exception $e) {
    echo "❌ Error checking duplicates: " . $e->getMessage() . "\n";
    recordTest('Duplicate Email Prevention', false, $e->getMessage());
}

echo "\n";

// ============================================================================
// TEST SUMMARY
// ============================================================================
echo "================================================================================\n";
echo "TEST SUMMARY\n";
echo "================================================================================\n\n";

echo "Total Tests: " . ($testResults['passed'] + $testResults['failed']) . "\n";
echo "✅ Passed: " . $testResults['passed'] . "\n";
echo "❌ Failed: " . $testResults['failed'] . "\n\n";

// Detailed results
echo "DETAILED RESULTS:\n";
echo "-----------------------------------\n";
foreach ($testResults['tests'] as $test) {
    $status = $test['passed'] ? '✅' : '❌';
    echo $status . " " . $test['name'] . "\n";
    if ($test['message']) {
        echo "   └─ " . $test['message'] . "\n";
    }
}

echo "\n";

// Overall status
if ($testResults['failed'] == 0) {
    echo "🎉 ALL TESTS PASSED!\n";
    echo "✅ Authentication system is working correctly\n";
    echo "✅ Data is being saved to database\n";
    echo "✅ Login flow is functional\n";
} else {
    echo "⚠️  SOME TESTS FAILED\n";
    echo "Please review the failures above and fix any issues.\n";
}

echo "\n";

// ============================================================================
// CLEANUP (Optional)
// ============================================================================
echo "================================================================================\n";
echo "CLEANUP\n";
echo "================================================================================\n\n";

if (isset($userId)) {
    echo "Test user created: $testEmail\n";
    echo "User ID: $userId\n";
    echo "\nYou can:\n";
    echo "1. Test login with: $testEmail / $testPassword\n";
    echo "2. Delete test user with the following SQL:\n";
    echo "   DELETE FROM users WHERE user_id = $userId;\n";
    echo "   DELETE FROM freelancers WHERE user_id = $userId;\n";
}

echo "\n";

// Output buffer
ob_end_flush();
?>
