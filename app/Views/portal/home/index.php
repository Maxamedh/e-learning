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
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .course-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #f0f0f0;
    }

    .course-card img[src=""],
    .course-card img:not([src]) {
        display: none;
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
                <p>Start, switch, or advance your career with thousands of courses, Professional Certificates, and
                    degrees from world-class instructors.</p>
                <form action="<?= base_url('courses') ?>" method="GET" class="search-box d-flex">
                    <input type="text" name="search" class="flex-grow-1" placeholder="Search for anything..."
                        value="<?= esc($search ?? '') ?>">
                    <button type="submit"><i class="fas fa-search me-2"></i>Search</button>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="container mb-5">
    <div class="text-center mb-4">
        <p class="text-muted fw-bold mb-3">Trusted by over 5,000 companies and millions of learners around the world
        </p>
        <div class="d-flex justify-content-center align-items-center flex-wrap gap-5 opacity-50">
            <img src="<?= base_url('logo/png/logo@2x-8.png') ?>" alt="Partner 1"
                style="height: 40px; filter: grayscale(100%);">
            <img src="<?= base_url('logo/png/logo@2x-8.png') ?>" alt="Partner 2"
                style="height: 40px; filter: grayscale(100%);">
            <img src="<?= base_url('logo/png/logo@2x-8.png') ?>" alt="Partner 3"
                style="height: 40px; filter: grayscale(100%);">
            <img src="<?= base_url('logo/png/logo@2x-8.png') ?>" alt="Partner 4"
                style="height: 40px; filter: grayscale(100%);">
            <img src="<?= base_url('logo/png/logo@2x-8.png') ?>" alt="Partner 5"
                style="height: 40px; filter: grayscale(100%);">
        </div>
    </div>
</div>

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
                <a href="<?= base_url('courses') ?>" class="btn btn-link">View All <i
                        class="fas fa-arrow-right ms-1"></i></a>
            </div>
            <div class="row g-4">
                <?php foreach ($featuredCourses as $course): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="<?= base_url('courses/' . $course['id']) ?>" class="text-decoration-none">
                            <div class="course-card">
                                <?php
                                $thumbnailUrl = !empty($course['thumbnail_url']) ? esc($course['thumbnail_url']) : '';
                                ?>
                                <?php if (!empty($thumbnailUrl)): ?>
                                    <img src="<?= $thumbnailUrl ?>" alt="<?= esc($course['title']) ?>"
                                        style="width: 100%; height: 200px; object-fit: cover;" loading="lazy"
                                        onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <?php endif; ?>
                                <div
                                    style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: <?= empty($thumbnailUrl) ? 'flex' : 'none' ?>; align-items: center; justify-content: center; color: #fff;">
                                    <i class="fas fa-graduation-cap fa-3x"></i>
                                </div>
                                <div class="course-card-body">
                                    <h5 class="course-card-title"><?= esc($course['title']) ?></h5>
                                    <div class="course-card-instructor"><?= esc($course['instructor_name'] ?? 'Instructor') ?>
                                    </div>
                                    <?php if (isset($course['avg_rating']) && $course['avg_rating'] > 0): ?>
                                        <div class="course-card-rating">
                                            <span class="text-warning"><?= number_format($course['avg_rating'], 1) ?></span>
                                            <i class="fas fa-star text-warning"></i>
                                            <span class="text-muted">(<?= number_format($course['total_reviews'] ?? 0) ?>)</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="course-card-price">
                                        <?php if (!empty($course['is_free'])): ?>
                                            <span class="text-success">FREE</span>
                                        <?php else: ?>
                                            <?php if (!empty($course['discount_price'])): ?>
                                                <span
                                                    class="text-decoration-line-through text-muted me-2">$<?= number_format($course['price'] ?? 0, 2) ?></span>
                                                <span class="text-danger">$<?= number_format($course['discount_price'], 2) ?></span>
                                            <?php else: ?>
                                                $<?= number_format($course['price'] ?? 0, 2) ?>
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
                <a href="<?= base_url('courses') ?>" class="btn btn-link">View All <i
                        class="fas fa-arrow-right ms-1"></i></a>
            </div>
            <div class="row g-4">
                <?php foreach ($popularCourses as $course): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="<?= base_url('courses/' . $course['id']) ?>" class="text-decoration-none">
                            <div class="course-card">
                                <?php
                                $thumbnailUrl = !empty($course['thumbnail_url']) ? esc($course['thumbnail_url']) : '';
                                ?>
                                <?php if (!empty($thumbnailUrl)): ?>
                                    <img src="<?= $thumbnailUrl ?>" alt="<?= esc($course['title']) ?>"
                                        style="width: 100%; height: 200px; object-fit: cover;" loading="lazy"
                                        onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <?php endif; ?>
                                <div
                                    style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: <?= empty($thumbnailUrl) ? 'flex' : 'none' ?>; align-items: center; justify-content: center; color: #fff;">
                                    <i class="fas fa-graduation-cap fa-3x"></i>
                                </div>
                                <div class="course-card-body">
                                    <h5 class="course-card-title"><?= esc($course['title']) ?></h5>
                                    <div class="course-card-instructor"><?= esc($course['instructor_name'] ?? 'Instructor') ?>
                                    </div>
                                    <?php if (isset($course['avg_rating']) && $course['avg_rating'] > 0): ?>
                                        <div class="course-card-rating">
                                            <span class="text-warning"><?= number_format($course['avg_rating'], 1) ?></span>
                                            <i class="fas fa-star text-warning"></i>
                                            <span class="text-muted">(<?= number_format($course['total_reviews'] ?? 0) ?>)</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="course-card-price">
                                        <?php if (!empty($course['is_free'])): ?>
                                            <span class="text-success">FREE</span>
                                        <?php else: ?>
                                            <?php if (!empty($course['discount_price'])): ?>
                                                <span
                                                    class="text-decoration-line-through text-muted me-2">$<?= number_format($course['price'] ?? 0, 2) ?></span>
                                                <span class="text-danger">$<?= number_format($course['discount_price'], 2) ?></span>
                                            <?php else: ?>
                                                $<?= number_format($course['price'] ?? 0, 2) ?>
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