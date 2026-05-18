# 📚 SideKick Developer Guide & Project Status

## Overview
SideKick is a role-based project management and freelance collaboration platform built with PHP 8.0, MySQL, and Tailwind CSS.

**Current Phase**: Phase 1 - Core System & Dashboard Testing
**Status**: 🟢 Ready for Testing

---

## Architecture

### Tech Stack
- **Backend**: PHP 8.0.30 with PDO
- **Database**: MySQL/MariaDB (11 tables, normalized schema)
- **Frontend**: HTML5, Tailwind CSS 3.4.19, Vanilla JavaScript
- **Session Management**: PHP Sessions with 30-minute timeout
- **Authentication**: Bcrypt password hashing, role-based access control

### Directory Structure
```
SideKick/
├── api/                      # RESTful API endpoints
│   ├── auth/                # Authentication (login, register)
│   ├── tasks/               # Task CRUD (complete)
│   └── dashboard/           # Dashboard data aggregation
├── public/                   # Frontend pages
│   ├── admin/               # Admin dashboard
│   ├── project_manager/     # Project manager dashboard
│   ├── freelancer/          # Freelancer dashboard
│   ├── client/              # Client dashboard
│   ├── login.php            # Authentication entry point
│   └── register.php         # Account creation
├── includes/                # Reusable components
│   ├── partials/            # UI components
│   ├── sidebar.php          # Navigation sidebar
│   ├── auth_guard.php       # Authentication middleware
│   ├── helpers.php          # Utility functions
│   └── router.php           # Route definitions
├── config/                  # Configuration files
│   ├── config.php           # App constants and settings
│   └── db.php               # Database connection
├── database/                # Database schema
│   └── schema.sql           # Complete schema with seed data
├── assets/                  # Static files
│   ├── css/                 # Tailwind compiled output
│   ├── js/                  # JavaScript utilities
│   └── images/              # Images and icons
├── test/                    # Testing utilities
│   ├── verify_auth_data.php # Authorization/data verification
│   ├── QUICK_START.md       # Quick start guide
│   ├── README.md            # Testing README
│   └── TESTING_GUIDE.md     # Testing procedures
├── IMPROVEMENT_PLAN.md      # Feature roadmap
├── DEPLOYMENT_CHECKLIST.md  # Pre-deployment checklist
└── README.md                # Project documentation
```

---

## User Roles & Permissions

| Role | Purpose | Dashboards | Features |
|------|---------|-----------|----------|
| **Admin** | System administration | System stats, users, projects, tasks | Full system control |
| **Manager** | Project oversight | Assigned projects, team management | Create/manage projects |
| **Freelancer** | Work execution | Active tasks, time tracking | Submit work, track hours |
| **Client** | Project commission | Posted projects, invoices | Create projects, pay invoices |

---

## Test Credentials

```
┌─────────────────┬──────────────────────────┬─────────────────┐
│ Role            │ Email                    │ Password        │
├─────────────────┼──────────────────────────┼─────────────────┤
│ Admin           │ admin@test.com           │ AdminPass123    │
│ Manager         │ manager@test.com         │ ManagerPass123  │
│ Freelancer      │ freelancer1@test.com     │ Freelancer123   │
│ Client          │ client@test.com          │ ClientPass123   │
└─────────────────┴──────────────────────────┴─────────────────┘
```

**All credentials verified and tested** ✅

---

## API Endpoints Reference

### Authentication
```
POST   /SideKick/api/auth/login.php         → User login
POST   /SideKick/api/auth/register.php      → User registration
GET    /SideKick/api/auth/check_session.php → Session status
POST   /SideKick/api/auth/logout.php        → User logout
```

### Dashboard
```
GET    /SideKick/api/dashboard/summary.php  → Stats & recent data
```
Response includes:
- Role-specific statistics
- Recent projects (4)
- My tasks (5)
- Recent activity (10 items)

### Tasks
```
GET    /SideKick/api/tasks/list.php         → List tasks
POST   /SideKick/api/tasks/create.php       → Create task
PUT    /SideKick/api/tasks/update.php       → Update task
DELETE /SideKick/api/tasks/delete.php       → Delete task
GET    /SideKick/api/tasks/details.php      → Get task details
```

### Planned APIs
```
Projects:  /SideKick/api/projects/*
Invoices:  /SideKick/api/invoices/*
Payments:  /SideKick/api/payments/*
```

---

## Session Management

### Session Variables
After login, these variables are set in `$_SESSION`:
```php
$_SESSION['user_id']        // Unique user identifier
$_SESSION['email']          // User email
$_SESSION['user_role']      // Role (admin|manager|freelancer|client)
$_SESSION['user_first_name']// First name from profile table
$_SESSION['user_last_name'] // Last name from profile table
$_SESSION['session_created']// Timestamp of session creation
$_SESSION['last_activity']  // Last activity timestamp
```

