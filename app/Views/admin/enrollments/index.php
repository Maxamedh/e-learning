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
                    <a href="<?= base_url('admin/enrollments/create') ?>" class="btn btn-primary">
                        <i class="fa-solid fa-plus me-2"></i>Create Enrollment
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
            <form method="GET" action="<?= base_url('admin/enrollments') ?>" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email, or course..." value="<?= esc($search ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <select name="course_id" class="form-select">
                        <option value="">All Courses</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['id'] ?>" <?= (isset($course_id) && $course_id == $course['id']) ? 'selected' : '' ?>>
                                <?= esc($course['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="<?= base_url('admin/enrollments') ?>" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Enrollments Table -->
    <div class="card mt-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Enrolled At</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($enrollments)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">No enrollments found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($enrollments as $enrollment): ?>
                                <tr>
                                    <td>
                                        <div>
                                            <strong><?= esc($enrollment['first_name'] . ' ' . $enrollment['last_name']) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= esc($enrollment['email']) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($enrollment['thumbnail_url'])): ?>
                                                <img src="<?= esc($enrollment['thumbnail_url']) ?>" alt="<?= esc($enrollment['course_title']) ?>" 
                                                     class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;" 
                                                     onerror="this.style.display='none'">
                                            <?php endif; ?>
                                            <div>
                                                <strong><?= esc($enrollment['course_title'] ?? 'N/A') ?></strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($enrollment['enrolled_at'])) ?></td>
                                    <td style="min-width: 220px;">
                                        <?php 
                                        $progressPercentage = $enrollment['progress_percentage'] ?? 0;
                                        $completedLectures = $enrollment['completed_lectures'] ?? 0;
                                        $totalLectures = $enrollment['total_lectures'] ?? 0;
                                        $progressClass = $progressPercentage >= 100 ? 'bg-success' : ($progressPercentage > 0 ? 'bg-primary' : 'bg-secondary');
                                        $showTextInside = $progressPercentage >= 15; // Show text inside bar if >= 15%
                                        ?>
                                        <div class="d-flex flex-column gap-2">
                                            <div class="position-relative" style="height: 30px;">
                                                <div class="progress" style="height: 30px; border-radius: 6px; background-color: #e9ecef;">
                                                    <div class="progress-bar <?= $progressClass ?>" 
                                                         role="progressbar" 
                                                         style="width: <?= $progressPercentage ?>%; transition: width 0.3s ease;"
                                                         aria-valuenow="<?= $progressPercentage ?>" 
                                                         aria-valuemin="0" aria-valuemax="100">
                                                        <?php if ($showTextInside): ?>
                                                            <span class="d-flex align-items-center justify-content-center h-100 fw-bold" style="font-size: 0.875rem; color: #fff;">
                                                                <?= number_format($progressPercentage, 1) ?>%
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <?php if (!$showTextInside): ?>
                                                    <span class="position-absolute top-50 start-0 translate-middle-y fw-bold" style="font-size: 0.875rem; color: #6c757d; padding-left: 8px;">
                                                        <?= number_format($progressPercentage, 1) ?>%
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($totalLectures > 0): ?>
                                                <small class="text-muted" style="font-size: 0.75rem;">
                                                    <i class="fas fa-check-circle text-success me-1"></i>
                                                    <?= $completedLectures ?> / <?= $totalLectures ?> lectures completed
                                                </small>
                                            <?php else: ?>
                                                <small class="text-muted" style="font-size: 0.75rem;">No lectures available</small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($enrollment['completed_at'])): ?>
                                            <span class="badge bg-success">Completed</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary">In Progress</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/enrollments/delete/' . $enrollment['id']) ?>" 
                                           class="btn btn-sm btn-danger" 
                                           title="Delete" 
                                           onclick="return confirm('Are you sure you want to delete this enrollment?')">
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

