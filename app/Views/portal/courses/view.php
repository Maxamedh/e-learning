<?= $this->extend('layouts/portal') ?>
<?= $this->section('content') ?>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('error')): ?>
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('info')): ?>
    <div class="container mt-3">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('info') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
<?php endif; ?>

<style>
    .course-hero {
        background: #1c1d1f;
        color: #fff;
        padding: 2rem 0;
    }
    
    .course-hero h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .course-hero .subtitle {
        font-size: 1.125rem;
        opacity: 0.9;
        margin-bottom: 1rem;
    }
    
    .course-meta {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        font-size: 0.875rem;
    }
    
    .course-meta span {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .course-content {
        padding: 2rem 0;
    }
    
    .course-sidebar {
        position: sticky;
        top: 80px;
    }
    
    .enrollment-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.5rem;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .price-section {
        margin-bottom: 1.5rem;
    }
    
    .price-current {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
    }
    
    .price-original {
        font-size: 1rem;
        color: var(--text-gray);
        text-decoration: line-through;
    }
    
    .btn-enroll {
        background: var(--text-dark);
        color: #fff;
        border: none;
        padding: 0.875rem;
        font-weight: 600;
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .btn-enroll:hover {
        background: var(--dark-blue);
        color: #fff;
    }
    
    .course-features {
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
    }
    
    .course-features li {
        padding: 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .course-features i {
        color: var(--primary-blue);
    }
    
    .instructor-card {
        border-top: 1px solid #e0e0e0;
        padding-top: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .instructor-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .instructor-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--primary-blue);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.5rem;
    }
    
    .curriculum-section {
        margin-top: 3rem;
    }
    
    .section-item {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 1rem;
        overflow: hidden;
    }
    
    .section-header {
        background: #f7f9fa;
        padding: 1rem 1.5rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .lecture-item {
        padding: 0.75rem 1.5rem;
        border-top: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .lecture-item i {
        color: var(--text-gray);
    }
</style>

<!-- Course Hero Section -->
<section class="course-hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1><?= esc($course['title']) ?></h1>
                <p class="subtitle"><?= esc($course['subtitle'] ?? $course['short_description'] ?? '') ?></p>
                <div class="course-meta">
                    <?php if ($course['avg_rating'] > 0): ?>
                        <span>
                            <span class="text-warning"><?= number_format($course['avg_rating'], 1) ?></span>
                            <i class="fas fa-star text-warning"></i>
                            <span>(<?= number_format($course['total_reviews']) ?> ratings)</span>
                        </span>
                    <?php endif; ?>
                    <span><i class="fas fa-users"></i> <?= number_format($course['total_students'] ?? 0) ?> students</span>
                    <span><i class="fas fa-signal"></i> <?= ucfirst($course['level']) ?></span>
                    <span><i class="fas fa-clock"></i> <?= !empty($course['duration_hours']) ? $course['duration_hours'] . ' hours' : 'N/A' ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="course-content">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Course Description -->
                <section class="mb-5">
                    <h2 class="mb-3">About this course</h2>
                    <div class="course-description">
                        <?= nl2br(esc($course['description'])) ?>
                    </div>
                </section>

                <!-- What you'll learn -->
                <?php if (!empty($course['learning_outcomes'])): ?>
                <section class="mb-5">
                    <h2 class="mb-3">What you'll learn</h2>
                    <?php 
                    $outcomes = is_string($course['learning_outcomes']) ? json_decode($course['learning_outcomes'], true) : $course['learning_outcomes'];
                    if (is_array($outcomes)):
                    ?>
                        <ul class="list-unstyled">
                            <?php foreach ($outcomes as $outcome): ?>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i><?= esc($outcome) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </section>
                <?php endif; ?>

                <!-- Course Content -->
                <?php if (!empty($sections)): ?>
                <section class="curriculum-section">
                    <h2 class="mb-3">Course content</h2>
                    <div class="mb-3">
                        <span class="text-muted"><?= count($sections) ?> section<?= count($sections) != 1 ? 's' : '' ?> • 
                        <?php 
                        $totalLectures = 0;
                        foreach ($sections as $section) {
                            $totalLectures += count($section['lectures']);
                        }
                        echo $totalLectures;
                        ?> lecture<?= $totalLectures != 1 ? 's' : '' ?></span>
                    </div>
                    
                    <?php foreach ($sections as $section): ?>
                        <div class="section-item">
                            <div class="section-header">
                                <span><?= esc($section['title']) ?></span>
                                <span class="text-muted"><?= count($section['lectures']) ?> lecture<?= count($section['lectures']) != 1 ? 's' : '' ?></span>
                            </div>
                            <?php if (!empty($section['lectures'])): ?>
                                <?php foreach ($section['lectures'] as $lecture): ?>
                                    <div class="lecture-item">
                                        <i class="fas fa-<?= $lecture['content_type'] === 'video' ? 'play-circle' : 'file-alt' ?>"></i>
                                        <span><?= esc($lecture['title']) ?></span>
                                        <?php if ($lecture['is_preview']): ?>
                                            <span class="badge bg-info ms-auto">Preview</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </section>
                <?php endif; ?>

                <!-- Instructor -->
                <?php if ($instructor): ?>
                <section class="instructor-card mt-5">
                    <h2 class="mb-3">Instructor</h2>
                    <div class="instructor-info">
                        <?php if (!empty($instructor['profile_picture'])): ?>
                            <img src="<?= esc($instructor['profile_picture']) ?>" alt="<?= esc($instructor['first_name']) ?>" 
                                 class="instructor-avatar" style="width: 64px; height: 64px; object-fit: cover; border-radius: 50%;"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <?php endif; ?>
                        <div class="instructor-avatar" <?= !empty($instructor['profile_picture']) ? 'style="display: none;"' : '' ?>>
                            <?= strtoupper(substr($instructor['first_name'] ?? 'I', 0, 1) . substr($instructor['last_name'] ?? '', 0, 1)) ?>
                        </div>
                        <div>
                            <h5 class="mb-1"><?= esc($instructor['first_name'] . ' ' . $instructor['last_name']) ?></h5>
                            <?php if (!empty($instructor['bio'])): ?>
                                <p class="text-muted mb-0"><?= esc($instructor['bio']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="course-sidebar">
                    <div class="enrollment-card">
                        <?php if (!empty($course['thumbnail_url'])): ?>
                            <img src="<?= esc($course['thumbnail_url']) ?>" alt="<?= esc($course['title']) ?>" 
                                 class="img-fluid rounded mb-3" style="width: 100%; height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        
                        <div class="price-section">
                            <?php if ($course['is_free']): ?>
                                <div class="price-current">FREE</div>
                            <?php else: ?>
                                <?php if ($course['discount_price']): ?>
                                    <div class="price-current">$<?= number_format($course['discount_price'], 2) ?></div>
                                    <div class="price-original">$<?= number_format($course['price'], 2) ?></div>
                                <?php else: ?>
                                    <div class="price-current">$<?= number_format($course['price'], 2) ?></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <?php if ($isEnrolled): ?>
                            <a href="<?= base_url('portal/learn/' . $course['id']) ?>" class="btn btn-enroll">
                                Continue Learning
                            </a>
                        <?php else: ?>
                            <form method="POST" action="<?= base_url('portal/enroll/' . $course['id']) ?>">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-enroll">
                                    <?= $course['is_free'] ? 'Enroll for Free' : 'Enroll Now' ?>
                                </button>
                            </form>
                        <?php endif; ?>

                        <ul class="course-features">
                            <li><i class="fas fa-check"></i> Full lifetime access</li>
                            <li><i class="fas fa-check"></i> Mobile and desktop</li>
                            <li><i class="fas fa-check"></i> Certificate of completion</li>
                            <li><i class="fas fa-check"></i> 30-day money-back guarantee</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

