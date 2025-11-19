<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
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
            <div class="col-11 col-sm-8 col-md-8 col-lg-4">
                <div class="bg-white rounded-4 shadow-sm p-4">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <a href="<?= base_url('login') ?>"><img src="<?= base_url('assets/images/logo.png') ?>" alt="logo"></a>
                        </div>
                    </div>

                    <!-- Sign In Form -->
                    <h2 class="mb-4 text-dark h4">Sign In</h2>
                    <form id="loginForm">
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
                                       id="password" name="password" placeholder="••••••••" required>
                                <i class="fas fa-lock input-icon"></i>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12 col-lg-6 mb-2 mb-lg-0">
                            <div class="form-check ps-0">
                                <input type="checkbox" class="custom-checkbox" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">Remember me</label>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 text-lg-end">
                            <a href="<?= base_url('forgot-password') ?>" class="text-primary">Forgot password</a>
                            </div>
                        </div>

                        <!-- Error/Success Messages -->
                        <div id="loginMessage" class="alert d-none" role="alert"></div>

                        <!-- Sign In Button -->
                        <button type="submit" class="btn btn-signin btn-lg w-100 rounded-3 mb-4" id="loginBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Sign In
                        </button>

                        <!-- Divider -->
                        <div class="text-center text-muted mb-4 text-size-14">
                             Don't have an account yet? <a href="<?= base_url('signup') ?>" class="text-primary">Sign Up</a>
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
        var base_url = '<?= base_url() ?>';
        // Update CSRF token
        function updateCSRFToken() {
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            if (metaTag) {
                fetch('/api/auth/me')
                    .then(response => response.json())
                    .then(data => {
                        // Token will be updated on next request
                    });
            }
        }

        // Login form handler
       document.getElementById('loginForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  const messageDiv = document.getElementById('loginMessage');
  const loginBtn = document.getElementById('loginBtn');
  const spinner = loginBtn.querySelector('.spinner-border');

  loginBtn.disabled = true;
  spinner.classList.remove('d-none');
  messageDiv.classList.add('d-none');

  try {
    const response = await Auth.login(email, password);

    if (response.status === 'success') {
      console.log('Login succeeded — redirecting');
      messageDiv.className = 'alert alert-success';
      messageDiv.textContent = response.message || 'Login successful! Redirecting...';
      messageDiv.classList.remove('d-none');
       
      // give a slight delay so user sees message, then redirect
      setTimeout(() => {
        // Use correct route
        window.location.href = base_url + '/'; // or '/dashboard' if route exists
      }, 500);
    } else {
      messageDiv.className = 'alert alert-danger';
      messageDiv.textContent = response.message || 'Login failed';
      messageDiv.classList.remove('d-none');
    }
  } catch (error) {
    messageDiv.className = 'alert alert-danger';
    messageDiv.textContent = error.message || 'An error occurred';
    messageDiv.classList.remove('d-none');
  } finally {
    loginBtn.disabled = false;
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
