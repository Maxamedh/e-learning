
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

            <!-- Course Management -->
            <li>
                <a href="#" class="sidebar-link submenu-parent">
                    <i class="fa-brands fa-discourse"></i>
                    <p>Course Management <i class="fa-solid fa-chevron-right right-icon"></i></p>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= base_url('admin/courses') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>All Courses</p></a></li>
                    <li><a href="<?= base_url('admin/courses/create') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Create Course</p></a></li>
                    <li><a href="<?= base_url('admin/categories') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Categories</p></a></li>
                    <li><a href="<?= base_url('admin/enrollments') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Enrollments</p></a></li>
                </ul>
            </li>
            
            <!-- Course Content Management -->
            <li>
                <a href="#" class="sidebar-link submenu-parent">
                    <i class="fa-solid fa-book-open"></i>
                    <p>Course Content <i class="fa-solid fa-chevron-right right-icon"></i></p>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= base_url('admin/courses') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Select Course First</p></a></li>
                </ul>
            </li>

            <!-- User Management -->
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

            <!-- Order Management -->
            <li>
                <a href="<?= base_url('admin/orders') ?>" class="sidebar-link <?= (strpos(uri_string(), 'admin/orders') !== false) ? 'active' : '' ?>">
                    <i class="fa-solid fa-shopping-cart"></i>
                    <p>Orders & Payments</p>
                </a>
            </li>

            <!-- Discussion & Notifications -->
            <li>
                <a href="#" class="sidebar-link submenu-parent">
                    <i class="fa-solid fa-comments"></i>
                    <p>Discussions & Notifications <i class="fa-solid fa-chevron-right right-icon"></i></p>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= base_url('discussions') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Discussions</p></a></li>
                    <li><a href="<?= base_url('notifications') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Notifications</p></a></li>
                </ul>
            </li>

            <!-- System Components -->
            <li>
                <a href="#" class="sidebar-link submenu-parent">
                    <i class="fa-solid fa-gears"></i>
                    <p>System Components <i class="fa-solid fa-chevron-right right-icon"></i></p>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= base_url('form') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Forms</p></a></li>
                    <li><a href="<?= base_url('table-bootstrap') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Bootstrap Table</p></a></li>
                    <li><a href="<?= base_url('data-table') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Data Table</p></a></li>
                </ul>
            </li>

            <!-- Authentication Pages -->
            <li>
                <a href="#" class="sidebar-link submenu-parent">
                    <i class="fa-solid fa-lock"></i>
                    <p>Authentication <i class="fa-solid fa-chevron-right right-icon"></i></p>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= base_url('login') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Login</p></a></li>
                    <li><a href="<?= base_url('signup') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Register</p></a></li>
                    <li><a href="<?= base_url('forgot-password') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Forgot Password</p></a></li>
                </ul>
            </li>

            <!-- Error Pages -->
            <li>
                <a href="#" class="sidebar-link submenu-parent">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <p>Error Pages <i class="fa-solid fa-chevron-right right-icon"></i></p>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= base_url('errors/404') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>404 Page</p></a></li>
                    <li><a href="<?= base_url('errors/500') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>500 Page</p></a></li>
                </ul>
            </li>

        </ul>
    </div>
</div>
