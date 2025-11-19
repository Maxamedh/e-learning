
<div class="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <div class="lg-logo">
            <a href="<?= base_url('/') ?>">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="logo large">
            </a>
        </div>
        <div class="sm-logo">
            <a href="<?= base_url('/') ?>">
                <img src="<?= base_url('assets/images/small-logo.png') ?>" alt="logo small">
            </a>
        </div>
    </div>

    <!-- Sidebar Body -->
    <div class="sidebar-body custom-scrollbar">
        <ul class="sidebar-menu">

            <!-- Dashboard -->
            <li>
                <a href="<?= base_url('/') ?>" class="sidebar-link active">
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
                    <li><a href="<?= base_url('course') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Courses</p></a></li>
                    <li><a href="<?= base_url('course-details') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Course Details</p></a></li>
                    <li><a href="<?= base_url('sections') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Sections</p></a></li>
                    <li><a href="<?= base_url('lectures') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Lectures</p></a></li>
                    <li><a href="<?= base_url('assignments') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Assignments</p></a></li>
                    <li><a href="<?= base_url('quizzes') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Quizzes</p></a></li>
                    <li><a href="<?= base_url('reviews') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Reviews</p></a></li>
                    <li><a href="<?= base_url('certificates') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Certificates</p></a></li>
                </ul>
            </li>

            <!-- User Management -->
            <li>
                <a href="#" class="sidebar-link submenu-parent">
                    <i class="fa-solid fa-users"></i>
                    <p>User Management <i class="fa-solid fa-chevron-right right-icon"></i></p>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= base_url('students') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Students</p></a></li>
                    <li><a href="<?= base_url('teacher') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Teachers</p></a></li>
                    <li><a href="<?= base_url('staff') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Staff</p></a></li>
                    <li><a href="<?= base_url('users') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>All Users</p></a></li>
                </ul>
            </li>

            <!-- Academic Management -->
            <li>
                <a href="#" class="sidebar-link submenu-parent">
                    <i class="fa-solid fa-building-columns"></i>
                    <p>Academic Management <i class="fa-solid fa-chevron-right right-icon"></i></p>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= base_url('department') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Departments</p></a></li>
                    <li><a href="<?= base_url('library') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Library</p></a></li>
                    <li><a href="<?= base_url('enrollments') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Enrollments</p></a></li>
                </ul>
            </li>

            <!-- Financial Management -->
            <li>
                <a href="#" class="sidebar-link submenu-parent">
                    <i class="fa-solid fa-coins"></i>
                    <p>Financial Management <i class="fa-solid fa-chevron-right right-icon"></i></p>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= base_url('fees') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Fees</p></a></li>
                    <li><a href="<?= base_url('orders') ?>" class="submenu-link"><i class="fa-solid fa-circle me-4"></i><p>Orders</p></a></li>
                </ul>
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
