# SideKick - Project & Task Management Application

A modern, lightweight PHP-based project management and task tracking system built with MVC architecture. SideKick helps teams organize projects, track tasks, manage notes, and collaborate efficiently.

## Features

### Core Features
- **User Authentication**: Secure login and registration with password hashing
- **Dashboard**: Overview of projects, tasks, notes, and team activity
- **Project Management**: Create, edit, and track project progress
- **Task Management**: Organize tasks with status tracking (To Do, In Progress, Done)
- **Notes**: Create and search personal or shared notes
- **Team Management**: Add and manage team members with roles and contact information
- **Analytics**: Project completion metrics and performance tracking
- **User Settings**: Profile management, photo upload, and notification preferences

### Technical Features
- MVC Architecture with custom routing
- Prepared statements for SQL injection prevention
- Session-based authentication 
- Responsive dark-themed UI
- RESTful-style routing
- Modular controller/model/view separation

## Technology Stack

- **Backend**: PHP 7.0+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3 (custom dark theme)
- **Icons**: Font Awesome 6.0.0
- **Architecture**: MVC with Router pattern

## Project Structure

```
SideKick/
├── app/
│   ├── Controllers/          # Request handlers
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── ProjectController.php
│   │   ├── TaskController.php
│   │   ├── NoteController.php
│   │   ├── TeamController.php
│   │   └── SettingsController.php
│   ├── Models/               # Database access layer
│   │   ├── User.php
│   │   ├── Project.php
│   │   ├── Task.php
│   │   ├── Note.php
│   │   ├── Team.php
│   │   └── Notification.php
│   ├── Views/                # Template files
│   │   ├── layouts/
│   │   │   └── app.php      # Master template
│   │   ├── auth/
│   │   ├── dashboard/
│   │   ├── project/
│   │   ├── task/
│   │   ├── note/
│   │   ├── team/
│   │   └── settings/
│   └── Core/                 # Framework classes
│       ├── Router.php        # URL routing engine
│       ├── Controller.php    # Base controller class
│       ├── Model.php         # Base model class
│       └── Database.php      # Database connection
├── config/                   # Configuration files
│   └── Database.php
├── routes/
│   └── web.php              # Route definitions
├── public/                  # Public assets
│   ├── css/
│   │   └── style.css
│   ├── js/
│   └── uploads/             # User uploads (photos, files)
├── database/
│   ├── database.sql         # Initial schema
│   └── migration_20240101_extend_schema.sql
├── .env.example             # Environment variables template
├── .htaccess                # Apache rewrite rules
├── .gitignore               # Git ignore rules
├── index.php                # Application entry point
└── README.md                # This file
```

## Installation

### Prerequisites
- PHP 7.0 or higher
- MySQL 5.7 or higher (or MariaDB)
- Apache with mod_rewrite enabled
- Composer (optional, for package management)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/sidekick.git
   cd sidekick
   ```

2. **Configure database**
   - Create a new MySQL database named `SidekickDB`
   - Import the schema:
   ```bash
   mysql -u root -p SidekickDB < database/database.sql
   mysql -u root -p SidekickDB < database/migration_20240101_extend_schema.sql
   ```

3. **Configure environment variables**
   ```bash
   cp .env.example .env
   ```
   Edit `.env` with your database credentials:
   ```
   DB_HOST=localhost
   DB_USER=root
   DB_PASSWORD=your_password
   DB_NAME=SidekickDB
   APP_NAME=SideKick
   APP_ENV=development
   ```

4. **Set up directory permissions**
   ```bash
   chmod 755 public/uploads
   chmod 644 public/css/style.css
   ```

5. **Start the application**
   - Place the project in your web root (e.g., `/var/www/html/sidekick`)
   - Access via: `http://localhost/sidekick`

## Usage

### Authentication
1. Navigate to the login page
2. Click "Create Account" to register
3. Fill in your details and create an account
4. Log in with your credentials

### Dashboard
- View dashboard overview with key metrics
- See recent projects and active tasks
- Quick access to all features

