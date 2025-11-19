<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Dashboard
$routes->get('/', 'Dashboard::index');

// Main Pages
$routes->get('/course', 'Course::index');
$routes->get('/students', 'Students::index');
$routes->get('/teacher', 'Teacher::index');
$routes->get('/library', 'Library::index');
$routes->get('/department', 'Department::index');
$routes->get('/staff', 'Staff::index');
$routes->get('/fees', 'Fees::index');
$routes->get('/course-details', 'CourseDetails::index');

// Auth Pages
$routes->get('/login', 'Login::index');
$routes->get('/signup', 'Signup::index');
$routes->get('/forgot-password', 'ForgotPassword::index');

// Table Pages
$routes->get('/table-bootstrap', 'TableBootstrap::index');
$routes->get('/data-table', 'DataTable::index');

// Component Pages
$routes->get('/form', 'Form::index');

// API Routes
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($routes) {
    // Auth endpoints (no token required)
    $routes->post('auth/register', 'AuthController::register');
    $routes->post('auth/login', 'AuthController::login');
    $routes->post('auth/verify', 'AuthController::verifyToken');
    $routes->post('auth/logout', 'AuthController::logout');
    
    // User CRUD
    $routes->get('users', 'UserApiController::index');
    $routes->get('users/(:segment)', 'UserApiController::show/$1');
    $routes->post('users', 'UserApiController::create');
    $routes->put('users/(:segment)', 'UserApiController::update/$1');
    $routes->delete('users/(:segment)', 'UserApiController::delete/$1');
    
    // Category CRUD
    $routes->get('categories', 'CategoryApiController::index');
    $routes->get('categories/(:segment)', 'CategoryApiController::show/$1');
    $routes->post('categories', 'CategoryApiController::create');
    $routes->put('categories/(:segment)', 'CategoryApiController::update/$1');
    $routes->delete('categories/(:segment)', 'CategoryApiController::delete/$1');
    
    // Course CRUD
    $routes->get('courses', 'CourseApiController::index');
    $routes->get('courses/(:segment)', 'CourseApiController::show/$1');
    $routes->post('courses', 'CourseApiController::create');
    $routes->put('courses/(:segment)', 'CourseApiController::update/$1');
    $routes->delete('courses/(:segment)', 'CourseApiController::delete/$1');
    
    // Section CRUD
    $routes->get('sections', 'SectionApiController::index');
    $routes->get('sections/(:segment)', 'SectionApiController::show/$1');
    $routes->post('sections', 'SectionApiController::create');
    $routes->put('sections/(:segment)', 'SectionApiController::update/$1');
    $routes->delete('sections/(:segment)', 'SectionApiController::delete/$1');
    
    // Lecture CRUD
    $routes->get('lectures', 'LectureApiController::index');
    $routes->get('lectures/(:segment)', 'LectureApiController::show/$1');
    $routes->post('lectures', 'LectureApiController::create');
    $routes->put('lectures/(:segment)', 'LectureApiController::update/$1');
    $routes->delete('lectures/(:segment)', 'LectureApiController::delete/$1');
    
    // Enrollment CRUD
    $routes->get('enrollments', 'EnrollmentApiController::index');
    $routes->get('enrollments/(:segment)', 'EnrollmentApiController::show/$1');
    $routes->post('enrollments', 'EnrollmentApiController::create');
    $routes->put('enrollments/(:segment)', 'EnrollmentApiController::update/$1');
    $routes->delete('enrollments/(:segment)', 'EnrollmentApiController::delete/$1');
    
    // Order CRUD
    $routes->get('orders', 'OrderApiController::index');
    $routes->get('orders/(:segment)', 'OrderApiController::show/$1');
    $routes->post('orders', 'OrderApiController::create');
    $routes->put('orders/(:segment)', 'OrderApiController::update/$1');
    $routes->delete('orders/(:segment)', 'OrderApiController::delete/$1');
    
    // Quiz CRUD
    $routes->get('quizzes', 'QuizApiController::index');
    $routes->get('quizzes/(:segment)', 'QuizApiController::show/$1');
    $routes->post('quizzes', 'QuizApiController::create');
    $routes->put('quizzes/(:segment)', 'QuizApiController::update/$1');
    $routes->delete('quizzes/(:segment)', 'QuizApiController::delete/$1');
    
    // Assignment CRUD
    $routes->get('assignments', 'AssignmentApiController::index');
    $routes->get('assignments/(:segment)', 'AssignmentApiController::show/$1');
    $routes->post('assignments', 'AssignmentApiController::create');
    $routes->put('assignments/(:segment)', 'AssignmentApiController::update/$1');
    $routes->delete('assignments/(:segment)', 'AssignmentApiController::delete/$1');
    
    // Discussion CRUD
    $routes->get('discussions', 'DiscussionApiController::index');
    $routes->get('discussions/(:segment)', 'DiscussionApiController::show/$1');
    $routes->post('discussions', 'DiscussionApiController::create');
    $routes->put('discussions/(:segment)', 'DiscussionApiController::update/$1');
    $routes->delete('discussions/(:segment)', 'DiscussionApiController::delete/$1');
    
    // Review CRUD
    $routes->get('reviews', 'ReviewApiController::index');
    $routes->get('reviews/(:segment)', 'ReviewApiController::show/$1');
    $routes->post('reviews', 'ReviewApiController::create');
    $routes->put('reviews/(:segment)', 'ReviewApiController::update/$1');
    $routes->delete('reviews/(:segment)', 'ReviewApiController::delete/$1');
    
    // Certificate CRUD
    $routes->get('certificates', 'CertificateApiController::index');
    $routes->get('certificates/(:segment)', 'CertificateApiController::show/$1');
    $routes->post('certificates', 'CertificateApiController::create');
    
    // Notification CRUD
    $routes->get('notifications', 'NotificationApiController::index');
    $routes->get('notifications/(:segment)', 'NotificationApiController::show/$1');
    $routes->put('notifications/(:segment)', 'NotificationApiController::update/$1');
});
