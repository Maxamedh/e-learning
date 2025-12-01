<?= $this->extend('layouts/portal') ?>
<?= $this->section('content') ?>

<style>
    .auth-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
        background: var(--bg-light);
    }
    
    .auth-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 2.5rem;
        max-width: 400px;
        width: 100%;
    }
    
    .auth-card h2 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
    }
    
    .auth-card .subtitle {
        color: var(--text-gray);
        margin-bottom: 2rem;
    }
    
    .form-control:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .btn-primary {
        background: var(--text-dark);
        border: none;
        padding: 0.75rem;
        font-weight: 600;
    }
    
    .btn-primary:hover {
        background: var(--dark-blue);
    }
</style>

<div class="auth-container">
    <div class="auth-card">
        <h2>Log In</h2>
        <p class="subtitle">Welcome back! Please login to your account.</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('portal/login') ?>">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?= old('email') ?>" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">Log In</button>
        </form>

        <div class="text-center">
            <p class="mb-0">Don't have an account? <a href="<?= base_url('portal/register') ?>" class="text-decoration-none">Sign Up</a></p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

