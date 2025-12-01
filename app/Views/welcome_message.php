<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-LOOX Academy - Welcome</title>
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico')?>" type="image/x-icon">
    <link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/fontawesome.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/brands.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/solid.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/css/style.css')?>" rel="stylesheet">
    <base href="<?= base_url() ?>">
    <style>
        .welcome-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .welcome-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 3rem;
            max-width: 800px;
            width: 100%;
        }
        .welcome-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .welcome-logo img {
            max-width: 250px;
            height: auto;
        }
        .welcome-title {
            color: #1E3A8A;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .welcome-actions {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-welcome {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary-welcome {
            background-color: #1E3A8A;
            color: white;
            border: 2px solid #1E3A8A;
        }
        .btn-primary-welcome:hover {
            background-color: #3B82F6;
            border-color: #3B82F6;
            color: white;
        }
        .btn-outline-welcome {
            background-color: transparent;
            color: #1E3A8A;
            border: 2px solid #1E3A8A;
        }
        .btn-outline-welcome:hover {
            background-color: #1E3A8A;
            color: white;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-card">
            <div class="welcome-logo">
                <img src="<?= base_url('logo/jpg/logo@3x-100.jpg') ?>" alt="E-LOOX Academy Logo" onerror="this.src='<?= base_url('assets/images/logo.png') ?>'">
            </div>
            
            <h1 class="welcome-title text-center">Welcome to E-LOOX Academy</h1>
            <p class="text-center text-muted mb-4">
                Your comprehensive e-learning platform for quality education and professional development.
            </p>

            <div class="welcome-actions">
                <a href="<?= base_url('admin/login') ?>" class="btn-welcome btn-primary-welcome">
                    <i class="fas fa-user-shield me-2"></i>Admin Login
                </a>
                <a href="<?= base_url() ?>" class="btn-welcome btn-outline-welcome">
                    <i class="fas fa-graduation-cap me-2"></i>Student Portal
                </a>
                <a href="<?= base_url('login') ?>" class="btn-welcome btn-outline-welcome">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </a>
            </div>

            <hr class="my-4">

            <div class="text-center">
                <p class="text-muted small mb-0">
                    <strong>E-LOOX Academy</strong> - Empowering learners worldwide
                </p>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/jquery-3.6.0.min.js')?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js')?>"></script>
</body>
</html>

