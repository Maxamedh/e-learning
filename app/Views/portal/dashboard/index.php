<?= $this->extend('layouts/portal') ?>
<?= $this->section('content') ?>

<style>
    .dashboard-hero {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: #fff;
        padding: 3rem 0;
        margin-bottom: 3rem;
    }
    
    .stat-card {
        background: #fff;
        border-radius: 8px;
        padding: 1.5rem;
        border: 1px solid #e0e0e0;
        text-align: center;
    }
    
    .stat-card .number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-blue);
    }
    
    .stat-card .label {
        color: var(--text-gray);
        font-size: 0.875rem;
    }
    
    .course-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.2s;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    
    .course-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        text-decoration: none;
        color: inherit;
    }
    
    .course-card-pending {
        border: 1px solid #ffc107;
        border-radius: 8px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        display: block;
        opacity: 0.8;
    }
    
    .progress-bar-container {
        background: #e0e0e0;
        height: 8px;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 0.5rem;
    }
    
    .progress-bar-fill {
        background: var(--primary-blue);
        height: 100%;
        transition: width 0.3s;
    }
</style>

<div class="dashboard-hero">
    <div class="container">
        <h1 class="mb-2">Welcome back, <?= esc(session()->get('user')['first_name'] ?? 'Student') ?>!</h1>
        <p class="mb-0 opacity-90">Continue your learning journey</p>
    </div>
</div>

<div class="container">
    <!-- Stats -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="number"><?= $totalCourses ?></div>
                <div class="label">Total Courses</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="number"><?= $inProgressCourses ?></div>
                <div class="label">In Progress</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="number"><?= $completedCourses ?></div>
                <div class="label">Completed</div>
            </div>
        </div>
    </div>

    <!-- Continue Learning -->
    <?php if (!empty($recentCourses)): ?>
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Continue Learning</h2>
            <a href="<?= base_url('portal/my-courses') ?>" class="btn btn-link">View All <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4">
            <?php foreach ($recentCourses as $enrollment): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <?php 
                    $isPending = !empty($enrollment['order_status']) && $enrollment['order_status'] === 'pending';
                    $cardClass = $isPending ? 'course-card-pending' : 'course-card';
                    $cardHref = $isPending ? '#' : base_url('portal/learn/' . $enrollment['course_id']);
                    ?>
                    <a href="<?= $cardHref ?>" class="<?= $cardClass ?>" <?= $isPending ? 'onclick="return false;" style="cursor: not-allowed; opacity: 0.7;"' : '' ?>>
                        <?php if (!empty($enrollment['thumbnail_url'])): ?>
                            <img src="<?= esc($enrollment['thumbnail_url']) ?>" alt="<?= esc($enrollment['title']) ?>" 
                                 style="width: 100%; height: 150px; object-fit: cover;">
                        <?php else: ?>
                            <div style="height: 150px; background: #e0e0e0; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image fa-2x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div class="p-3">
                            <h6 class="mb-2" style="font-size: 0.9rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= esc($enrollment['title']) ?>
                            </h6>
                            <?php if ($isPending): ?>
                                <span class="badge bg-warning mb-2">Payment Pending</span>
                                <p class="text-muted small mb-0" style="font-size: 0.75rem;">Awaiting approval</p>
                            <?php else: ?>
                                <div class="progress-bar-container" style="height: 8px; margin-bottom: 8px;">
                                    <div class="progress-bar-fill" style="width: <?= $enrollment['progress_percentage'] ?? 0 ?>%; transition: width 0.3s ease;"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">
                                        <strong><?= number_format($enrollment['progress_percentage'] ?? 0, 0) ?>%</strong> Complete
                                    </span>
                                    <?php if (($enrollment['progress_percentage'] ?? 0) >= 100): ?>
                                        <span class="badge bg-success" style="font-size: 0.65rem;">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($enrollment['completed_lectures']) && !empty($enrollment['total_lectures'])): ?>
                                    <div class="text-muted small" style="font-size: 0.7rem; margin-top: 4px;">
                                        <?= $enrollment['completed_lectures'] ?>/<?= $enrollment['total_lectures'] ?> lectures
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
            <h4>No courses yet</h4>
            <p class="text-muted">Start learning by browsing our courses</p>
            <a href="<?= base_url('courses') ?>" class="btn btn-primary">Browse Courses</a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

