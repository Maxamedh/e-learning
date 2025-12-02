<?= $this->extend('layouts/portal') ?>
<?= $this->section('content') ?>

<style>
    .courses-header {
        background: var(--bg-light);
        padding: 2rem 0;
        margin-bottom: 2rem;
    }
    
    .filter-sidebar {
        background: #fff;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .filter-section {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .filter-section:last-child {
        border-bottom: none;
    }
    
    .filter-section h6 {
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--text-dark);
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

<div class="courses-header">
    <div class="container">
        <h1 class="mb-2">Browse Courses</h1>
        <p class="text-muted mb-0">Discover thousands of courses from expert instructors</p>
    </div>
</div>

<div class="container">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3">
            <div class="filter-sidebar">
                <form method="GET" action="<?= base_url('courses') ?>">
                    <div class="filter-section">
                        <h6>Search</h6>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search courses..." value="<?= esc($search ?? '') ?>">
                    </div>
                    
                    <div class="filter-section">
                        <h6>Category</h6>
                        <select name="category" class="form-select">
                            <option value="all" <?= ($category ?? 'all') === 'all' ? 'selected' : '' ?>>All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($category ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                    <?= esc($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-section">
                        <h6>Level</h6>
                        <select name="level" class="form-select">
                            <option value="all" <?= ($level ?? 'all') === 'all' ? 'selected' : '' ?>>All Levels</option>
                            <option value="beginner" <?= ($level ?? '') === 'beginner' ? 'selected' : '' ?>>Beginner</option>
                            <option value="intermediate" <?= ($level ?? '') === 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
                            <option value="advanced" <?= ($level ?? '') === 'advanced' ? 'selected' : '' ?>>Advanced</option>
                        </select>
                    </div>
                    
                    <div class="filter-section">
                        <h6>Sort By</h6>
                        <select name="sort" class="form-select">
                            <option value="recent" <?= ($sort ?? 'recent') === 'recent' ? 'selected' : '' ?>>Most Recent</option>
                            <option value="popular" <?= ($sort ?? '') === 'popular' ? 'selected' : '' ?>>Most Popular</option>
                            <option value="rating" <?= ($sort ?? '') === 'rating' ? 'selected' : '' ?>>Highest Rated</option>
                            <option value="price-low" <?= ($sort ?? '') === 'price-low' ? 'selected' : '' ?>>Price: Low to High</option>
                            <option value="price-high" <?= ($sort ?? '') === 'price-high' ? 'selected' : '' ?>>Price: High to Low</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-2">Apply Filters</button>
                    <a href="<?= base_url('courses') ?>" class="btn btn-outline-secondary w-100">Reset</a>
                </form>
            </div>
        </div>
        
        <!-- Courses Grid -->
        <div class="col-lg-9">
            <?php if (empty($courses)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>No courses found</h4>
                    <p class="text-muted">Try adjusting your search or filters</p>
                </div>
            <?php else: ?>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <p class="mb-0"><?= count($courses) ?> course<?= count($courses) != 1 ? 's' : '' ?> found</p>
                </div>
                <div class="row g-4">
                    <?php foreach ($courses as $course): ?>
                        <div class="col-6 col-md-4">
                            <a href="<?= base_url('courses/' . $course['id']) ?>" class="course-card">
                                <?php 
                                $thumbnailUrl = !empty($course['thumbnail_url']) ? esc($course['thumbnail_url']) : '';
                                ?>
                                <?php if (!empty($thumbnailUrl)): ?>
                                    <img src="<?= $thumbnailUrl ?>" 
                                         alt="<?= esc($course['title']) ?>" 
                                         style="width: 100%; height: 200px; object-fit: cover;"
                                         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <?php endif; ?>
                                <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: <?= empty($thumbnailUrl) ? 'flex' : 'none' ?>; align-items: center; justify-content: center; color: #fff;">
                                    <i class="fas fa-graduation-cap fa-3x"></i>
                                </div>
                                <div class="p-3">
                                    <h5 class="course-card-title"><?= esc($course['title']) ?></h5>
                                    <div class="text-muted small mb-2"><?= esc($course['first_name'] . ' ' . $course['last_name']) ?></div>
                                    <?php if ($course['avg_rating'] > 0): ?>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="text-warning fw-bold"><?= number_format($course['avg_rating'], 1) ?></span>
                                            <i class="fas fa-star text-warning"></i>
                                            <span class="text-muted small">(<?= number_format($course['total_reviews']) ?>)</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="fw-bold">
                                        <?php if ($course['is_free']): ?>
                                            <span class="text-success">FREE</span>
                                        <?php else: ?>
                                            <?php if ($course['discount_price']): ?>
                                                <span class="text-decoration-line-through text-muted me-2">$<?= number_format($course['price'], 2) ?></span>
                                                <span class="text-danger">$<?= number_format($course['discount_price'], 2) ?></span>
                                            <?php else: ?>
                                                $<?= number_format($course['price'], 2) ?>
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
    </div>
</div>

<?= $this->endSection() ?>

