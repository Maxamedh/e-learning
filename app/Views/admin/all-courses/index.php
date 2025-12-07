<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-column flex-md-row flex-lg-row mt-3">
                <div class="flex-grow-1">
                    <h3 class="mb-2 text-color-2"><?= $title ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <?php if (empty($courses)): ?>
                <div class="text-center py-5">
                    <i class="fa-solid fa-book fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No courses found</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Instructor</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Students</th>
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
                                    <td><?= esc($course['first_name'] . ' ' . $course['last_name']) ?></td>
                                    <td><?= esc($course['category_name'] ?? 'Uncategorized') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $course['status'] === 'published' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst($course['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= number_format($course['enrollment_count'] ?? 0) ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/all-courses/' . $course['id']) ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-users me-1"></i>View Students
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

