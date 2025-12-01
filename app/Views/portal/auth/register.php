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
        max-width: 500px;
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
</style>

<div class="auth-container">
    <div class="auth-card">
        <h2>Sign Up</h2>
        <p class="subtitle">Join thousands of students learning new skills today!</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('portal/register') ?>">
            <?= csrf_field() ?>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name *</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" 
                           value="<?= old('first_name') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name *</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" 
                           value="<?= old('last_name') ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?= old('email') ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password *</label>
                <input type="password" class="form-control" id="password" name="password" 
                       minlength="6" required>
                <small class="text-muted">Minimum 6 characters</small>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password *</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">Sign Up</button>
        </form>

        <div class="text-center">
            <p class="mb-0">Already have an account? <a href="<?= base_url('portal/login') ?>" class="text-decoration-none">Log In</a></p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