### Projects
1. **Create Project**: Click "New Project" on dashboard
2. **View Project**: Click project name to see details
3. **Edit Project**: Update project information
4. **Delete Project**: Remove project and associated tasks

### Tasks
1. **Create Task**: Add tasks to projects
2. **Update Status**: Change task status (To Do → In Progress → Done)
3. **Filter Tasks**: View by project or status
4. **Delete Task**: Remove completed or unnecessary tasks

### Notes
1. **Create Note**: Quick note-taking feature
2. **Search Notes**: Find notes by title or content
3. **Edit/Delete**: Modify or remove notes as needed

### Team Management
1. **Add Member**: Click "Add Member" in team section
2. **Edit Member**: Update member details and role
3. **View Team**: See all team members and their roles
4. **Remove Member**: Delete team member records

### Settings
1. **Profile Tab**: Update personal information, phone, company
2. **Security Tab**: Change password
3. **Notifications Tab**: Manage email/push notification preferences

## API Routes

### Authentication Routes
- `GET/POST /login` - User login
- `GET/POST /signup` - User registration
- `GET /logout` - User logout

### Dashboard Routes
- `GET /dashboard` - Main dashboard
- `GET /dashboard/analytics` - Analytics view

### Project Routes
- `GET /projects` - List all projects
- `GET /projects/create` - Create project form
- `POST /projects` - Store new project
- `GET /projects/:id` - View project details
- `GET /projects/:id/edit` - Edit project form
- `PUT/POST /projects/:id` - Update project
- `DELETE /projects/:id` - Delete project

### Task Routes
- `GET /tasks` - List all tasks
- `GET /tasks/create` - Create task form
- `POST /tasks` - Store new task
- `GET /tasks/:id/edit` - Edit task form
- `PUT/POST /tasks/:id` - Update task
- `PUT/POST /tasks/:id/status` - Update task status
- `DELETE /tasks/:id` - Delete task

### Note Routes
- `GET /notes` - List all notes
- `GET /notes/create` - Create note form
- `POST /notes` - Store new note
- `GET /notes/:id` - View note details
- `GET /notes/:id/edit` - Edit note form
- `PUT/POST /notes/:id` - Update note
- `DELETE /notes/:id` - Delete note
- `GET/POST /notes/search` - Search notes

### Team Routes
- `GET /team` - List team members
- `GET /team/create` - Add member form
- `POST /team` - Store new member
- `GET /team/:id/edit` - Edit member form
- `PUT/POST /team/:id` - Update member
- `DELETE /team/:id` - Delete member

### Settings Routes
- `GET /settings` - Settings page (profile/security/notifications tabs)
- `POST /settings/profile` - Update profile information
- `POST /settings/password` - Change password
- `POST /settings/notifications` - Update notification preferences

## Database Schema

### Tables

#### profiles
```sql
- id (INT, PRIMARY KEY)
- name (VARCHAR)
- email (VARCHAR, UNIQUE)
- password (VARCHAR)
- employee_id (VARCHAR)
- role (VARCHAR)
- profile_photo (VARCHAR)
- phone (VARCHAR)
- company (VARCHAR)
- created_at (TIMESTAMP)
```

#### projects
```sql
- id (INT, PRIMARY KEY)
- user_id (INT, FOREIGN KEY)
- name (VARCHAR)
- progress (INT)
- due_date (DATE)
- members_count (INT)
- created_at (TIMESTAMP)
```

#### tasks
```sql
- id (INT, PRIMARY KEY)
- user_id (INT, FOREIGN KEY)
- project_id (INT, FOREIGN KEY)
- title (VARCHAR)
- status (ENUM: 'To Do', 'In Progress', 'Done')
- created_at (TIMESTAMP)
```

#### notes
```sql
- id (INT, PRIMARY KEY)
- user_id (INT, FOREIGN KEY)
- title (VARCHAR)
- content (LONGTEXT)
- created_at (TIMESTAMP)
```

