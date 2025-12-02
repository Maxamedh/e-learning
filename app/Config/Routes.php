<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Database Setup (Development only)
$routes->get('setup-database', 'SetupDatabase::index');
$routes->get('test-login', 'Admin\TestLogin::index');
$routes->get('debug-login', 'Admin\DebugLogin::test');
$routes->get('test-session', 'Admin\TestSession::index');

// Admin Authentication (Public)
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function($routes) {
    $routes->get('login', 'AdminAuth::login');
    $routes->post('login', 'AdminAuth::login');
    $routes->get('logout', 'AdminAuth::logout');
    
    // Setup (only if no admin exists)
    $routes->get('setup', 'Setup::index');
    $routes->post('setup/create-admin', 'Setup::createAdmin');
});

// Admin Routes (Protected)
$routes->group('admin', ['namespace' => 'App\Controllers', 'filter' => 'adminAuth'], function($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('/', 'Dashboard::index'); // Redirect admin root to dashboard
    
    // Profile & Settings
    $routes->group('profile', ['namespace' => 'App\Controllers\Admin'], function($routes) {
        $routes->get('/', 'Profile::index');
        $routes->post('update', 'Profile::update');
        $routes->post('change-password', 'Profile::changePassword');
    });
    
    $routes->get('settings', 'Admin\Settings::index');
    
    // Course Management
    $routes->group('courses', ['namespace' => 'App\Controllers\Admin'], function($routes) {
        $routes->get('/', 'Courses::index');
        $routes->get('create', 'Courses::create');
        $routes->post('store', 'Courses::store');
        $routes->get('view/(:num)', 'Courses::view/$1');
        $routes->get('edit/(:num)', 'Courses::edit/$1');
        $routes->post('update/(:num)', 'Courses::update/$1');
        $routes->get('delete/(:num)', 'Courses::delete/$1');
    });
    
    // Course Sections Management
    $routes->group('sections', ['namespace' => 'App\Controllers\Admin'], function($routes) {
        $routes->get('(:num)', 'Sections::index/$1');
        $routes->post('(:num)/store', 'Sections::store/$1');
        $routes->post('(:num)/update/(:num)', 'Sections::update/$1/$2');
        $routes->get('(:num)/delete/(:num)', 'Sections::delete/$1/$2');
    });
    
    // Course Lectures Management
    $routes->group('lectures', ['namespace' => 'App\Controllers\Admin'], function($routes) {
        $routes->get('(:num)', 'Lectures::index/$1');
        $routes->get('(:num)/(:num)', 'Lectures::index/$1/$2');
        $routes->post('(:num)/(:num)/store', 'Lectures::store/$1/$2');
        $routes->post('(:num)/(:num)/update/(:num)', 'Lectures::update/$1/$2/$3');
        $routes->get('(:num)/(:num)/delete/(:num)', 'Lectures::delete/$1/$2/$3');
    });
    
    // User Management
    $routes->group('users', ['namespace' => 'App\Controllers\Admin'], function($routes) {
        $routes->get('/', 'Users::index');
        $routes->get('create', 'Users::create');
        $routes->post('store', 'Users::store');
        $routes->get('edit/(:num)', 'Users::edit/$1');
        $routes->post('update/(:num)', 'Users::update/$1');
        $routes->get('delete/(:num)', 'Users::delete/$1');
    });
    
    // Category Management
    $routes->group('categories', ['namespace' => 'App\Controllers\Admin'], function($routes) {
        $routes->get('/', 'Categories::index');
        $routes->post('store', 'Categories::store');
        $routes->post('update/(:num)', 'Categories::update/$1');
        $routes->get('delete/(:num)', 'Categories::delete/$1');
        $routes->get('seed', 'SeedCategories::index');
    });
    
    // Enrollment Management
    $routes->get('enrollments', 'Admin\Enrollments::index');
    $routes->get('enrollments/create', 'Admin\Enrollments::create');
    $routes->post('enrollments/store', 'Admin\Enrollments::store');
    $routes->get('enrollments/delete/(:num)', 'Admin\Enrollments::delete/$1');
    
    // Order Management
    $routes->group('orders', ['namespace' => 'App\Controllers\Admin'], function($routes) {
        $routes->get('/', 'Orders::index');
        $routes->get('view/(:num)', 'Orders::view/$1');
        $routes->post('update-status/(:num)', 'Orders::updateStatus/$1');
    });
});

// Public/Client Portal Routes - Moved to line 221 below

// Legacy routes (redirect to admin if logged in as admin)
$routes->get('/course', 'Course::index');
$routes->get('/students', 'Students::index');
$routes->get('/teacher', 'Teacher::index');
$routes->get('/library', 'Library::index');
$routes->get('/department', 'Department::index');
$routes->get('/staff', 'Staff::index');
$routes->get('/fees', 'Fees::index');
$routes->get('/course-details', 'CourseDetails::index');

// Auth Pages (Public)
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
    
    // Progress Tracking
    $routes->post('progress/start', 'ProgressController::start');
    $routes->post('progress/update', 'ProgressController::update');
    $routes->post('progress/complete', 'ProgressController::complete');
    
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

// Portal Routes (Public - Student Portal)
$routes->get('/', 'Portal\Home::index');
$routes->get('courses', 'Portal\Courses::index');
$routes->get('courses/(:num)', 'Portal\Courses::view/$1');
$routes->get('categories/(:num)', 'Portal\Courses::category/$1');

// Portal Authentication (Public)
$routes->group('portal', ['namespace' => 'App\Controllers\Portal'], function($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::login');
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::register');
    $routes->get('logout', 'Auth::logout');
});

// Portal Routes (Protected - Student Only)
$routes->group('portal', ['namespace' => 'App\Controllers\Portal', 'filter' => 'portalAuth'], function($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('my-courses', 'Dashboard::myCourses');
    $routes->get('learn/(:num)', 'Learn::index/$1');
    $routes->get('learn/(:num)/lecture/(:num)', 'Learn::lecture/$1/$2');
    $routes->post('enroll/(:num)', 'Courses::enroll/$1');
});
