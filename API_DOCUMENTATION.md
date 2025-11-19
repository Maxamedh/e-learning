# E-Learning System API Documentation

## Overview
This e-learning system provides a complete RESTful API with token-based authentication. All API endpoints require authentication headers (`X-Auth-Token` and `X-Username`) except for registration and login.

## Base URL
```
http://your-domain.com/api
```

## Authentication

### Headers Required
All authenticated requests must include:
```
X-Auth-Token: your-auth-token
X-Username: user@email.com
```

### Register
**POST** `/api/auth/register`

Request Body:
```json
{
  "email": "user@example.com",
  "password": "password123",
  "first_name": "John",
  "last_name": "Doe",
  "user_type": "student"
}
```

Response:
```json
{
  "status": "success",
  "message": "User registered successfully",
  "data": {
    "user": {...},
    "token": "auth-token-here"
  }
}
```

### Login
**POST** `/api/auth/login`

Request Body:
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

Response:
```json
{
  "status": "success",
  "message": "Login successful",
  "data": {
    "user": {...},
    "token": "auth-token-here"
  }
}
```

## API Endpoints

### Users
- **GET** `/api/users` - List all users (Admin only)
- **GET** `/api/users/{id}` - Get user by ID
- **POST** `/api/users` - Create user (Admin only)
- **PUT** `/api/users/{id}` - Update user
- **DELETE** `/api/users/{id}` - Delete user (Admin only)

### Categories
- **GET** `/api/categories` - List all categories
- **GET** `/api/categories/{id}` - Get category by ID
- **POST** `/api/categories` - Create category (Admin only)
- **PUT** `/api/categories/{id}` - Update category (Admin only)
- **DELETE** `/api/categories/{id}` - Delete category (Admin only)

### Courses
- **GET** `/api/courses` - List courses (with pagination, filters: status, category_id, search)
- **GET** `/api/courses/{id}` - Get course by ID
- **POST** `/api/courses` - Create course (Instructor/Admin)
- **PUT** `/api/courses/{id}` - Update course (Instructor/Admin)
- **DELETE** `/api/courses/{id}` - Delete course (Instructor/Admin)

### Sections
- **GET** `/api/sections?course_id={id}` - List sections for a course
- **GET** `/api/sections/{id}` - Get section by ID
- **POST** `/api/sections` - Create section (Instructor/Admin)
- **PUT** `/api/sections/{id}` - Update section (Instructor/Admin)
- **DELETE** `/api/sections/{id}` - Delete section (Instructor/Admin)

### Lectures
- **GET** `/api/lectures?section_id={id}` - List lectures for a section
- **GET** `/api/lectures/{id}` - Get lecture by ID
- **POST** `/api/lectures` - Create lecture (Instructor/Admin)
- **PUT** `/api/lectures/{id}` - Update lecture (Instructor/Admin)
- **DELETE** `/api/lectures/{id}` - Delete lecture (Instructor/Admin)

### Enrollments
- **GET** `/api/enrollments?user_id={id}` - List enrollments
- **GET** `/api/enrollments/{id}` - Get enrollment by ID
- **POST** `/api/enrollments` - Create enrollment
- **PUT** `/api/enrollments/{id}` - Update enrollment
- **DELETE** `/api/enrollments/{id}` - Delete enrollment

### Orders
- **GET** `/api/orders?user_id={id}` - List orders
- **GET** `/api/orders/{id}` - Get order by ID
- **POST** `/api/orders` - Create order
- **PUT** `/api/orders/{id}` - Update order (Admin only)
- **DELETE** `/api/orders/{id}` - Delete order (Admin only)

### Quizzes
- **GET** `/api/quizzes?lecture_id={id}` - List quizzes for a lecture
- **GET** `/api/quizzes/{id}` - Get quiz by ID (includes questions and options)
- **POST** `/api/quizzes` - Create quiz (Instructor/Admin)
- **PUT** `/api/quizzes/{id}` - Update quiz (Instructor/Admin)
- **DELETE** `/api/quizzes/{id}` - Delete quiz (Instructor/Admin)

### Assignments
- **GET** `/api/assignments?course_id={id}` - List assignments for a course
- **GET** `/api/assignments/{id}` - Get assignment by ID
- **POST** `/api/assignments` - Create assignment (Instructor/Admin)
- **PUT** `/api/assignments/{id}` - Update assignment (Instructor/Admin)
- **DELETE** `/api/assignments/{id}` - Delete assignment (Instructor/Admin)

### Discussions
- **GET** `/api/discussions?course_id={id}` - List discussions for a course
- **GET** `/api/discussions/{id}` - Get discussion by ID (includes replies)
- **POST** `/api/discussions` - Create discussion
- **PUT** `/api/discussions/{id}` - Update discussion
- **DELETE** `/api/discussions/{id}` - Delete discussion

### Reviews
- **GET** `/api/reviews?course_id={id}` - List reviews for a course
- **GET** `/api/reviews/{id}` - Get review by ID
- **POST** `/api/reviews` - Create review
- **PUT** `/api/reviews/{id}` - Update review
- **DELETE** `/api/reviews/{id}` - Delete review

### Certificates
- **GET** `/api/certificates?user_id={id}` - List certificates
- **GET** `/api/certificates/{id}` - Get certificate by ID
- **POST** `/api/certificates` - Create certificate (Admin only)

### Notifications
- **GET** `/api/notifications?is_read={true/false}` - List notifications
- **GET** `/api/notifications/{id}` - Get notification by ID (marks as read)
- **PUT** `/api/notifications/{id}` - Update notification

## JavaScript API Client

The system includes a JavaScript API client (`assets/js/api.js`) that handles all API calls with automatic token management.

### Usage Example

```javascript
// Login
const response = await api.login('user@example.com', 'password');
if (response.status === 'success') {
    // Token is automatically stored
    console.log('Logged in!');
}

// Get courses
const courses = await api.getCourses({ page: 1, limit: 10 });

// Create course
const newCourse = await api.createCourse({
    title: 'New Course',
    description: 'Course description',
    instructor_id: 'user-id',
    price: 99.99
});

// Update course
await api.updateCourse('course-id', { title: 'Updated Title' });

// Delete course
await api.deleteCourse('course-id');
```

## Error Responses

All errors follow this format:
```json
{
  "status": "error",
  "message": "Error message here",
  "errors": {...} // Optional validation errors
}
```

Common HTTP Status Codes:
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## Permissions

- **Student**: Can view courses, enroll, submit assignments, participate in discussions
- **Instructor**: All student permissions + create/edit own courses, sections, lectures, quizzes, assignments
- **Admin**: Full access to all resources

## Notes

- All timestamps are in ISO 8601 format
- UUIDs are used for primary keys
- Pagination is available for list endpoints (page, limit parameters)
- Token expires after 7 days
- Passwords are hashed using bcrypt
