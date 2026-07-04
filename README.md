# IJFER - Indian Journals for Engineering and Research

A web-based research journal management platform for managing paper submissions, peer review workflows, user authentication, and journal administration.

## Problem it solves / Motivation

IJFER streamlines the process of academic journal management by providing a centralized platform for:
- Authors to submit research papers with ease
- Administrators to manage submissions, review statuses, and editorial board
- Readers to access published papers and archives
- Ensuring consistent data organization and rapid publication workflows

## Tech Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| PHP | 8.0+ | Backend logic and server-side rendering |
| MySQL / MariaDB | 10.4+ | Database for storing users, papers, news, etc. |
| Bootstrap | 5.3.2 | Frontend UI framework |
| Font Awesome | 6.4.0 | Icons |
| PDO | - | Database abstraction layer |

## Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                        Frontend (Browser)                        │
│  ┌───────────────┐  ┌───────────────┐  ┌───────────────────┐   │
│  │   Public UI   │  │  Author UI    │  │   Admin Dashboard │   │
│  │  (index.php)  │  │(submit-paper) │  │   (admin/)        │   │
│  └───────┬───────┘  └───────┬───────┘  └─────────┬─────────┘   │
└──────────┼──────────────────┼─────────────────────┼─────────────┘
           │                  │                     │
           └──────────────────┼─────────────────────┘
                              │
                    ┌─────────▼─────────┐
                    │   PHP Backend     │
                    │ (Session-based)   │
                    └─────────┬─────────┘
                              │
                    ┌─────────▼─────────┐
                    │   MySQL / MariaDB │
                    │   (ijfer DB)      │
                    └───────────────────┘
```

## Folder Structure

```
iejmir/
├── admin/                      # Admin dashboard and management
│   ├── assets/                 # Admin UI assets (CSS, JS, images)
│   ├── config/                 # Configuration files
│   │   ├── auth.php            # Authentication checks
│   │   ├── db.php              # Database connection
│   │   ├── login.php           # Admin login
│   │   └── logout.php          # Admin logout
│   ├── author/                 # Paper management for admins
│   │   ├── delete_paper.php
│   │   ├── manage_paper.php
│   │   └── update_status.php
│   ├── uploads/                # File uploads for papers and editorial
│   ├── components/             # Reusable admin UI components
│   └── [admin pages]
├── assets/                     # Public UI assets
│   ├── css/
│   ├── js/
│   └── images/
├── components/                 # Reusable public UI components
│   ├── header.php
│   ├── footer.php
│   ├── navbar.php
│   └── sidebar.php
├── pages/                      # Public and author-facing pages
│   ├── about/                  # About the journal pages
│   ├── archives/               # Archive pages
│   └── author/                 # Author tools (submit, track, etc.)
├── uploads/                    # Public uploads (templates, etc.)
├── index.php                   # Homepage entry point
├── ijfer.sql                   # Database schema and sample data
└── [other public pages]
```

## Setup & Installation

1. **Requirements**
   - PHP 8.0 or higher
   - MySQL/MariaDB 10.4 or higher
   - Web server (Apache/Nginx)
   - Composer (optional, not required for this project)

2. **Database Setup**
   - Create a MySQL database named `ijfer`
   - Import `ijfer.sql` to create tables and sample data
   - Update database credentials in `admin/config/db.php` if needed:
     ```php
     $host = "localhost";
     $dbname = "ijfer";
     $username = "root";
     $password = "";
     ```

3. **Web Server Setup**
   - Place the project folder in your web server's document root
   - Ensure the `admin/uploads/` and `uploads/` directories are writable by the web server

4. **Default Admin Credentials**
   - Email: `admin@ijfer.com`
   - Password: `admin123` (hashed in database)

## How to Run, Build, Test, and Deploy

### Running the Application
- Start your web server and MySQL server
- Navigate to `http://localhost/iejmir/` in your browser

### Building
- No build step required - this is a traditional PHP application with static frontend assets

### Testing
- No automated test suite is included in the codebase
- Manual testing can be performed by submitting papers, managing the admin dashboard, etc.

### Deployment
- Deploy to any PHP-compatible hosting provider
- Ensure database credentials are updated for production environment
- Set appropriate file permissions for upload directories
- Use HTTPS in production for security

## Key Features

- **Paper Submission System**: Authors can submit papers with multiple authors, abstracts, and Word document uploads
- **Unique Paper ID Generation**: Automatically generates unique IDs for each submission
- **Paper Status Tracking**: Track papers through `submitted`, `under_review`, `accepted`, and `rejected` statuses
- **Admin Dashboard**: Complete management interface for papers, news, editorial board, and contact messages
- **News & Announcements**: Publish and manage news items with PDF attachments
- **Editorial Board Management**: Maintain editorial board members with roles and expertise
- **Contact Form**: Handle inquiries from visitors
- **Responsive UI**: Mobile-friendly frontend using Bootstrap 5

## Database Schema

The database `ijfer` contains the following tables:

| Table | Purpose |
|-------|---------|
| `users` | Admin and user accounts |
| `papers` | Submitted research papers with metadata and file paths |
| `news` | News and announcements |
| `editorial_board` | Editorial board members |
| `contact_messages` | Messages from contact form |
| `paper_templates` | Paper templates for authors |

## Known Limitations / Future Improvements

- **Authentication**: The current authentication system uses simple session-based auth; consider implementing more secure methods (JWT, OAuth2)
- **Input Validation**: Add more robust input validation and sanitization for all user inputs
- **File Uploads**: Implement better file security checks (virus scanning, stricter MIME type validation)
- **Testing**: Add automated testing (unit tests, integration tests)
- **Role-Based Access**: Implement more granular role-based access control (RBAC)
- **API**: Add a REST API for third-party integrations
- **Plagiarism Check**: Integrate a plagiarism checking service
- **Email Notifications**: Implement more comprehensive email notification system using a proper email service (SMTP) instead of PHP's `mail()` function

## License & Author

This project was developed for IJFER (Indian Journals for Engineering and Research).
