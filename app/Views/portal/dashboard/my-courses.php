<?= $this->extend('layouts/portal') ?>
<?= $this->section('content') ?>

<style>
    .course-item {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: box-shadow 0.2s;
    }
    
    .course-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">My Courses</h1>
    </div>

    <?php if (empty($enrollments)): ?>
        <div class="text-center py-5">
            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
            <h4>No courses yet</h4>
            <p class="text-muted">Start learning by browsing our courses</p>
            <a href="<?= base_url('courses') ?>" class="btn btn-primary">Browse Courses</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($enrollments as $enrollment): ?>
                <div class="col-12 mb-3">
                    <div class="course-item">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <?php if (!empty($enrollment['thumbnail_url'])): ?>
                                    <img src="<?= esc($enrollment['thumbnail_url']) ?>" alt="<?= esc($enrollment['title']) ?>" 
                                         class="img-fluid rounded" style="width: 100%; height: 120px; object-fit: cover;">
                                <?php else: ?>
                                    <div style="width: 100%; height: 120px; background: #e0e0e0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-7">
                                <h5 class="mb-2"><?= esc($enrollment['title']) ?></h5>
                                <p class="text-muted mb-2"><?= esc($enrollment['first_name'] . ' ' . $enrollment['last_name']) ?></p>
                                <?php if (!empty($enrollment['order_status']) && $enrollment['order_status'] === 'pending'): ?>
                                    <span class="badge bg-warning mb-2">Payment Pending</span>
                                    <p class="text-muted small mb-2">Your enrollment is pending payment approval. You will be able to access the course once payment is approved.</p>
                                <?php else: ?>
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <div class="flex-grow-1">
                                            <div class="progress-bar-container" style="height: 10px;">
                                                <div class="progress-bar-fill" style="width: <?= $enrollment['progress_percentage'] ?? 0 ?>%; transition: width 0.3s ease;"></div>
                                            </div>
                                        </div>
                                        <div class="text-muted small" style="min-width: 60px; text-align: right;">
                                            <strong><?= number_format($enrollment['progress_percentage'] ?? 0, 1) ?>%</strong>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center text-muted small">
                                        <span>
                                            <i class="fas fa-check-circle text-success me-1"></i>
                                            <?= $enrollment['completed_lectures'] ?? 0 ?> of <?= $enrollment['total_lectures'] ?? 0 ?> lectures completed
                                        </span>
                                        <?php if (($enrollment['progress_percentage'] ?? 0) >= 100): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-trophy me-1"></i>Completed
                                            </span>
                                        <?php elseif (($enrollment['progress_percentage'] ?? 0) > 0): ?>
                                            <span class="badge bg-primary">In Progress</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Not Started</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-3 text-end">
                                <?php if (!empty($enrollment['order_status']) && $enrollment['order_status'] === 'pending'): ?>
                                    <button class="btn btn-secondary" disabled title="Payment pending approval">
                                        <i class="fas fa-clock me-2"></i>Pending Approval
                                    </button>
                                <?php else: ?>
                                    <a href="<?= base_url('portal/learn/' . $enrollment['course_id']) ?>" class="btn btn-primary">
                                        <?= $enrollment['progress_percentage'] > 0 ? 'Continue Learning' : 'Start Learning' ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

