<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-column flex-md-row flex-lg-row mt-3">
                <div class="flex-grow-1">
                    <h3 class="mb-2 text-color-2"><?= $title ?></h3>
                </div>
                <div class="mt-3 mt-lg-0">
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Edit User Form -->
    <div class="card mt-4">
        <div class="card-body">
            <form method="POST" action="<?= base_url('admin/users/update/' . $user['id']) ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">First Name *</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?= old('first_name', $user['first_name']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Last Name *</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?= old('last_name', $user['last_name']) ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $user['email']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password (leave blank to keep current)</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label">Role *</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="student" <?= (old('role', $user['role']) == 'student') ? 'selected' : '' ?>>Student</option>
                            <option value="instructor" <?= (old('role', $user['role']) == 'instructor') ? 'selected' : '' ?>>Instructor</option>
                            <option value="admin" <?= (old('role', $user['role']) == 'admin') ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= old('phone_number', $user['phone_number'] ?? '') ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?= old('date_of_birth', $user['date_of_birth'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="profile_picture" class="form-label">Profile Picture</label>
                        <?php if (!empty($user['profile_picture'])): ?>
                            <div class="mb-2">
                                <img src="<?= esc($user['profile_picture']) ?>" alt="Current Profile" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;" onerror="this.style.display='none'">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
                        <small class="text-muted">Upload new profile picture to replace current (JPG, PNG, GIF - Max 2MB)</small>
                        <div id="profilePreview" class="mt-2" style="display: none;">
                            <img id="profilePreviewImg" src="" alt="Profile Preview" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="profile_picture_url" class="form-label">Or Profile Picture URL (Alternative)</label>
                        <input type="url" class="form-control" id="profile_picture_url" name="profile_picture_url" value="<?= old('profile_picture_url', $user['profile_picture'] ?? '') ?>" placeholder="https://example.com/profile.jpg">
                        <small class="text-muted">Use URL if you prefer to host image externally</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea class="form-control" id="bio" name="bio" rows="3"><?= old('bio', $user['bio'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= (old('is_active', $user['is_active']) ? 'checked' : '') ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="email_verified" name="email_verified" value="1" <?= (old('email_verified', $user['email_verified']) ? 'checked' : '') ?>>
                            <label class="form-check-label" for="email_verified">Email Verified</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-2"></i>Update User
                    </button>
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Preview profile picture
document.getElementById('profile_picture')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePreviewImg').src = e.target.result;
            document.getElementById('profilePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});
</script>

<?= $this->endSection() ?>

