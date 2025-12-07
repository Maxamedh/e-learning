
<div class="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <div class="lg-logo">
            <a href="<?= base_url('admin/dashboard') ?>">
                <img src="<?= base_url('assets/images/logo@3x-100.jpg') ?>" alt="E-LOOX Academy Logo" style="max-width: 180px; height: auto;" onerror="this.onerror=null; this.style.display='none';">
            </a>
        </div>
        <div class="sm-logo">
            <a href="<?= base_url('admin/dashboard') ?>">
                <img src="<?= base_url('assets/images/logo@3x-100.jpg') ?>" alt="E-LOOX Academy Logo" style="max-width: 50px; height: auto;" onerror="this.onerror=null; this.style.display='none';">
            </a>
        </div>
    </div>

    <!-- Sidebar Body -->
    <div class="sidebar-body custom-scrollbar">
        <ul class="sidebar-menu">

            <!-- Dashboard -->
            <li>
                <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-link <?= (uri_string() == 'admin/dashboard' || uri_string() == 'admin') ? 'active' : '' ?>">
                    <i class="fa-solid fa-house"></i><p>Dashboard</p>
                </a>
            </li>

            <?php 
            $session = \Config\Services::session();
            $user = $session->get('user');
            $isAdmin = isset($user['role']) && $user['role'] === 'admin';
            $isInstructor = isset($user['role']) && $user['role'] === 'instructor';
            ?>
            
            <?php if ($isAdmin): ?>
            <!-- Course Management (Admin Only) -->
            <li>
                <a href="#" class="sidebar-link submenu-parent">
                    <i class="fa-brands fa-discourse"></i>
                    <p>Course Management <i class="fa-solid fa-chevron-right right-icon"></i></p>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= base_url('admin/courses') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Manage Courses</p></a></li>
                    <li><a href="<?= base_url('admin/all-courses') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>All Courses & Students</p></a></li>
                    <li><a href="<?= base_url('admin/courses/create') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Create Course</p></a></li>
                    <li><a href="<?= base_url('admin/categories') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Categories</p></a></li>
                    <li><a href="<?= base_url('admin/enrollments') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Enrollments</p></a></li>
                </ul>
            </li>
            <?php endif; ?>
            
            <?php if ($isInstructor): ?>
            <!-- Instructor Menu -->
            <li>
                <a href="<?= base_url('instructor/courses') ?>" class="sidebar-link <?= (strpos(uri_string(), 'instructor/courses') !== false) ? 'active' : '' ?>">
                    <i class="fa-brands fa-discourse"></i>
                    <p>My Courses</p>
                </a>
            </li>
            <li>
                <a href="<?= base_url('instructor/students') ?>" class="sidebar-link <?= (strpos(uri_string(), 'instructor/students') !== false) ? 'active' : '' ?>">
                    <i class="fa-solid fa-users"></i>
                    <p>My Students</p>
                </a>
            </li>
            <li>
                <a href="<?= base_url('instructor/discussions') ?>" class="sidebar-link <?= (strpos(uri_string(), 'instructor/discussions') !== false) ? 'active' : '' ?>">
                    <i class="fa-solid fa-comments"></i>
                    <p>Course Discussions</p>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if ($isAdmin): ?>
            <!-- Course Content Management (Admin Only) -->
            <li>
                <a href="#" class="sidebar-link submenu-parent">
                    <i class="fa-solid fa-book-open"></i>
                    <p>Course Content <i class="fa-solid fa-chevron-right right-icon"></i></p>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= base_url('admin/courses') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Select Course First</p></a></li>
                </ul>
            </li>

            <!-- User Management (Admin Only) -->
            <li>
                <a href="#" class="sidebar-link submenu-parent">
                    <i class="fa-solid fa-users"></i>
                    <p>User Management <i class="fa-solid fa-chevron-right right-icon"></i></p>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= base_url('admin/users') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>All Users</p></a></li>
                    <li><a href="<?= base_url('admin/users?role=student') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Students</p></a></li>
                    <li><a href="<?= base_url('admin/users?role=instructor') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Instructors</p></a></li>
                    <li><a href="<?= base_url('admin/users?role=admin') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Admins</p></a></li>
                </ul>
            </li>

            <!-- Order Management (Admin Only) -->
            <li>
                <a href="<?= base_url('admin/orders') ?>" class="sidebar-link <?= (strpos(uri_string(), 'admin/orders') !== false) ? 'active' : '' ?>">
                    <i class="fa-solid fa-shopping-cart"></i>
                    <p>Orders & Payments</p>
                </a>
            </li>

            <!-- Discussion & Notifications (Admin Only) -->
            <li>
                <a href="#" class="sidebar-link submenu-parent">
                    <i class="fa-solid fa-comments"></i>
                    <p>Discussions & Notifications <i class="fa-solid fa-chevron-right right-icon"></i></p>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= base_url('admin/discussions') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Discussions</p></a></li>
                    <li><a href="<?= base_url('admin/notifications') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Notifications</p></a></li>
                </ul>
            </li>
            <?php endif; ?>


        </ul>
    </div>
</div>
