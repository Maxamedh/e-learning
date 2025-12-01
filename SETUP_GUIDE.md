# E-LOOX Academy - Setup Guide

## Overview
This is a comprehensive e-learning platform built with CodeIgniter 4, featuring:
- **Admin/Staff Section**: For managing courses, users, orders, and system settings
- **Student Portal**: For browsing, enrolling, and learning from courses

## Database Setup

### Step 1: Create Database
1. Open phpMyAdmin or MySQL command line
2. Create a new database:
```sql
CREATE DATABASE `e-learning` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

### Step 2: Import Schema
1. Run the SQL file `database_schema.sql` located in the project root
2. This will create all necessary tables with proper relationships

### Step 3: Create Admin User
After importing the schema, create your first admin user:

```sql
INSERT INTO users (uuid, email, password_hash, role, first_name, last_name, is_active, email_verified) 
VALUES (
    UUID(),
    'admin@elooxacademy.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'admin',
    'Admin',
    'User',
    TRUE,
    TRUE
);
```

**Default Admin Credentials:**
- Email: `admin@elooxacademy.com`
- Password: `password`

**⚠️ IMPORTANT**: Change the admin password immediately after first login!

## Configuration

### Step 1: Environment Setup
1. Copy `.env` file (if not exists, create from `env` file)
2. Update database credentials in `.env`:
```
database.default.hostname = localhost
database.default.database = e-learning
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
```

### Step 2: Base URL
Update `app/Config/App.php` or set in `.env`:
```
app.baseURL = 'http://localhost/e-learning/'
```

## Features Implemented

### ✅ Admin Section
- [x] Admin authentication with role-based access
- [x] Admin dashboard with real-time statistics
- [x] User management (Students, Instructors, Admins)
- [x] Course management structure
- [x] Database schema matching requirements
- [x] Blue color scheme from logo

### 🚧 In Progress
- [ ] Complete course CRUD operations
- [ ] Category management
- [ ] Order and payment management
- [ ] Enrollment management
- [ ] Student portal authentication
- [ ] Course browsing and enrollment
- [ ] Learning dashboard

## File Structure

### Key Files Created/Updated:
- `database_schema.sql` - Complete database schema
- `app/Filters/AdminAuth.php` - Admin authentication filter
- `app/Controllers/Admin/AdminAuth.php` - Admin login/logout
- `app/Controllers/Dashboard.php` - Admin dashboard with statistics
- `app/Models/UserModel.php` - Updated to match new schema
- `app/Models/UserSessionModel.php` - Updated session management
- `app/Views/admin/login.php` - Admin login page
- `app/Config/Routes.php` - Updated with admin routes
- `assets/css/style.css` - Updated with blue color scheme

## Access Points

### Admin Panel
- **Login**: `http://localhost/e-learning/admin/login`
- **Dashboard**: `http://localhost/e-learning/admin/dashboard` (requires login)

### Public Portal
- **Homepage**: `http://localhost/e-learning/`
- **Student Portal**: `http://localhost/e-learning/portal` (coming soon)

## Color Scheme
The platform uses the E-LOOX Academy blue color scheme:
- **Primary Dark Blue**: `#1E3A8A`
- **Primary Light Blue**: `#3B82F6`

## Next Steps

1. **Run Database Migration**: Execute `database_schema.sql`
2. **Create Admin User**: Use the SQL provided above
3. **Login**: Access admin panel at `/admin/login`
4. **Update Models**: Some models still need updating to match new schema (CourseModel, EnrollmentModel, OrderModel, etc.)
5. **Build Course Management**: Complete CRUD operations for courses
6. **Build Student Portal**: Create student-facing pages

## Troubleshooting

### Database Connection Issues
- Check database credentials in `.env`
- Ensure MySQL service is running
- Verify database name matches

### Authentication Issues
- Clear browser cookies/session
- Check session configuration in `app/Config/App.php`
- Verify user exists and is active in database

### Model Errors
- Some models may still reference old field names
- Update models to use `id` instead of `*_id` where applicable
- Update foreign key references

## Support
For issues or questions, refer to the CodeIgniter 4 documentation or check the code comments.

