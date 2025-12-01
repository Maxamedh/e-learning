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
                    <a href="<?= base_url('admin/courses') ?>" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back to Courses
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Create Course Form -->
    <div class="card mt-4">
        <div class="card-body">
            <form method="POST" action="<?= base_url('admin/courses/store') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="title" class="form-label">Course Title *</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= old('title') ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="short_description" class="form-label">Short Description</label>
                        <textarea class="form-control" id="short_description" name="short_description" rows="2"><?= old('short_description') ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Full Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required><?= old('description') ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="instructor_id" class="form-label">Instructor *</label>
                        <select class="form-select" id="instructor_id" name="instructor_id" required>
                            <option value="">Select Instructor</option>
                            <?php foreach ($instructors as $instructor): ?>
                                <option value="<?= $instructor['id'] ?>" <?= old('instructor_id') == $instructor['id'] ? 'selected' : '' ?>>
                                    <?= esc($instructor['first_name'] . ' ' . $instructor['last_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label">Category *</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= old('category_id') == $category['id'] ? 'selected' : '' ?>>
                                    <?= esc($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label">Price *</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="<?= old('price', '0') ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="discount_price" class="form-label">Discount Price</label>
                        <input type="number" class="form-control" id="discount_price" name="discount_price" step="0.01" min="0" value="<?= old('discount_price') ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="level" class="form-label">Level *</label>
                        <select class="form-select" id="level" name="level" required>
                            <option value="beginner" <?= old('level') == 'beginner' ? 'selected' : '' ?>>Beginner</option>
                            <option value="intermediate" <?= old('level') == 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
                            <option value="advanced" <?= old('level') == 'advanced' ? 'selected' : '' ?>>Advanced</option>
                            <option value="all" <?= old('level') == 'all' ? 'selected' : '' ?>>All Levels</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="language" class="form-label">Language</label>
                        <input type="text" class="form-control" id="language" name="language" value="<?= old('language', 'English') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="draft" <?= old('status') == 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="published" <?= old('status') == 'published' ? 'selected' : '' ?>>Published</option>
                            <option value="unpublished" <?= old('status') == 'unpublished' ? 'selected' : '' ?>>Unpublished</option>
                            <option value="pending" <?= old('status') == 'pending' ? 'selected' : '' ?>>Pending</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="thumbnail" class="form-label">Thumbnail Image *</label>
                        <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                        <small class="text-muted">Upload course thumbnail image (JPG, PNG, GIF - Max 5MB)</small>
                        <div id="thumbnailPreview" class="mt-2" style="display: none;">
                            <img id="thumbnailPreviewImg" src="" alt="Thumbnail Preview" style="max-width: 200px; max-height: 150px; border-radius: 8px;">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="promo_video" class="form-label">Promo Video *</label>
                        <input type="file" class="form-control" id="promo_video" name="promo_video" accept="video/*">
                        <small class="text-muted">Upload promo video (MP4, WebM - Max 100MB)</small>
                        <div id="videoPreview" class="mt-2" style="display: none;">
                            <video id="videoPreviewPlayer" controls style="max-width: 200px; max-height: 150px; border-radius: 8px;" src=""></video>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="thumbnail_url" class="form-label">Or Thumbnail URL (Alternative)</label>
                        <input type="url" class="form-control" id="thumbnail_url" name="thumbnail_url" value="<?= old('thumbnail_url') ?>" placeholder="https://example.com/image.jpg">
                        <small class="text-muted">Use URL if you prefer to host image externally</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="promo_video_url" class="form-label">Or Promo Video URL (Alternative)</label>
                        <input type="url" class="form-control" id="promo_video_url" name="promo_video_url" value="<?= old('promo_video_url') ?>" placeholder="https://example.com/video.mp4">
                        <small class="text-muted">Use URL if you prefer to host video externally (YouTube, Vimeo, etc.)</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="requirements" class="form-label">Requirements (one per line)</label>
                        <textarea class="form-control" id="requirements" name="requirements" rows="3"><?= old('requirements') ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="learning_outcomes" class="form-label">Learning Outcomes (one per line)</label>
                        <textarea class="form-control" id="learning_outcomes" name="learning_outcomes" rows="3"><?= old('learning_outcomes') ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_free" name="is_free" value="1" <?= old('is_free') ? 'checked' : '' ?> onchange="togglePriceFields()">
                            <label class="form-check-label fw-bold" for="is_free">
                                <i class="fa-solid fa-gift text-success me-2"></i>Free Course
                            </label>
                            <small class="d-block text-muted mt-1">If checked, this course will be available for free</small>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" <?= old('is_featured') ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="is_featured">
                                <i class="fa-solid fa-star text-warning me-2"></i>Featured Course
                            </label>
                            <small class="d-block text-muted mt-1">Featured courses appear on homepage</small>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="fa-solid fa-info-circle me-2"></i>
                    <strong>Note:</strong> Course content videos (lectures) are added after creating the course. Go to Course Sections → Lectures to add video content.
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-2"></i>Create Course
                    </button>
                    <a href="<?= base_url('admin/courses') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Preview thumbnail image
document.getElementById('thumbnail').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('thumbnailPreviewImg').src = e.target.result;
            document.getElementById('thumbnailPreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

// Preview video
document.getElementById('promo_video').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const url = URL.createObjectURL(file);
        document.getElementById('videoPreviewPlayer').src = url;
        document.getElementById('videoPreview').style.display = 'block';
    }
});

// Toggle price fields based on free course
function togglePriceFields() {
    const isFree = document.getElementById('is_free').checked;
    const priceField = document.getElementById('price');
    const discountField = document.getElementById('discount_price');
    
    if (isFree) {
        priceField.value = '0';
        priceField.disabled = true;
        discountField.disabled = true;
    } else {
        priceField.disabled = false;
        discountField.disabled = false;
    }
}

// Initialize on page load
togglePriceFields();
</script>

<?= $this->endSection() ?>

