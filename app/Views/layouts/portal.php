<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= ($title ?? 'E-LOOX Academy') ?> - E-LOOX Academy</title>
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico')?>" type="image/x-icon">
    <link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/fontawesome.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/brands.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/solid.min.css')?>" rel="stylesheet">
    <base href="<?= base_url() ?>">
    <style>
        :root {
            --primary-blue: #0d6efd;
            --dark-blue: #1e3a8a;
            --light-blue: #3b82f6;
            --text-dark: #1c1d1f;
            --text-gray: #6a6f73;
            --bg-light: #f7f9fa;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: var(--text-dark);
        }
        
        .portal-header {
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
        }
        
        .portal-header .navbar-brand img {
            height: 50px;
            width: auto;
            max-width: 200px;
            object-fit: contain;
        }
        
        .portal-header .nav-link {
            color: var(--text-dark);
            font-weight: 400;
            padding: 0.5rem 1rem;
        }
        
        .portal-header .nav-link:hover {
            color: var(--primary-blue);
        }
        
        .portal-header .btn-login {
            border: 1px solid var(--text-dark);
            color: var(--text-dark);
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }
        
        .portal-header .btn-signup {
            background: var(--text-dark);
            color: #fff;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }
        
        .portal-header .btn-signup:hover {
            background: var(--dark-blue);
            color: #fff;
        }
        
        .user-menu .dropdown-toggle::after {
            display: none;
        }
        
        .user-avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary-blue);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .portal-footer {
            background: #1c1d1f;
            color: #fff;
            padding: 3rem 0 1.5rem;
            margin-top: 4rem;
        }
        
        .portal-footer a {
            color: #fff;
            text-decoration: none;
        }
        
        .portal-footer a:hover {
            color: var(--light-blue);
        }
    </style>
    <?= $this->renderSection('head') ?>
</head>
<body>
    <!-- Header -->
    <header class="portal-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="<?= base_url() ?>">
                    <img src="<?= base_url('assets/images/logo@3x-100.jpg') ?>" alt="E-LOOX Academy" 
                         style="height: 50px; width: auto; max-width: 200px; object-fit: contain;" 
                         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                    <span style="display: none; font-weight: 700; color: var(--primary-blue); font-size: 1.5rem;">E-LOOX Academy</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url() ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('courses') ?>">Browse Courses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('courses') ?>?category=all">Categories</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <?php $user = session()->get('user'); ?>
                        <?php if ($user && $user['role'] === 'student'): ?>
                            <li class="nav-item dropdown user-menu">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                    <?php if (!empty($user['profile_picture'])): ?>
                                        <img src="<?= esc($user['profile_picture']) ?>" alt="Profile" class="user-avatar-circle me-2" style="width: 32px; height: 32px; object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <?php endif; ?>
                                    <span class="user-avatar-circle me-2" <?= !empty($user['profile_picture']) ? 'style="display: none;"' : '' ?>>
                                        <?= strtoupper(substr($user['first_name'] ?? 'U', 0, 1)) ?>
                                    </span>
                                    <span class="d-none d-md-inline"><?= esc($user['first_name'] ?? 'User') ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?= base_url('portal/dashboard') ?>"><i class="fas fa-home me-2"></i>My Dashboard</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('portal/my-courses') ?>"><i class="fas fa-book me-2"></i>My Courses</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= base_url('portal/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link btn-login me-2" href="<?= base_url('portal/login') ?>">Log In</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-signup" href="<?= base_url('portal/register') ?>">Sign Up</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="portal-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>E-LOOX Academy</h5>
                    <p>Transform your future with our comprehensive online courses. Learn from industry experts and advance your career.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>Company</h6>
                    <ul class="list-unstyled">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>Support</h6>
                    <ul class="list-unstyled">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>Legal</h6>
                    <ul class="list-unstyled">
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>Connect</h6>
                    <ul class="list-unstyled">
                        <li><a href="#"><i class="fab fa-facebook me-2"></i>Facebook</a></li>
                        <li><a href="#"><i class="fab fa-twitter me-2"></i>Twitter</a></li>
                        <li><a href="#"><i class="fab fa-linkedin me-2"></i>LinkedIn</a></li>
                    </ul>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p class="mb-0">&copy; <?= date('Y') ?> E-LOOX Academy. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="<?=base_url('assets/js/bootstrap.bundle.min.js')?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>

