<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-column flex-md-row flex-lg-row mt-3">
                <div class="flex-grow-1">
                    <h3 class="mb-2 text-color-2"><?= esc($course['title']) ?></h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin/all-courses') ?>">All Courses</a></li>
                            <li class="breadcrumb-item active"><?= esc($course['title']) ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Course Information</h5>
                    <p><strong>Instructor:</strong> <?= esc($instructor['first_name'] . ' ' . $instructor['last_name']) ?></p>
                    <p><strong>Category:</strong> <?= esc($category['name'] ?? 'Uncategorized') ?></p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-<?= $course['status'] === 'published' ? 'success' : 'secondary' ?>">
                            <?= ucfirst($course['status']) ?>
                        </span>
                    </p>
                    <p><strong>Total Students:</strong> <?= count($enrollments) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Enrolled Students (<?= count($enrollments) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (empty($enrollments)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No students enrolled in this course</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Enrolled</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enrollments as $enrollment): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($enrollment['first_name'] . ' ' . $enrollment['last_name']) ?></strong>
                                    </td>
                                    <td><?= esc($enrollment['email']) ?></td>
                                    <td><?= esc($enrollment['phone_number'] ?? 'N/A') ?></td>
                                    <td><?= date('M d, Y', strtotime($enrollment['enrolled_at'])) ?></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: <?= esc($enrollment['progress_percentage'] ?? 0) ?>%"
                                                 aria-valuenow="<?= esc($enrollment['progress_percentage'] ?? 0) ?>" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                                <?= esc($enrollment['progress_percentage'] ?? 0) ?>%
                                            </div>
                                        </div>
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

