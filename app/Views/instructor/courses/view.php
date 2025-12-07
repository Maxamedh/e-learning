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
                            <li class="breadcrumb-item"><a href="<?= base_url('instructor/courses') ?>">My Courses</a></li>
                            <li class="breadcrumb-item active"><?= esc($course['title']) ?></li>
                        </ol>
                    </nav>
                </div>
                <div class="mt-3 mt-lg-0">
                    <a href="<?= base_url('instructor/courses/edit/' . $course['id']) ?>" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Edit Course
                    </a>
                    <a href="<?= base_url('instructor/students?course=' . $course['id']) ?>" class="btn btn-primary">
                        <i class="fas fa-users me-2"></i>View Students
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Course Information</h5>
                    <p><strong>Description:</strong></p>
                    <p><?= nl2br(esc($course['description'])) ?></p>
                    
                    <p><strong>Status:</strong> 
                        <span class="badge bg-<?= $course['status'] === 'published' ? 'success' : 'secondary' ?>">
                            <?= ucfirst($course['status']) ?>
                        </span>
                    </p>
                    
                    <p><strong>Total Students:</strong> <?= number_format($course['total_students'] ?? 0) ?></p>
                    <p><strong>Rating:</strong> 
                        <?php if ($course['avg_rating'] > 0): ?>
                            <?= number_format($course['avg_rating'], 1) ?> <i class="fas fa-star text-warning"></i>
                            (<?= number_format($course['total_reviews'] ?? 0) ?> reviews)
                        <?php else: ?>
                            <span class="text-muted">No ratings yet</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Quick Stats</h5>
                    <p><strong>Enrolled Students:</strong> <?= count($enrollments) ?></p>
                    <p><strong>Sections:</strong> <?= count($sections) ?></p>
                    <?php 
                    $totalLectures = 0;
                    foreach ($sections as $section) {
                        $totalLectures += count($section['lectures']);
                    }
                    ?>
                    <p><strong>Total Lectures:</strong> <?= $totalLectures ?></p>
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
                    <p class="text-muted">No students enrolled yet</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Email</th>
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

