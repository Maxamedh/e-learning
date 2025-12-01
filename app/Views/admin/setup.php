<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Setup Admin' ?> - E-LOOX Academy</title>
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico')?>" type="image/x-icon">
    <link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/fontawesome.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/brands.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/solid.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/css/style.css')?>" rel="stylesheet">
    <base href="<?= base_url() ?>">
    <style>
        .setup-container {
            background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .setup-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 2.5rem;
            max-width: 500px;
            width: 100%;
        }
        .setup-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .setup-logo img {
            max-width: 200px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="setup-card">
            <div class="setup-logo">
                <img src="<?= base_url('logo/jpg/logo@3x-100.jpg') ?>" alt="E-LOOX Academy Logo" onerror="this.src='<?= base_url('assets/images/logo.png') ?>'">
            </div>

            <h2 class="text-center mb-4" style="color: #1E3A8A;">Setup Admin Account</h2>
            <p class="text-center text-muted mb-4">Create your first admin account to access the system</p>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div id="setupMessage" class="alert d-none" role="alert"></div>

            <form id="setupForm" method="POST" action="<?= base_url('admin/setup/create-admin') ?>">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control form-control-lg" id="email" name="email" 
                           value="<?= old('email', 'admin@elooxacademy.com') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control form-control-lg" id="first_name" name="first_name" 
                           value="<?= old('first_name', 'Admin') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control form-control-lg" id="last_name" name="last_name" 
                           value="<?= old('last_name', 'User') ?>" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control form-control-lg" id="password" name="password" 
                           value="admin123" required minlength="6">
                    <small class="text-muted">Minimum 6 characters</small>
                </div>

                <button type="submit" id="submitBtn" class="btn btn-primary btn-lg w-100" style="background-color: #1E3A8A; border-color: #1E3A8A;">
                    <span id="submitText"><i class="fas fa-user-plus me-2"></i>Create Admin Account</span>
                    <span id="submitSpinner" class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                </button>
            </form>
        </div>
    </div>

    <script src="<?= base_url('assets/js/jquery-3.6.0.min.js')?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js')?>"></script>
    <script>
        document.getElementById('setupForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');
            const messageDiv = document.getElementById('setupMessage');
            
            // Show loading state
            submitBtn.disabled = true;
            submitText.innerHTML = 'Creating Account...';
            submitSpinner.classList.remove('d-none');
            messageDiv.classList.add('d-none');
            
            // Form will submit normally - no need to prevent default
            // If there's an error, it will be shown via PHP redirect/flash data
        });
    </script>
</body>
</html>

