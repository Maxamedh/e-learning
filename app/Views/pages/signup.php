<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signup</title>
    <!-- Stylesheets -->
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico')?>" type="image/x-icon">
    <link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/fontawesome.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/brands.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/icons/fontawesome/css/solid.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/plugin/quill/quill.snow.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/css/style.css')?>" rel="stylesheet">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <base href="<?= base_url() ?>">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-11 col-sm-8 col-md-6 col-lg-4">
                <div class="bg-white rounded-4 shadow-sm p-4">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <a href="<?= base_url('login') ?>"><img src="<?= base_url('assets/images/logo.png') ?>" alt="logo"></a>
                        </div>
                    </div>

                    <!-- Sign Up Form -->
                    <h2 class="mb-4 text-dark h4">Sign Up</h2>
                    <form id="signupForm">
                        <!-- Name Input -->
                        <div class="mb-3 position-relative">
                            <label for="first_name" class="form-label text-muted small">First Name</label>
                            <div class="position-relative">
                                <input type="text" class="form-control form-control-lg rounded-3" 
                                       id="first_name" name="first_name" placeholder="John" required>
                                <i class="fas fa-user input-icon"></i>
                            </div>
                        </div>

                        <div class="mb-3 position-relative">
                            <label for="last_name" class="form-label text-muted small">Last Name</label>
                            <div class="position-relative">
                                <input type="text" class="form-control form-control-lg rounded-3" 
                                       id="last_name" name="last_name" placeholder="Doe" required>
                                <i class="fas fa-user input-icon"></i>
                            </div>
                        </div>

                        <!-- Email Input -->
                        <div class="mb-3 position-relative">
                            <label for="email" class="form-label text-muted small">Email</label>
                            <div class="position-relative">
                                <input type="email" class="form-control form-control-lg rounded-3" 
                                       id="email" name="email" placeholder="example@outlook.com" required>
                                <i class="fas fa-envelope input-icon"></i>
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="mb-4 position-relative">
                            <label for="password" class="form-label text-muted small">Password</label>
                            <div class="position-relative">
                                <input type="password" class="form-control form-control-lg rounded-3" 
                                       placeholder="••••••••" id="password" name="password" required>
                                <i class="fas fa-lock input-icon"></i>
                            </div>
                        </div>

                        <!-- User Type -->
                        <div class="mb-3">
                            <label for="user_type" class="form-label text-muted small">I am a</label>
                            <select class="form-select form-control-lg rounded-3" id="user_type" name="user_type" required>
                                <option value="student" selected>Student</option>
                                <option value="instructor">Instructor</option>
                            </select>
                        </div>

                        <!-- Error/Success Messages -->
                        <div id="signupMessage" class="alert d-none" role="alert"></div>
                        
                        <!-- Sign Up Button -->
                        <button type="submit" class="btn btn-signin btn-lg w-100 rounded-3 mb-4" id="signupBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Sign Up
                        </button>

                        <!-- Divider -->
                        <div class="text-center text-muted mb-4 text-size-14">
                            You have an account? <a href="<?= base_url('login') ?>" class="text-primary">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/jquery-3.6.0.min.js')?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js')?>"></script>
    <script src="<?= base_url('assets/js/ajax-helper.js')?>"></script>
    <script src="<?= base_url('assets/js/crud-operations.js')?>"></script>
    <script>
        // Signup form handler
        document.getElementById('signupForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });
            
            const messageDiv = document.getElementById('signupMessage');
            const signupBtn = document.getElementById('signupBtn');
            const spinner = signupBtn.querySelector('.spinner-border');
            
            // Show loading
            signupBtn.disabled = true;
            spinner.classList.remove('d-none');
            messageDiv.classList.add('d-none');
            
            try {
                const response = await Auth.register(data);
                
                if (response.success) {
                    messageDiv.className = 'alert alert-success';
                    messageDiv.textContent = response.message || 'Registration successful! Redirecting...';
                    messageDiv.classList.remove('d-none');
                    
                    if (response.data && response.data.redirect) {
                        setTimeout(() => {
                            window.location.href = response.data.redirect;
                        }, 1000);
                    }
                } else {
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.textContent = response.message || 'Registration failed';
                    messageDiv.classList.remove('d-none');
                }
            } catch (error) {
                messageDiv.className = 'alert alert-danger';
                messageDiv.textContent = error.message || 'An error occurred';
                messageDiv.classList.remove('d-none');
            } finally {
                signupBtn.disabled = false;
                spinner.classList.add('d-none');
            }
        });
    </script>
    <script src="<?= base_url('assets/plugin/chart/chart.js')?>"></script>
    <script src="<?= base_url('assets/plugin/quill/quill.js')?>"></script>
    <script src="<?= base_url('assets/js/chart.js')?>"></script>
    <script src="<?= base_url('assets/js/main.js')?>"></script>
</body>
</html>
