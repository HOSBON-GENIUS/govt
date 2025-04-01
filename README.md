# Kenya Government Projects Accountability Platform

This is a lightweight web application developed using PHP (Procedural), MySQL, and Bootstrap. It allows citizens, project managers, and auditors to interact with government projects transparently through comments, feedback, and audits.

---

##  Features
- View detailed government projects
- Citizens (or guests) can comment using text, image, or video
- Project managers can create, update, and manage projects
- Auditors can post audit reports
- Admin dashboard for user and project oversight

---

##  Project Structure
```
/htdocs/govt
│
├── assets/                   # Images, styles, or frontend JS assets
├── vendor/                   # Composer packages (e.g., JWT library)
├── uploads/                  # User-submitted image/video files
│
├── config.php                # Database connection (PDO)
├── session.php               # Session config or session check
├── auth.php                  # Auth helper for verifying roles/access
│
├── index.php                 # Homepage: shows list of projects
├── navbar.php                # Reusable navigation bar
│
├── project.php               # View specific project details + comment form
├── view_project.php          # Load/display projects via AJAX (used by index)
│
├── add_project_page.php      # UI form for project managers to add a project
├── create_project.php        # Backend script to insert a project
├── edit_project.php          # UI form for project managers to edit a project
├── update_project.php        # Backend to update project info
│
├── add_comment.php           # Backend to add comment (with optional media)
├── view_comments.php         # Load/display comments for a project
│
├── post_audit.php            # Auditors post an audit (likely attached to project)
├── view_audits.php           # View audits for a given project
│
├── admin.php                 # Admin dashboard
├── admin_login.php           # Admin login page
├── verify_admin.php          # Confirms token/session role is admin
│
├── login.php                 # Backend login logic (sets session/token)
├── login_page.php            # Login form UI
├── logout.php                # Logout and destroy session
│
├── register.php              # Backend logic to register users
├── register_page.php         # UI form for user registration
│
├── pm_dashboard.php          # Project manager dashboard (list + actions)
│
├── fetch_projects.php        # Fetches project data for JS/AJAX use
├── fetch_comments.php        # Fetches comments data (likely unused now)
├── fetch_audits.php          # Fetches audits for admin or AJAX UI
├── fetch_users.php           # Fetches user data (admin view?)
│
├── composer.json             # Composer dependency list
├── composer.lock             # Composer lock file
└── .gitignore                # Git ignored files and folders

---

## Setup Instructions

### 1. Prerequisites
- XAMPP/WAMP installed (Apache + MySQL)
- PHP 7.4+ recommended

### 2. Database Setup
- Import the `govt_db` structure into phpMyAdmin:
```sql
CREATE DATABASE govt_db;
-- Then import tables (project, comment, media, user, etc.)
```

### 3. Configure Database
Edit `config.php`:
```php
$host = 'localhost';
$db = 'govt_db';
$user = 'root';
$pass = ''; // or your MySQL password
```

### 4. Folder Permissions
- Ensure `/uploads/` is writable (for image/video uploads)

### 5. Run Locally
Visit:
```
http://localhost/govt/index.php
```

---

## Testing
- Create a test project as a project manager
- Comment as guest with/without media
- Confirm uploads appear correctly

---

## Deployment
When ready, upload the entire `govt` folder to your web host (e.g., Bluehost) under `public_html/`, import the database, and update credentials in `config.php`.

---

## Feedback
For questions, improvements, or bugs, please contact the developer or submit suggestions via email or GitHub (if hosted).

---

> Built with simplicity and transparency in mind 🇰🇪