#### team
```sql
- id (INT, PRIMARY KEY)
- user_id (INT, FOREIGN KEY)
- name (VARCHAR)
- role (VARCHAR)
- email (VARCHAR)
- phone (VARCHAR)
- created_at (TIMESTAMP)
```

#### user_notifications
```sql
- id (INT, PRIMARY KEY)
- user_id (INT, FOREIGN KEY, UNIQUE)
- email_notifications (TINYINT)
- push_notifications (TINYINT)
- sms_notifications (TINYINT)
- task_updates (TINYINT)
- project_updates (TINYINT)
- team_updates (TINYINT)
- created_at (TIMESTAMP)
```

## Architecture & Design Patterns

### MVC Pattern
- **Model**: Database access layer with prepared statements
- **View**: Template files with embedded PHP
- **Controller**: Business logic and request handling

### Router Pattern
```php
// Route definition
Route::post('/tasks/:id/status', 'TaskController@updateStatus');

// Router dispatches to appropriate controller and action
$router->dispatch($method, $path);
```

### Base Classes
- **Controller**: Extends with `view()` method for rendering
- **Model**: Extends with `execute()` method for database queries
- **Database**: Singleton pattern for connection management

### Security Features
- Prepared statements prevent SQL injection
- Password hashing with `password_hash()` / `password_verify()`
- Session validation on protected routes
- Input sanitization with `htmlspecialchars()`

## Configuration

### Environment Variables (.env)
```
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=password
DB_NAME=SidekickDB
APP_NAME=SideKick
APP_ENV=development
```

### CSS Theme Variables
The application uses CSS variables for theming:
```css
--bg: #0b0b14              /* Main background */
--sidebar-bg: #161625      /* Sidebar background */
--card-bg: #1a1a2e         /* Card background */
--border: #2a2a3e          /* Border color */
--text: #ffffff            /* Text color */
--text-dim: #a0a0b0        /* Dim text */
--pink: #ff007a            /* Primary accent */
--purple: #8a2be2          /* Secondary accent */
```

## Development

### Creating a New Controller
```php
<?php
namespace App\Controllers;

class CustomController extends Controller {
    public function index() {
        return $this->view('custom/index', ['data' => []]);
    }
}
```

### Creating a New Model
```php
<?php
namespace App\Models;

class Custom extends Model {
    protected $table = 'custom_table';
    
    public function getAll() {
        return $this->execute("SELECT * FROM {$this->table}");
    }
}
```

### Adding a New Route
```php
// In routes/web.php
Route::get('/custom', 'CustomController@index');
Route::post('/custom', 'CustomController@store');
```

## Troubleshooting

### 404 Errors
- Ensure `.htaccess` is enabled with `mod_rewrite`
- Check route definitions in `routes/web.php`
- Verify controller and action names

### Database Connection Errors
- Verify database credentials in `.env`
- Ensure MySQL service is running
- Check that `SidekickDB` database exists

### File Upload Issues
- Ensure `public/uploads` directory exists and is writable
- Check file size limits in PHP configuration
- Verify file extension is allowed

### Session Issues
- Ensure PHP sessions are enabled
- Check session save path permissions
- Clear browser cookies if necessary

## Performance Optimization

- Use indexes on frequently queried columns
- Cache frequently accessed data
- Optimize database queries with EXPLAIN
- Minify CSS and JavaScript
- Enable gzip compression

## Security Best Practices

- Keep PHP and dependencies updated
- Use HTTPS in production
- Implement CSRF tokens for state-changing operations
- Validate and sanitize all user input
- Use environment variables for sensitive data
- Implement rate limiting on authentication
- Regular security audits

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is licensed under the MIT License - see LICENSE file for details.

## Support

For issues, questions, or suggestions, please open an issue on GitHub or contact the development team.

## Changelog

### Version 1.0.0
- Initial release
- Core MVC framework
- Authentication system
- Project, Task, Note management
- Team management
- User settings and notifications
- Analytics dashboard

## Author

SideKick Development Team

---

**Last Updated**: January 2024
**Status**: Active Development
