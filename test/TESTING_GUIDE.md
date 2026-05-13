# SideKick Authentication Testing Guide

## Overview
This document provides comprehensive testing procedures for the SideKick authentication system, including registration, login, and database persistence.

---

## Test Files Available

### 1. **auth_test_suite.php** - Core Authentication Tests
- **Purpose**: Tests database connection, registration logic, login logic, and session management
- **URL**: `http://localhost/SideKick/test/auth_test_suite.php`
- **Tests Performed**:
  - Database connection verification
  - Email validation
  - Duplicate email detection
  - User registration to database
  - User data fetching
  - Password hashing and verification
  - Session creation and persistence
  - Account status checks
  - Last login timestamp updates
  - Data persistence verification
  - Edge cases (invalid email, short password, etc.)

**Result**: Creates a test user in the database that you can use for login testing

---

### 2. **api_auth_test.php** - API Endpoint Tests
- **Purpose**: Tests the actual API endpoints that the frontend uses
- **URL**: `http://localhost/SideKick/test/api_auth_test.php`
- **Tests Performed**:
  - Registration API endpoint
  - Login API endpoint with valid credentials
  - Login with invalid password
  - Login with non-existent email
  - Duplicate email prevention
  - Password mismatch detection
  - Missing fields validation
  - HTTP status code verification

**Result**: Tests all API endpoints and validates error handling

---

## Testing Workflow

### Step 1: Run Core Authentication Tests
1. Open browser: `http://localhost/SideKick/test/auth_test_suite.php`
2. Review all test results (should all be ✅)
3. Note the test user credentials displayed at the end

**Expected Output**:
```
Total Tests: XX
✅ Passed: XX
❌ Failed: 0

🎉 ALL TESTS PASSED!
```

---

### Step 2: Run API Endpoint Tests
1. Open browser: `http://localhost/SideKick/test/api_auth_test.php`
2. Review all test results (should all be ✅)
3. Verify HTTP status codes are correct

**Expected Output**:
- Registration: HTTP 201 (Created)
- Valid Login: HTTP 200 (OK)
- Invalid Password: HTTP 401 (Unauthorized)
- Non-existent Email: HTTP 401 (Unauthorized)
- Duplicate Email: HTTP 409 (Conflict)
- Missing Fields: HTTP 400 (Bad Request)

---

### Step 3: Manual Frontend Testing
1. Go to: `http://localhost/SideKick/public/login.php`
2. You'll see the Tailwind-styled login page
3. Test the following:

#### 3.1 Dark Mode Toggle
- Click the 🌙 button in top-right
- Verify page switches to dark theme
- Click again to switch back to light theme
- Refresh page - dark mode should persist (saved in localStorage)

#### 3.2 Password Visibility Toggle
- Enter a password in the password field
- Click the 👁️ eye icon
- Password should become visible
- Click again - password should be hidden

#### 3.3 Test Registration
- Click "Create an account" link (bottom of page or left panel)
- Go to: `http://localhost/SideKick/public/register.php`
- Fill in form with test data
- Watch password strength meter update in real-time
- Enter password and verify strength colors:
  - Red (weak)
  - Orange (fair)
  - Blue (good)
  - Green (strong)
- Check the requirements list updates with ✓ marks
- Click "Create Account"

#### 3.4 Test Login
- Go back to login page
- Use credentials from the test suite output
- Example: `testuser_1234567890@example.com` / `TestPassword123!`
- Click "Sign In"
- If login is successful, you should see a success alert
- Session should be created and persisted

#### 3.5 Test Form Validation
- Try submitting with empty fields
- Try entering invalid email format
- Try entering mismatched passwords on register
- Try entering password shorter than 8 characters

---

## Expected Test Results

### Successful Test Output

#### Database Tests ✅
```
✅ Database connection: SUCCESS
✅ Users table exists
✅ Email format valid
✅ Email not in database (available for registration)
✅ User registered successfully (User ID: XXX)
✅ User found in database
✅ Password verification successful
✅ Wrong password correctly rejected
✅ Account is active
✅ Last login updated
```

#### API Tests ✅
```
✅ API connection successful
✅ Registration successful (HTTP 201)
✅ Login successful (HTTP 200)
✅ Invalid password correctly rejected (HTTP 401)
✅ Non-existent email correctly rejected (HTTP 401)
✅ Duplicate email correctly rejected (HTTP 409)
✅ Password mismatch correctly detected (HTTP 400)
✅ Missing fields correctly rejected (HTTP 400)
```

