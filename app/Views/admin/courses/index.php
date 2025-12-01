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
                    <a href="<?= base_url('admin/courses/create') ?>" class="btn btn-primary">
                        <i class="fa-solid fa-plus me-2"></i>Create Course
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

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <strong>Error!</strong> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="card mt-4">
        <div class="card-body">
            <form method="GET" action="<?= base_url('admin/courses') ?>" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search courses..." value="<?= esc($search ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="draft" <?= ($status ?? '') == 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= ($status ?? '') == 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="unpublished" <?= ($status ?? '') == 'unpublished' ? 'selected' : '' ?>>Unpublished</option>
                        <option value="pending" <?= ($status ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="<?= base_url('admin/courses') ?>" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Courses Table -->
    <div class="card mt-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Instructor</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Students</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($courses)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">No courses found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($course['thumbnail_url'])): ?>
                                                <img src="<?= esc($course['thumbnail_url']) ?>" alt="<?= esc($course['title']) ?>" class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.src='<?= base_url('assets/images/placeholder.jpg') ?>'">
                                            <?php else: ?>
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-2" style="width: 50px; height: 50px;">
                                                    <i class="fa-solid fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <strong><?= esc($course['title']) ?></strong>
                                                <div class="mt-1">
                                                    <?php if ($course['is_free']): ?>
                                                        <span class="badge bg-success me-1">
                                                            <i class="fa-solid fa-gift me-1"></i>FREE
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-primary me-1">
                                                            <i class="fa-solid fa-dollar-sign me-1"></i>PAID
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if ($course['is_featured']): ?>
                                                        <span class="badge bg-warning">
                                                            <i class="fa-solid fa-star me-1"></i>Featured
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= esc(($course['first_name'] ?? '') . ' ' . ($course['last_name'] ?? '')) ?></td>
                                    <td><?= esc($course['category_name'] ?? 'N/A') ?></td>
                                    <td>
                                        <?php if ($course['is_free']): ?>
                                            <span class="badge bg-success">FREE</span>
                                        <?php else: ?>
                                            <?php if ($course['discount_price']): ?>
                                                <span class="text-decoration-line-through text-muted">$<?= number_format($course['price'], 2) ?></span>
                                                <span class="text-danger fw-bold">$<?= number_format($course['discount_price'], 2) ?></span>
                                            <?php else: ?>
                                                $<?= number_format($course['price'], 2) ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= number_format($course['total_students'] ?? 0) ?></td>
                                    <td>
                                        <?php
                                        $statusColors = [
                                            'published' => 'success',
                                            'draft' => 'secondary',
                                            'unpublished' => 'warning',
                                            'pending' => 'info'
                                        ];
                                        $statusColor = $statusColors[$course['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $statusColor ?>"><?= ucfirst($course['status']) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/courses/view/' . $course['id']) ?>" class="btn btn-sm btn-info me-1" title="View Course">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('admin/sections/' . $course['id']) ?>" class="btn btn-sm btn-success me-1" title="Manage Sections & Lectures">
                                            <i class="fa-solid fa-book-open"></i>
                                        </a>
                                        <a href="<?= base_url('admin/courses/edit/' . $course['id']) ?>" class="btn btn-sm btn-primary me-1" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <a href="<?= base_url('admin/courses/delete/' . $course['id']) ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this course?')">
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

