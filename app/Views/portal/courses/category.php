<?= $this->extend('layouts/portal') ?>
<?= $this->section('content') ?>

<style>
    .category-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: #fff;
        padding: 3rem 0;
        margin-bottom: 3rem;
    }
    
    .category-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .course-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
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
</style>

<!-- Category Header -->
<section class="category-header">
    <div class="container">
        <h1><?= esc($category['name']) ?></h1>
        <p class="mb-0 opacity-90"><?= esc($category['description'] ?? 'Explore courses in this category') ?></p>
    </div>
</section>

<div class="container">
    <?php if (empty($courses)): ?>
        <div class="text-center py-5">
            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
            <h4>No courses found</h4>
            <p class="text-muted">There are no courses available in this category yet.</p>
            <a href="<?= base_url('courses') ?>" class="btn btn-primary">Browse All Courses</a>
        </div>
    <?php else: ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <p class="mb-0"><?= count($courses) ?> course<?= count($courses) != 1 ? 's' : '' ?> found</p>
        </div>
        <div class="row g-4">
            <?php foreach ($courses as $course): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="<?= base_url('courses/' . $course['id']) ?>" class="course-card">
                        <?php 
                        $thumbnailUrl = !empty($course['thumbnail_url']) ? esc($course['thumbnail_url']) : '';
                        ?>
                        <?php if (!empty($thumbnailUrl)): ?>
                            <img src="<?= $thumbnailUrl ?>" 
                                 alt="<?= esc($course['title']) ?>" 
                                 style="width: 100%; height: 200px; object-fit: cover;"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <?php endif; ?>
                        <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: <?= empty($thumbnailUrl) ? 'flex' : 'none' ?>; align-items: center; justify-content: center; color: #fff;">
                            <i class="fas fa-graduation-cap fa-3x"></i>
                        </div>
                        <div class="p-3">
                            <h5 class="mb-2" style="font-size: 0.9rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= esc($course['title']) ?>
                            </h5>
                            <div class="text-muted small mb-2"><?= esc($course['first_name'] . ' ' . $course['last_name']) ?></div>
                            <?php if (isset($course['avg_rating']) && $course['avg_rating'] > 0): ?>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="text-warning fw-bold"><?= number_format($course['avg_rating'], 1) ?></span>
                                    <i class="fas fa-star text-warning"></i>
                                    <span class="text-muted small">(<?= number_format($course['total_reviews'] ?? 0) ?>)</span>
                                </div>
                            <?php endif; ?>
                            <div class="fw-bold">
                                <?php if (!empty($course['is_free'])): ?>
                                    <span class="text-success">FREE</span>
                                <?php else: ?>
                                    <?php if (!empty($course['discount_price'])): ?>
                                        <span class="text-decoration-line-through text-muted me-2">$<?= number_format($course['price'] ?? 0, 2) ?></span>
                                        <span class="text-danger">$<?= number_format($course['discount_price'], 2) ?></span>
                                    <?php else: ?>
                                        $<?= number_format($course['price'] ?? 0, 2) ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

