<?= $this->extend('layouts/portal') ?>
<?= $this->section('content') ?>

<style>
    .hero-section {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: #fff;
        padding: 4rem 0;
        margin-bottom: 3rem;
    }
    
    .hero-section h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .hero-section p {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }
    
    .search-box {
        background: #fff;
        border-radius: 8px;
        padding: 0.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .search-box input {
        border: none;
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }
    
    .search-box input:focus {
        outline: none;
        box-shadow: none;
    }
    
    .search-box button {
        background: var(--text-dark);
        color: #fff;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 4px;
        font-weight: 600;
    }
    
    .course-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
    }
    
    .course-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    
    .course-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .course-card-body {
        padding: 1rem;
    }
    
    .course-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .course-card-instructor {
        font-size: 0.875rem;
        color: var(--text-gray);
        margin-bottom: 0.5rem;
    }
    
    .course-card-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .course-card-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-dark);
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: var(--text-dark);
    }
    
    .category-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.2s;
        text-decoration: none;
        color: var(--text-dark);
        display: block;
    }
    
    .category-card:hover {
        border-color: var(--primary-blue);
        color: var(--primary-blue);
        transform: translateY(-2px);
    }
    
    .category-card i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: var(--primary-blue);
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1>Learn Without Limits</h1>
                <p>Start, switch, or advance your career with thousands of courses, Professional Certificates, and degrees from world-class instructors.</p>
                <form action="<?= base_url('courses') ?>" method="GET" class="search-box d-flex">
                    <input type="text" name="search" class="flex-grow-1" placeholder="Search for anything..." value="<?= esc($search ?? '') ?>">
                    <button type="submit"><i class="fas fa-search me-2"></i>Search</button>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <!-- Categories -->
    <?php if (!empty($categories)): ?>
    <section class="mb-5">
        <h2 class="section-title">Explore Top Categories</h2>
        <div class="row g-3">
            <?php foreach (array_slice($categories, 0, 8) as $category): ?>
                <div class="col-6 col-md-3">
                    <a href="<?= base_url('categories/' . $category['id']) ?>" class="category-card">
                        <?php if (!empty($category['icon'])): ?>
                            <i class="<?= esc($category['icon']) ?>"></i>
                        <?php else: ?>
                            <i class="fas fa-folder"></i>
                        <?php endif; ?>
                        <div class="fw-bold"><?= esc($category['name']) ?></div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Featured Courses -->
    <?php if (!empty($featuredCourses)): ?>
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">Featured Courses</h2>
            <a href="<?= base_url('courses') ?>" class="btn btn-link">View All <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4">
            <?php foreach ($featuredCourses as $course): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="<?= base_url('courses/' . $course['id']) ?>" class="text-decoration-none">
                        <div class="course-card">
                            <?php if (!empty($course['thumbnail_url'])): ?>
                                <img src="<?= esc($course['thumbnail_url']) ?>" alt="<?= esc($course['title']) ?>" onerror="this.src='<?= base_url('assets/images/placeholder-course.jpg') ?>'">
                            <?php else: ?>
                                <div style="height: 200px; background: #e0e0e0; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            <?php endif; ?>
                            <div class="course-card-body">
                                <h5 class="course-card-title"><?= esc($course['title']) ?></h5>
                                <div class="course-card-instructor"><?= esc($course['instructor_name'] ?? 'Instructor') ?></div>
                                <?php if ($course['avg_rating'] > 0): ?>
                                    <div class="course-card-rating">
                                        <span class="text-warning"><?= number_format($course['avg_rating'], 1) ?></span>
                                        <i class="fas fa-star text-warning"></i>
                                        <span class="text-muted">(<?= number_format($course['total_reviews']) ?>)</span>
                                    </div>
                                <?php endif; ?>
                                <div class="course-card-price">
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
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Popular Courses -->
    <?php if (!empty($popularCourses)): ?>
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">Popular Courses</h2>
            <a href="<?= base_url('courses') ?>" class="btn btn-link">View All <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4">
            <?php foreach ($popularCourses as $course): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="<?= base_url('courses/' . $course['id']) ?>" class="text-decoration-none">
                        <div class="course-card">
                            <?php if (!empty($course['thumbnail_url'])): ?>
                                <img src="<?= esc($course['thumbnail_url']) ?>" alt="<?= esc($course['title']) ?>" onerror="this.src='<?= base_url('assets/images/placeholder-course.jpg') ?>'">
                            <?php else: ?>
                                <div style="height: 200px; background: #e0e0e0; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            <?php endif; ?>
                            <div class="course-card-body">
                                <h5 class="course-card-title"><?= esc($course['title']) ?></h5>
                                <div class="course-card-instructor"><?= esc($course['instructor_name'] ?? 'Instructor') ?></div>
                                <?php if ($course['avg_rating'] > 0): ?>
                                    <div class="course-card-rating">
                                        <span class="text-warning"><?= number_format($course['avg_rating'], 1) ?></span>
                                        <i class="fas fa-star text-warning"></i>
                                        <span class="text-muted">(<?= number_format($course['total_reviews']) ?>)</span>
                                    </div>
                                <?php endif; ?>
                                <div class="course-card-price">
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
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

