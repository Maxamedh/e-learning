<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-column flex-md-row flex-lg-row mt-3">
                <div class="flex-grow-1">
                    <h3 class="mb-2 text-color-2">Instructor Dashboard</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <div class="row">
            <!-- Statistics Cards -->
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-12 mb-4">
                        <div class="stats-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="stats-label">My Courses</div>
                                    <div class="stats-value"><?= number_format($stats['total_courses'] ?? 0) ?></div>
                                    <div class="trend-wrapper">
                                        Published: <?= number_format($stats['published_courses'] ?? 0) ?>
                                    </div>
                                </div>
                                <div class="icon-wrapper icon-red">
                                    <i class="fas fa-play-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6 col-lg-12 mb-4">
                        <div class="stats-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="stats-label">Total Students</div>
                                    <div class="stats-value"><?= number_format($stats['total_students'] ?? 0) ?></div>
                                    <div class="trend-wrapper">
                                        Enrolled Students
                                    </div>
                                </div>
                                <div class="icon-wrapper icon-purple">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6 col-lg-12 mb-4">
                        <div class="stats-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="stats-label">Total Enrollments</div>
                                    <div class="stats-value"><?= number_format($stats['total_enrollments'] ?? 0) ?></div>
                                    <div class="trend-wrapper">
                                        All Time
                                    </div>
                                </div>
                                <div class="icon-wrapper" style="background: rgba(75, 222, 151, 0.1); color: #4BDE97;">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Enrollments -->
            <div class="col-lg-9 mb-4 mb-lg-0">
                <div class="instructors-section card pb-1">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-4">
                        <h5 class="mb-0 text-color-2">Recent Enrollments</h5>
                        <a href="<?= base_url('instructor/students') ?>" class="text-color-3">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($recentEnrollments)): ?>
                            <div class="p-3 text-center text-muted">
                                <i class="fas fa-user-graduate fa-2x mb-2"></i>
                                <p class="mb-0">No enrollments yet</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Course</th>
                                            <th>Enrolled</th>
                                            <th>Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentEnrollments as $enrollment): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= esc($enrollment['first_name'] . ' ' . $enrollment['last_name']) ?></strong><br>
                                                    <small class="text-muted"><?= esc($enrollment['email']) ?></small>
                                                </td>
                                                <td><?= esc($enrollment['course_title']) ?></td>
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
            
            <!-- My Courses -->
            <div class="col-lg-12 mt-4">
                <div class="instructors-section card pb-1">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-4">
                        <h5 class="mb-0 text-color-2">My Courses</h5>
                        <a href="<?= base_url('instructor/courses') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Create Course
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($courses)): ?>
                            <div class="p-3 text-center text-muted">
                                <i class="fas fa-book fa-2x mb-2"></i>
                                <p class="mb-0">No courses yet. Create your first course!</p>
                                <a href="<?= base_url('instructor/courses/create') ?>" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus me-2"></i>Create Course
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Course</th>
                                            <th>Status</th>
                                            <th>Students</th>
                                            <th>Rating</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($courses as $course): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= esc($course['title']) ?></strong><br>
                                                    <small class="text-muted"><?= esc($course['short_description'] ?? '') ?></small>
                                                </td>
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
                                                <td>
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
        </div>
    </div>
</div>

<?= $this->endSection() ?>

