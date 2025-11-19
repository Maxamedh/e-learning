# E-Learning System - Database Setup Guide

## Overview
This document outlines the complete database schema for the e-learning platform. All migrations have been created and are ready to run.

## Database Structure

### Core Tables Created:

1. **Users & Authentication**
   - `users` - Main user table with student/instructor/admin roles
   - `user_roles` - Additional role assignments
   - `user_sessions` - Session tracking

2. **Course Management**
   - `categories` - Course categories with parent-child relationships
   - `courses` - Main course table
   - `course_prerequisites` - Course prerequisites
   - `course_objectives` - Learning objectives

3. **Content Management**
   - `sections` - Course sections
   - `lectures` - Individual lectures (video, article, quiz, etc.)
   - `lecture_completion` - Track lecture completion
   - `course_progress` - Overall course progress tracking

4. **Enrollment & Payments**
   - `enrollments` - Student course enrollments
   - `orders` - Payment orders
   - `order_items` - Order line items
   - `coupons` - Discount coupons
   - `coupon_usage` - Coupon usage tracking

5. **Assessments**
   - `quizzes` - Quiz definitions
   - `questions` - Quiz questions
   - `question_options` - Multiple choice options
   - `quiz_attempts` - Student quiz attempts
   - `quiz_answers` - Student answers
   - `assignments` - Course assignments
   - `assignment_submissions` - Student submissions

6. **Community & Reviews**
   - `course_reviews` - Course ratings and reviews
   - `discussions` - Course discussions/Q&A
   - `discussion_replies` - Discussion replies

7. **Certificates & Notifications**
   - `certificates` - Course completion certificates
   - `notifications` - User notifications
   - `user_preferences` - Notification preferences

## Setup Instructions

### Step 1: Configure Database
Edit `app/Config/Database.php` and set your database credentials:
```php
'username' => 'root',
'password' => '',
'database' => 'e-learning',
```

### Step 2: Run Migrations
```bash
php spark migrate
```

This will create all tables in your database.

### Step 3: Verify Tables
Check that all tables were created:
```bash
php spark migrate:status
```

## Key Features

- **UUID Support**: All primary keys use UUIDs (CHAR(36)) for better scalability
- **Foreign Keys**: Proper relationships with CASCADE rules
- **Indexes**: Performance indexes on frequently queried columns
- **ENUM Types**: Data integrity with ENUM constraints
- **Timestamps**: Automatic created_at and updated_at tracking

## Next Steps

1. Create Models for all entities
2. Set up Authentication system
3. Build Controllers for CRUD operations
4. Create Views for user interface
5. Implement business logic

## Notes

- MySQL doesn't have native UUID generation, so use the `generate_uuid()` helper function
- All timestamps use MySQL TIMESTAMP with automatic defaults
- Boolean fields use MySQL BOOLEAN type (TINYINT(1))
- JSON fields use MySQL JSON type for flexible data storage