#### Session Tests ✅
```
✅ Session started
✅ Session data set
✅ Session data persists
✅ User is authenticated
```

#### Data Persistence Tests ✅
```
✅ User data persisted in database
✅ Profile data persisted
```

---

## Database Schema Verification

After running tests, verify database tables:

```sql
-- Check users table
SELECT * FROM users WHERE email LIKE '%test%' LIMIT 1;

-- Check freelancer profile
SELECT * FROM freelancers WHERE user_id = (
  SELECT user_id FROM users WHERE email LIKE '%test%' LIMIT 1
);

-- Check last login was updated
SELECT user_id, email, last_login FROM users WHERE email LIKE '%test%';
```

---

## Test Data Cleanup

After testing, remove test data:

```sql
-- Option 1: Delete by email pattern
DELETE FROM users WHERE email LIKE 'testuser_%';
DELETE FROM users WHERE email LIKE 'api_test_%';
DELETE FROM users WHERE email LIKE 'mismatch_test_%';
DELETE FROM users WHERE email LIKE 'missing_test_%';

-- Option 2: Delete by specific user_id
DELETE FROM freelancers WHERE user_id = 123;
DELETE FROM users WHERE user_id = 123;

-- Verify cleanup
SELECT COUNT(*) FROM users;
```

---

## Troubleshooting

### Issue: "Database connection: FAILED"
- **Solution**: Check database credentials in `/config/db.php`
- Ensure MySQL service is running
- Verify database `sidekick_db` exists
- Check user permissions

### Issue: "Users table exists" but shows error
- **Solution**: Run database schema import
```bash
mysql -u root -p sidekick_db < database/schema.sql
```

### Issue: API endpoints return 404
- **Solution**: 
- Verify `.htaccess` is in root directory
- Check Apache `mod_rewrite` is enabled
- Verify file paths are correct

### Issue: Session tests failing
- **Solution**:
- Check `/sessions` directory exists and is writable
- Verify session save path in `/config/config.php`
- Clear browser cookies and try again

### Issue: Password hashing failures
- **Solution**:
- Check PHP version (bcrypt requires PHP 5.5+)
- Verify `PASSWORD_DEFAULT` constant is available
- Check error logs: `/logs/error.log`

---

## Performance Benchmarks

Expected performance times:

| Operation | Time |
|-----------|------|
| Database Connection | < 100ms |
| User Registration | 100-200ms |
| User Login | 150-250ms |
| Session Creation | < 50ms |
| Password Verification | 200-300ms |

---

## Security Verification Checklist

- ✅ Passwords are hashed with bcrypt
- ✅ SQL injection prevention (prepared statements)
- ✅ CSRF tokens are generated
- ✅ Session security (HTTPOnly cookies)
- ✅ Duplicate email prevention
- ✅ Account status validation
- ✅ Password strength enforcement
- ✅ Failed login attempt logging
- ✅ Session timeout configured
- ✅ Input sanitization implemented

---

## Integration Checklist

After tests pass, verify integration:

- ✅ Frontend login form sends data to `/api/auth/login.php`
- ✅ Frontend register form sends data to `/api/auth/register.php`
- ✅ Session data is accessible across pages
- ✅ User profile data loads correctly after login
- ✅ Dark mode preference persists across sessions
- ✅ Password strength meter updates in real-time
- ✅ Form validation prevents invalid submissions
- ✅ Error messages are user-friendly

---

## Next Steps

1. ✅ Run `auth_test_suite.php` - verify database and core logic
2. ✅ Run `api_auth_test.php` - verify API endpoints
3. ✅ Test login form manually
4. ✅ Test register form manually
5. ✅ Test dark mode toggle
6. ✅ Test password visibility toggle
7. ✅ Test session persistence
8. ✅ Verify data in database
9. Clean up test data
10. Document any issues found

---

## Support

For issues or questions:
1. Check error logs: `/logs/error.log`
2. Review test output for specific failures
3. Check database connectivity
4. Verify file permissions
5. Review API endpoint configuration

---

**Last Updated**: May 10, 2026  
**Status**: Testing Phase  
**Version**: 1.0