### Session Security
- HTTPOnly cookies enabled (no JavaScript access)
- SameSite=Lax configured
- 30-minute session timeout
- Session ID regeneration on login
- Secure flag for HTTPS

---

## Database Schema Highlights

### Core Tables
- **users**: User accounts with authentication
- **freelancers**: Freelancer profiles
- **project_manager**: Project manager profiles
- **client**: Client company profiles

### Feature Tables
- **project**: Projects with status and budget
- **task**: Project tasks with priorities
- **time_log**: Time tracking records
- **invoice**: Invoice generation
- **payment**: Payment tracking
- **notification**: User notifications
- **activity**: Activity audit log

All tables use:
- AUTO_INCREMENT primary keys
- UTF8MB4 character set
- Foreign key constraints
- Indexes on frequently queried columns

---

## Development Workflow

### Local Setup
1. Clone repository to `c:\xampp\htdocs\SideKick`
2. Ensure XAMPP running with MySQL
3. Import `database/schema.sql` into MySQL
4. Verify database connection: `test/verify_auth_data.php`
5. Test login with provided credentials
6. Access dashboards based on role

### Code Standards
- **PHP**: PSR-12 style guide
- **JS**: Vanilla JavaScript, no frameworks
- **CSS**: Tailwind CSS utilities
- **Database**: Prepared statements only, no string concatenation
- **Security**: Input sanitization, output escaping, CSRF tokens

### Before Committing
1. Run `php test/verify_auth_data.php` (should all PASS)
2. Test all user roles on local instance
3. Check for any `var_dump()`, `console.log()` in code
4. Verify no hardcoded credentials
5. Test responsive design (mobile/tablet)

---

## Common Tasks

### Adding a New Feature
1. Define API endpoint in `api/` directory
2. Add frontend in `public/` directory
3. Create component partial in `includes/partials/` if needed
4. Add route to `includes/router.php`
5. Test with all 4 roles
6. Document in API reference

### Fixing a Bug
1. Reproduce with specific role and action
2. Check error logs in `logs/` directory
3. Add test case in `test/verify_auth_data.php`
4. Fix in appropriate component
5. Re-run verification script
6. Document in commit message

### Testing API Endpoint
1. Use Postman or cURL
2. Include session cookie or CSRF token if needed
3. Test success case
4. Test with invalid data
5. Test with unauthorized user
6. Check error response structure

---

## Known Limitations & TODOs

### Current Phase 1 Limitations
- ⏳ Projects CRUD not yet implemented (routes defined)
- ⏳ Invoicing system incomplete
- ⏳ Payment processing not implemented
- ⏳ Notifications basic implementation only
- ⏳ No email notifications

### Performance Optimizations Pending
- Database query optimization for large datasets
- API response caching
- Asset minification and compression
- Image optimization

### Security Enhancements Pending
- API rate limiting
- Two-factor authentication
- Password reset flow
- Email verification

---

## Troubleshooting

### Login Not Working
1. Verify database connection: `test/verify_auth_data.php`
2. Check password verified: Review test credentials section
3. Ensure session folder permissions: `sessions/` (755)
4. Check PHP session save path: `config/config.php`

### Dashboard Not Loading Data
1. Check API endpoint: `/api/dashboard/summary.php`
2. Verify user is authenticated: Check session variables
3. Check database connection in `config/db.php`
4. Review browser console for JavaScript errors
5. Check error logs: `logs/` directory

### Database Connection Failed
1. Verify MySQL is running in XAMPP
2. Check credentials in `config/db.php`
3. Verify database exists: `sidekick_db`
4. Check user permissions in MySQL
5. Test connection: `test/verify_auth_data.php`

---

## Next Developer Notes

### Priority Checklist
- [ ] Understand 4 roles and their permission levels
- [ ] Test login with all 4 roles
- [ ] Review database schema in `database/schema.sql`
- [ ] Familiarize with API response structure
- [ ] Test responsive design on mobile
- [ ] Read IMPROVEMENT_PLAN.md for roadmap
- [ ] Review DEPLOYMENT_CHECKLIST.md before production

### Questions to Ask
1. Which features are highest priority?
2. What's the timeline for production?
3. Do we need mobile app support?
4. What payment processor will be used?
5. Is there a marketing email requirement?

---

## Documentation Files

- **README.md**: Project overview
- **IMPROVEMENT_PLAN.md**: Feature roadmap and phasing
- **DEPLOYMENT_CHECKLIST.md**: Pre-deployment requirements
- **test/QUICK_START.md**: Quick setup guide
- **test/TESTING_GUIDE.md**: Testing procedures

---

**Last Updated**: 2026-05-18
**Project Phase**: Phase 1 - Testing & Validation
**Status**: 🟢 Ready for Phase 1 Testing
