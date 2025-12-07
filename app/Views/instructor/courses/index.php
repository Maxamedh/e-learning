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

    <div class="card mt-4">
        <div class="card-body">
            <?php if (empty($courses)): ?>
                <div class="text-center py-5">
                    <i class="fa-solid fa-book fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No courses yet. Create your first course!</p>
                    <a href="<?= base_url('admin/courses/create') ?>" class="btn btn-primary mt-3">
                        <i class="fa-solid fa-plus me-2"></i>Create Course
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Students</th>
                                <th>Rating</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($course['title']) ?></strong><br>
                                        <small class="text-muted"><?= esc(substr($course['short_description'] ?? '', 0, 50)) ?>...</small>
                                    </td>
                                    <td><?= esc($course['category_name'] ?? 'Uncategorized') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $course['status'] === 'published' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst($course['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= number_format($course['total_students'] ?? 0) ?></td>
                                    <td>
                                        <?php if ($course['avg_rating'] > 0): ?>
                                            <?= number_format($course['avg_rating'], 1) ?> <i class="fas fa-star text-warning"></i>
                                        <?php else: ?>
                                            <span class="text-muted">No ratings</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('instructor/courses/view/' . $course['id']) ?>" class="btn btn-sm btn-info me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('instructor/courses/edit/' . $course['id']) ?>" class="btn btn-sm btn-warning me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('instructor/students?course=' . $course['id']) ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-users"></i> Students
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

