# SideKick Authentication Testing - Quick Start Guide

## 🚀 Quick Start (5 Minutes)

### Step 1: Initialize Database
**URL:** `http://localhost/SideKick/test/init_database.php`

- Click the link above in your browser
- Wait for all tables to be created
- Note the admin credentials displayed

**Status:** ✅ You should see "DATABASE INITIALIZATION COMPLETE"

---

### Step 2: Run Core Tests
**URL:** `http://localhost/SideKick/test/auth_test_suite.php`

This test will:
- ✅ Verify database connection
- ✅ Create a test user
- ✅ Test password hashing
- ✅ Test login logic
- ✅ Test session management
- ✅ Verify data persistence

**Expected Result:**
```
Total Tests: XX
✅ Passed: XX
❌ Failed: 0

🎉 ALL TESTS PASSED!
```

**Save these credentials from the output:**
```
Test user email: testuser_XXXXXXXXX@example.com
Test user password: TestPassword123!
```

---

### Step 3: Run API Tests
**URL:** `http://localhost/SideKick/test/api_auth_test.php`

This test will:
- ✅ Test registration endpoint
- ✅ Test login endpoint  
- ✅ Test validation and error handling
- ✅ Test duplicate email prevention
- ✅ Test password mismatch detection

**Expected Result:**
```
Total Tests: XX
✅ Passed: XX
❌ Failed: 0

🎉 ALL API TESTS PASSED!
```

---

### Step 4: Test Login Form
**URL:** `http://localhost/SideKick/public/login.php`

**Test These Features:**

1. **Dark Mode Toggle** 🌙
   - Click the moon icon in top-right
   - Page should switch to dark theme
   - Refresh - theme should persist

2. **Password Visibility** 👁️
   - Type a password
   - Click eye icon to show/hide

3. **Login Test**
   - Enter the test email from Step 2
   - Enter the test password: `TestPassword123!`
   - Click "Sign In"
   - You should see success message

4. **Register Test**
   - Click "Create an account"
   - Fill in the form
   - Watch password strength meter
   - Click "Create Account"

---

### Step 5: Verify Database
**URL:** Use any database client or check via test output

```sql
-- List all users
SELECT user_id, email, role, is_active, last_login FROM users;

-- List test users
SELECT * FROM users WHERE email LIKE '%test%';

-- Check session table
SELECT COUNT(*) FROM sessions;
```

---

## 📊 Test Coverage

| Component | Test File | Status |
|-----------|-----------|--------|
| Database Connection | auth_test_suite.php | ✅ |
| User Registration | auth_test_suite.php | ✅ |
| Password Hashing | auth_test_suite.php | ✅ |
| User Login | auth_test_suite.php | ✅ |
| Session Management | auth_test_suite.php | ✅ |
| Data Persistence | auth_test_suite.php | ✅ |
| API Registration | api_auth_test.php | ✅ |
| API Login | api_auth_test.php | ✅ |
| Validation | api_auth_test.php | ✅ |
| Error Handling | api_auth_test.php | ✅ |
| Frontend Forms | Manual Testing | ✅ |
| Dark Mode | Manual Testing | ✅ |
| Password Visibility | Manual Testing | ✅ |

---

## 🔧 Troubleshooting

### Database Connection Failed
```
Error: "Database connection: FAILED"
```
**Solution:**
1. Ensure MySQL service is running
2. Check `/config/db.php` for correct credentials
3. Run `init_database.php` first

### Test User Not Created
```
Error: "User registered: FAILED"
```
**Solution:**
1. Check database write permissions
2. Ensure `/sessions` directory is writable
3. Check error logs: `/logs/error.log`

### API Tests Failing (404)
```
Error: "Could not connect to API"
```
**Solution:**
1. Verify Apache `mod_rewrite` is enabled
2. Check `.htaccess` exists in root directory
3. Verify API file paths are correct

### Login Always Fails
```
Error: "Invalid email or password"
```
**Solution:**
1. Verify test user was created (check Step 2 output)
2. Try using admin credentials: `admin@test.com` / `AdminPass123!`
3. Check database has users table

---

## ✨ Key Features Tested

### Registration ✅
- ✅ Valid email format
- ✅ Password hashing with bcrypt
- ✅ Duplicate email prevention
- ✅ Required field validation
- ✅ Password confirmation matching
- ✅ Role-based profile creation
- ✅ Transaction rollback on error

### Login ✅
- ✅ Email lookup
- ✅ Password verification
- ✅ Account status check
- ✅ Session creation
- ✅ Last login timestamp
- ✅ Invalid credential rejection
- ✅ Non-existent user handling

### Security ✅
- ✅ Bcrypt password hashing
- ✅ SQL injection prevention (prepared statements)
- ✅ Session security (HTTPOnly cookies)
- ✅ CSRF token support
- ✅ Input sanitization
- ✅ Error logging

### Frontend ✅
- ✅ Responsive design (Tailwind CSS)
- ✅ Dark mode toggle with persistence
- ✅ Password visibility toggle
- ✅ Form validation
- ✅ Password strength meter
- ✅ Error messages

---

## 📝 Test Data Cleanup

After testing, remove test users:

```sql
-- Delete all test users
DELETE FROM users WHERE email LIKE '%test%';

-- Delete specific test user by ID
DELETE FROM users WHERE user_id = 123;

-- Verify cleanup
SELECT COUNT(*) as remaining_users FROM users;
```

---

## 🎯 Success Criteria

✅ **All tests pass** - No ❌ failures  
✅ **Data saves to database** - Users table has new entries  
✅ **Login works** - Can login with test credentials  
✅ **Sessions persist** - Session data retained across requests  
✅ **Dark mode works** - Theme toggle functions correctly  
✅ **Form validation** - Invalid inputs are rejected  
✅ **Password strength** - Meter updates in real-time  
✅ **Error handling** - Meaningful error messages displayed  

---

## 📚 Detailed Testing

For more detailed testing information, see: **`TESTING_GUIDE.md`**

Topics covered:
- Advanced testing scenarios
- Performance benchmarks
- Security verification
- Integration checklist
- Troubleshooting guide

---

## 🔗 Quick Links

| Page | URL |
|------|-----|
| Database Init | `http://localhost/SideKick/test/init_database.php` |
| Core Tests | `http://localhost/SideKick/test/auth_test_suite.php` |
| API Tests | `http://localhost/SideKick/test/api_auth_test.php` |
| Login Form | `http://localhost/SideKick/public/login.php` |
| Register Form | `http://localhost/SideKick/public/register.php` |
| Testing Guide | `test/TESTING_GUIDE.md` |

---

## 💡 Pro Tips

1. **Save Test Credentials** - Copy the email and password displayed after Step 2
2. **Check Error Logs** - Look in `/logs/error.log` if anything fails
3. **Browser DevTools** - Use Network tab to inspect API calls
4. **Database Client** - Use PHPMyAdmin or similar to verify data
5. **Clear Cache** - If dark mode doesn't persist, clear browser cache

---

## ✅ Final Checklist

Before deployment:

- [ ] Database initialized successfully
- [ ] All core tests passing
- [ ] All API tests passing
- [ ] Login form works manually
- [ ] Register form works manually
- [ ] Dark mode persists
- [ ] Password visibility toggle works
- [ ] Form validation prevents invalid input
- [ ] Data visible in database
- [ ] No errors in log files

---

**Ready to test?** Start with: `http://localhost/SideKick/test/init_database.php`

Need help? Check `TESTING_GUIDE.md` for detailed information.

---

*Last Updated: May 10, 2026*  
*Authentication Testing - Version 1.0*
