<?php
/**
 * API Authentication Endpoint Tests
 * Tests the actual API endpoints (register and login)
 * Run: http://localhost/SideKick/test/api_auth_test.php
 */

// Start output buffering
ob_start();

// Test configuration
$baseUrl = 'http://localhost/SideKick';
$testResults = [
    'passed' => 0,
    'failed' => 0,
    'tests' => []
];

function recordTest($name, $passed, $message = '') {
    global $testResults;
    $testResults['tests'][] = [
        'name' => $name,
        'passed' => $passed,
        'message' => $message
    ];
    if ($passed) {
        $testResults['passed']++;
    } else {
        $testResults['failed']++;
    }
}

function makeRequest($method, $endpoint, $data = null) {
    global $baseUrl;
    
    $url = $baseUrl . $endpoint;
    
    $options = [
        'http' => [
            'method' => $method,
            'header' => [
                'Content-Type: application/json',
            ],
            'timeout' => 5
        ]
    ];
    
    if ($data) {
        $options['http']['content'] = json_encode($data);
    }
    
    $context = stream_context_create($options);
    
    try {
        $response = file_get_contents($url, false, $context);
        $httpCode = 0;
        
        // Extract HTTP code from headers
        if (isset($http_response_header)) {
            preg_match('/HTTP\/\d+\.\d+ (\d+)/', $http_response_header[0], $matches);
            $httpCode = (int)$matches[1];
        }
        
        return [
            'success' => true,
            'response' => json_decode($response, true),
            'http_code' => $httpCode,
            'raw' => $response
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

echo "================================================================================\n";
echo "SIDEKICK API AUTHENTICATION ENDPOINT TESTS\n";
echo "================================================================================\n\n";

// Test data
$timestamp = time();
$testEmail = "api_test_{$timestamp}@example.com";
$testPassword = "TestPass123!";

// ============================================================================
// TEST 1: Registration API
// ============================================================================
echo "[TEST 1] REGISTRATION API ENDPOINT\n";
echo "-----------------------------------\n\n";

$registerData = [
    'first_name' => 'API',
    'last_name' => 'Tester',
    'email' => $testEmail,
    'password' => $testPassword,
    'password_confirm' => $testPassword,
    'role' => 'freelancer'
];

echo "[1.1] Test Registration Request\n";
$regResponse = makeRequest('POST', '/api/auth/register.php', $registerData);

if (!$regResponse['success']) {
    echo "❌ FAILED: Could not connect to API\n";
    echo "   Error: " . $regResponse['error'] . "\n";
    recordTest('API Connection (Register)', false, $regResponse['error']);
} else {
    echo "✅ API connection successful\n";
    echo "   HTTP Code: " . $regResponse['http_code'] . "\n";
    recordTest('API Connection (Register)', $regResponse['http_code'] == 201, 'HTTP ' . $regResponse['http_code']);
    
    if ($regResponse['http_code'] == 201) {
        echo "✅ Registration successful (HTTP 201)\n";
        recordTest('User Registration (API)', true, 'User created via API');
        
        $respData = $regResponse['response'];
        if (isset($respData['data'])) {
            echo "   Response Data:\n";
            echo "   - User ID: " . $respData['data']['user_id'] . "\n";
            echo "   - Email: " . $respData['data']['email'] . "\n";
            echo "   - Role: " . $respData['data']['role'] . "\n";
            $registeredUserId = $respData['data']['user_id'];
        }
    } else {
        echo "❌ Registration failed\n";
        echo "   Response: " . json_encode($regResponse['response'], JSON_PRETTY_PRINT) . "\n";
        recordTest('User Registration (API)', false, 'HTTP ' . $regResponse['http_code']);
    }
}

echo "\n";

// ============================================================================
// TEST 2: Login API - Valid Credentials
// ============================================================================
echo "[TEST 2] LOGIN API - VALID CREDENTIALS\n";
echo "-----------------------------------\n\n";

$loginData = [
    'email' => $testEmail,
    'password' => $testPassword
];

echo "[2.1] Test Login with Valid Credentials\n";
$loginResponse = makeRequest('POST', '/api/auth/login.php', $loginData);

if (!$loginResponse['success']) {
    echo "❌ FAILED: Could not connect to API\n";
    echo "   Error: " . $loginResponse['error'] . "\n";
    recordTest('API Connection (Login)', false, $loginResponse['error']);
} else {
    echo "✅ API connection successful\n";
    echo "   HTTP Code: " . $loginResponse['http_code'] . "\n";
    recordTest('API Connection (Login)', true, 'Connected to login endpoint');
    
    if ($loginResponse['http_code'] == 200) {
        echo "✅ Login successful (HTTP 200)\n";
        recordTest('Valid Login', true, 'Login successful');
        
        $respData = $loginResponse['response'];
        if (isset($respData['data'])) {
            echo "   Response Data:\n";
            echo "   - User ID: " . $respData['data']['user_id'] . "\n";
            echo "   - Email: " . $respData['data']['email'] . "\n";
            echo "   - Role: " . $respData['data']['role'] . "\n";
            echo "   - Session ID: " . $respData['data']['session_id'] . "\n";
        }
    } else {
        echo "❌ Login failed\n";
        echo "   HTTP Code: " . $loginResponse['http_code'] . "\n";
        echo "   Response: " . json_encode($loginResponse['response'], JSON_PRETTY_PRINT) . "\n";
        recordTest('Valid Login', false, 'HTTP ' . $loginResponse['http_code']);
    }
}

echo "\n";

// ============================================================================
// TEST 3: Login API - Invalid Password
// ============================================================================
echo "[TEST 3] LOGIN API - INVALID PASSWORD\n";
echo "-----------------------------------\n\n";

$invalidLoginData = [
    'email' => $testEmail,
    'password' => 'WrongPassword123'
];

echo "[3.1] Test Login with Invalid Password\n";
$invalidLoginResponse = makeRequest('POST', '/api/auth/login.php', $invalidLoginData);

if (!$invalidLoginResponse['success']) {
    echo "❌ Could not connect to API\n";
    recordTest('Invalid Password Test', false, $invalidLoginResponse['error']);
} else {
    if ($invalidLoginResponse['http_code'] == 401) {
        echo "✅ Invalid password correctly rejected (HTTP 401)\n";
        recordTest('Invalid Password Rejection', true, 'Correctly rejected');
    } else {
        echo "❌ Invalid password was not rejected\n";
        echo "   Expected: 401, Got: " . $invalidLoginResponse['http_code'] . "\n";
        recordTest('Invalid Password Rejection', false, 'HTTP ' . $invalidLoginResponse['http_code']);
    }
}

echo "\n";

// ============================================================================
// TEST 4: Login API - Non-existent Email
// ============================================================================
echo "[TEST 4] LOGIN API - NON-EXISTENT EMAIL\n";
echo "-----------------------------------\n\n";

$noEmailLoginData = [
    'email' => 'nonexistent_' . time() . '@example.com',
    'password' => 'SomePassword123'
];

echo "[4.1] Test Login with Non-existent Email\n";
$noEmailResponse = makeRequest('POST', '/api/auth/login.php', $noEmailLoginData);

if (!$noEmailResponse['success']) {
    echo "❌ Could not connect to API\n";
    recordTest('Non-existent Email Test', false, $noEmailResponse['error']);
} else {
    if ($noEmailResponse['http_code'] == 401) {
        echo "✅ Non-existent email correctly rejected (HTTP 401)\n";
        recordTest('Non-existent Email Rejection', true, 'Correctly rejected');
    } else {
        echo "❌ Non-existent email was not rejected\n";
        echo "   Expected: 401, Got: " . $noEmailResponse['http_code'] . "\n";
        recordTest('Non-existent Email Rejection', false, 'HTTP ' . $noEmailResponse['http_code']);
    }
}

echo "\n";

// ============================================================================
// TEST 5: Registration - Duplicate Email
// ============================================================================
echo "[TEST 5] REGISTRATION - DUPLICATE EMAIL\n";
echo "-----------------------------------\n\n";

$duplicateRegData = [
    'first_name' => 'Duplicate',
    'last_name' => 'Tester',
    'email' => $testEmail,  // Same email as before
    'password' => 'AnotherPass123',
    'password_confirm' => 'AnotherPass123',
    'role' => 'client'
];

echo "[5.1] Test Registration with Duplicate Email\n";
$dupResponse = makeRequest('POST', '/api/auth/register.php', $duplicateRegData);

if (!$dupResponse['success']) {
    echo "❌ Could not connect to API\n";
    recordTest('Duplicate Email Prevention', false, $dupResponse['error']);
} else {
    if ($dupResponse['http_code'] == 409) {
        echo "✅ Duplicate email correctly rejected (HTTP 409 Conflict)\n";
        recordTest('Duplicate Email Prevention', true, 'Correctly rejected');
    } else {
        echo "❌ Duplicate email was not rejected\n";
        echo "   Expected: 409, Got: " . $dupResponse['http_code'] . "\n";
        recordTest('Duplicate Email Prevention', false, 'HTTP ' . $dupResponse['http_code']);
    }
}

echo "\n";

// ============================================================================
// TEST 6: Registration - Password Mismatch
// ============================================================================
echo "[TEST 6] REGISTRATION - PASSWORD MISMATCH\n";
echo "-----------------------------------\n\n";

$mismatchRegData = [
    'first_name' => 'Mismatch',
    'last_name' => 'Tester',
    'email' => "mismatch_test_" . time() . "@example.com",
    'password' => 'Password123',
    'password_confirm' => 'DifferentPassword123',
    'role' => 'freelancer'
];

echo "[6.1] Test Registration with Mismatched Passwords\n";
$mismatchResponse = makeRequest('POST', '/api/auth/register.php', $mismatchRegData);

if (!$mismatchResponse['success']) {
    echo "❌ Could not connect to API\n";
    recordTest('Password Mismatch Detection', false, $mismatchResponse['error']);
} else {
    if ($mismatchResponse['http_code'] == 400) {
        echo "✅ Password mismatch correctly detected (HTTP 400)\n";
        recordTest('Password Mismatch Detection', true, 'Correctly detected');
    } else {
        echo "⚠️  Got HTTP " . $mismatchResponse['http_code'] . "\n";
        recordTest('Password Mismatch Detection', $mismatchResponse['http_code'] != 201, 'HTTP ' . $mismatchResponse['http_code']);
    }
}

echo "\n";

// ============================================================================
// TEST 7: Registration - Missing Fields
// ============================================================================
echo "[TEST 7] REGISTRATION - MISSING FIELDS\n";
echo "-----------------------------------\n\n";

$missingFieldsData = [
    'first_name' => 'Incomplete',
    'email' => "missing_test_" . time() . "@example.com",
    // Missing last_name, password, password_confirm, role
];

echo "[7.1] Test Registration with Missing Fields\n";
$missingResponse = makeRequest('POST', '/api/auth/register.php', $missingFieldsData);

if (!$missingResponse['success']) {
    echo "❌ Could not connect to API\n";
    recordTest('Missing Fields Detection', false, $missingResponse['error']);
} else {
    if ($missingResponse['http_code'] == 400) {
        echo "✅ Missing fields correctly rejected (HTTP 400)\n";
        recordTest('Missing Fields Detection', true, 'Correctly detected');
    } else {
        echo "⚠️  Got HTTP " . $missingResponse['http_code'] . "\n";
        recordTest('Missing Fields Detection', $missingResponse['http_code'] != 201, 'HTTP ' . $missingResponse['http_code']);
    }
}

echo "\n";

// ============================================================================
// TEST SUMMARY
// ============================================================================
echo "================================================================================\n";
echo "API TEST SUMMARY\n";
echo "================================================================================\n\n";

echo "Total Tests: " . ($testResults['passed'] + $testResults['failed']) . "\n";
echo "✅ Passed: " . $testResults['passed'] . "\n";
echo "❌ Failed: " . $testResults['failed'] . "\n\n";

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
    echo "🎉 ALL API TESTS PASSED!\n";
    echo "✅ Registration endpoint working correctly\n";
    echo "✅ Login endpoint working correctly\n";
    echo "✅ Validation and error handling working\n";
} else {
    echo "⚠️  SOME TESTS FAILED\n";
    echo "Please review the failures and check your API endpoints.\n";
}

echo "\n";

// Test credentials
echo "================================================================================\n";
echo "TEST CREDENTIALS\n";
echo "================================================================================\n\n";
echo "Email: $testEmail\n";
echo "Password: $testPassword\n";
echo "Role: freelancer\n\n";
echo "You can use these to test the login form:\n";
echo "http://localhost/SideKick/public/login.php\n";

echo "\n";

// Cleanup instructions
echo "================================================================================\n";
echo "CLEANUP\n";
echo "================================================================================\n\n";
echo "To clean up test data from database, run:\n";
echo "DELETE FROM users WHERE email LIKE 'api_test_%';\n";
echo "DELETE FROM users WHERE email LIKE 'mismatch_test_%';\n";
echo "DELETE FROM users WHERE email LIKE 'missing_test_%';\n";

echo "\n";

ob_end_flush();
?>
