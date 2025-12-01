<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Admin Login' ?> - E-LOOX Academy</title>
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico')?>" type="image/x-icon">
    <link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/fontawesome.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/brands.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/solid.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/css/style.css')?>" rel="stylesheet">
    <base href="<?= base_url() ?>">
    <style>
        .admin-login-container {
            background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .admin-login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 2.5rem;
            max-width: 450px;
            width: 100%;
        }
        .admin-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .admin-logo img {
            max-width: 200px;
            height: auto;
        }
        .admin-title {
            color: #1E3A8A;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .admin-subtitle {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <div class="admin-login-card">
            <!-- Logo -->
            <div class="admin-logo">
                <img src="<?= base_url('logo/jpg/logo@3x-100.jpg') ?>" alt="E-LOOX Academy Logo" onerror="this.src='<?= base_url('assets/images/logo.png') ?>'">
            </div>

            <h2 class="admin-title text-center">Admin Login</h2>
            <p class="admin-subtitle text-center">Sign in to access the admin panel</p>

            <!-- Display Flash Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('info')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('info') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="<?= base_url('admin/login') ?>" id="loginForm">
                <?= csrf_field() ?>
                
                <!-- Email Input -->
                <div class="mb-3">
                    <label for="email" class="form-label text-muted small">Email Address</label>
                    <div class="position-relative">
                        <input type="email" 
                               class="form-control form-control-lg rounded-3" 
                               id="email" 
                               name="email" 
                               placeholder="admin@example.com" 
                               value="<?= old('email') ?>"
                               required 
                               autofocus>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['email'])): ?>
                        <div class="text-danger small mt-1"><?= session()->getFlashdata('errors')['email'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Password Input -->
                <div class="mb-4">
                    <label for="password" class="form-label text-muted small">Password</label>
                    <div class="position-relative">
                        <input type="password" 
                               class="form-control form-control-lg rounded-3" 
                               id="password" 
                               name="password" 
                               placeholder="••••••••" 
                               required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                    <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['password'])): ?>
                        <div class="text-danger small mt-1"><?= session()->getFlashdata('errors')['password'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                </div>

                <!-- Login Button -->
                <button type="submit" id="loginBtn" class="btn btn-primary btn-lg w-100 rounded-3 mb-3" style="background-color: #1E3A8A; border-color: #1E3A8A;">
                    <span id="loginBtnText"><i class="fas fa-sign-in-alt me-2"></i>Sign In</span>
                    <span id="loginBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>

                <div class="text-center">
                    <a href="<?= base_url('forgot-password') ?>" class="text-primary text-decoration-none">Forgot Password?</a>
                </div>
            </form>

            <hr class="my-4">

            <div class="text-center">
                <p class="text-muted small mb-0">Student Portal? <a href="<?= base_url('portal') ?>" class="text-primary">Go to Portal</a></p>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/jquery-3.6.0.min.js')?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js')?>"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            const btnText = document.getElementById('loginBtnText');
            const btnSpinner = document.getElementById('loginBtnSpinner');
            
            // Show loading state
            btn.disabled = true;
            btnText.innerHTML = 'Signing In...';
            btnSpinner.classList.remove('d-none');
        });
        
        // Check for any errors in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('error')) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger alert-dismissible fade show';
            errorDiv.innerHTML = '<strong>Error:</strong> ' + urlParams.get('error') + 
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            document.querySelector('.admin-login-card').insertBefore(errorDiv, document.querySelector('form'));
        }
    </script>
</body>
</html>

