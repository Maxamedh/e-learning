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
                    <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
                        <i class="fa-solid fa-plus me-2"></i>Create User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <strong>Success!</strong> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="card mt-4">
        <div class="card-body">
            <form method="GET" action="<?= base_url('admin/users') ?>" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search users..." value="<?= esc($search ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="student" <?= (isset($role) && $role == 'student') ? 'selected' : '' ?>>Students</option>
                        <option value="instructor" <?= (isset($role) && $role == 'instructor') ? 'selected' : '' ?>>Instructors</option>
                        <option value="admin" <?= (isset($role) && $role == 'admin') ? 'selected' : '' ?>>Admins</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card mt-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">No users found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($user['profile_picture'])): ?>
                                                <img src="<?= esc($user['profile_picture']) ?>" alt="<?= esc($user['first_name']) ?>" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                    <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <strong><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= esc($user['email']) ?></td>
                                    <td>
                                        <?php
                                        $roleColors = ['admin' => 'danger', 'instructor' => 'primary', 'student' => 'success'];
                                        $roleColor = $roleColors[$user['role']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $roleColor ?>"><?= ucfirst($user['role']) ?></span>
                                    </td>
                                    <td><?= esc($user['phone_number'] ?? 'N/A') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $user['is_active'] ? 'success' : 'danger' ?>">
                                            <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" class="btn btn-sm btn-primary me-1" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <a href="<?= base_url('admin/users/delete/' . $user['id']) ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

