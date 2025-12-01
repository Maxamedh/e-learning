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

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mt-4" id="courseTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                <i class="fa-solid fa-info-circle me-2"></i>Course Details
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="content-tab" data-bs-toggle="tab" data-bs-target="#content" type="button" role="tab">
                <i class="fa-solid fa-book-open me-2"></i>Sections & Lectures
                <?php if (!empty($sections)): ?>
                    <span class="badge bg-primary ms-1"><?= count($sections) ?></span>
                <?php endif; ?>
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="courseTabsContent">
        <!-- Course Details Tab -->
        <div class="tab-pane fade show active" id="details" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?= base_url('admin/courses/update/' . $course['id']) ?>" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="title" class="form-label">Course Title *</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= old('title', $course['title']) ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="short_description" class="form-label">Short Description</label>
                        <textarea class="form-control" id="short_description" name="short_description" rows="2"><?= old('short_description', $course['short_description'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Full Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required><?= old('description', $course['description']) ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="instructor_id" class="form-label">Instructor *</label>
                        <select class="form-select" id="instructor_id" name="instructor_id" required>
                            <option value="">Select Instructor</option>
                            <?php foreach ($instructors as $instructor): ?>
                                <option value="<?= $instructor['id'] ?>" <?= (old('instructor_id', $course['instructor_id']) == $instructor['id']) ? 'selected' : '' ?>>
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
                                <option value="<?= $category['id'] ?>" <?= (old('category_id', $course['category_id']) == $category['id']) ? 'selected' : '' ?>>
                                    <?= esc($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label">Price *</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="<?= old('price', $course['price']) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="discount_price" class="form-label">Discount Price</label>
                        <input type="number" class="form-control" id="discount_price" name="discount_price" step="0.01" min="0" value="<?= old('discount_price', $course['discount_price'] ?? '') ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="level" class="form-label">Level *</label>
                        <select class="form-select" id="level" name="level" required>
                            <option value="beginner" <?= (old('level', $course['level']) == 'beginner') ? 'selected' : '' ?>>Beginner</option>
                            <option value="intermediate" <?= (old('level', $course['level']) == 'intermediate') ? 'selected' : '' ?>>Intermediate</option>
                            <option value="advanced" <?= (old('level', $course['level']) == 'advanced') ? 'selected' : '' ?>>Advanced</option>
                            <option value="all" <?= (old('level', $course['level']) == 'all') ? 'selected' : '' ?>>All Levels</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="language" class="form-label">Language</label>
                        <input type="text" class="form-control" id="language" name="language" value="<?= old('language', $course['language'] ?? 'English') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="draft" <?= (old('status', $course['status']) == 'draft') ? 'selected' : '' ?>>Draft</option>
                            <option value="published" <?= (old('status', $course['status']) == 'published') ? 'selected' : '' ?>>Published</option>
                            <option value="unpublished" <?= (old('status', $course['status']) == 'unpublished') ? 'selected' : '' ?>>Unpublished</option>
                            <option value="pending" <?= (old('status', $course['status']) == 'pending') ? 'selected' : '' ?>>Pending</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="thumbnail" class="form-label">Thumbnail Image</label>
                        <?php if (!empty($course['thumbnail_url'])): ?>
                            <div class="mb-2">
                                <img src="<?= esc($course['thumbnail_url']) ?>" alt="Current Thumbnail" class="rounded" style="max-width: 200px; max-height: 150px; object-fit: cover;" onerror="this.style.display='none'">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                        <small class="text-muted">Upload new thumbnail to replace current (JPG, PNG, GIF - Max 5MB)</small>
                        <div id="thumbnailPreview" class="mt-2" style="display: none;">
                            <img id="thumbnailPreviewImg" src="" alt="Thumbnail Preview" style="max-width: 200px; max-height: 150px; border-radius: 8px;">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="promo_video" class="form-label">Promo Video</label>
                        <?php if (!empty($course['promo_video_url'])): ?>
                            <div class="mb-2">
                                <video controls class="rounded" style="max-width: 200px; max-height: 150px;" src="<?= esc($course['promo_video_url']) ?>" onerror="this.style.display='none'"></video>
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="promo_video" name="promo_video" accept="video/*">
                        <small class="text-muted">Upload new video to replace current (MP4, WebM - Max 100MB)</small>
                        <div id="videoPreview" class="mt-2" style="display: none;">
                            <video id="videoPreviewPlayer" controls style="max-width: 200px; max-height: 150px; border-radius: 8px;" src=""></video>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="thumbnail_url" class="form-label">Or Thumbnail URL (Alternative)</label>
                        <input type="url" class="form-control" id="thumbnail_url" name="thumbnail_url" value="<?= old('thumbnail_url', $course['thumbnail_url'] ?? '') ?>" placeholder="https://example.com/image.jpg">
                        <small class="text-muted">Use URL if you prefer to host image externally</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="promo_video_url" class="form-label">Or Promo Video URL (Alternative)</label>
                        <input type="url" class="form-control" id="promo_video_url" name="promo_video_url" value="<?= old('promo_video_url', $course['promo_video_url'] ?? '') ?>" placeholder="https://example.com/video.mp4">
                        <small class="text-muted">Use URL if you prefer to host video externally (YouTube, Vimeo, etc.)</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="requirements" class="form-label">Requirements (one per line)</label>
                        <textarea class="form-control" id="requirements" name="requirements" rows="3"><?= old('requirements', is_array($course['requirements'] ?? null) ? implode("\n", json_decode($course['requirements'], true) ?? []) : '') ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="learning_outcomes" class="form-label">Learning Outcomes (one per line)</label>
                        <textarea class="form-control" id="learning_outcomes" name="learning_outcomes" rows="3"><?= old('learning_outcomes', is_array($course['learning_outcomes'] ?? null) ? implode("\n", json_decode($course['learning_outcomes'], true) ?? []) : '') ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_free" name="is_free" value="1" <?= (old('is_free', $course['is_free']) ? 'checked' : '') ?> onchange="togglePriceFields()">
                            <label class="form-check-label fw-bold" for="is_free">
                                <i class="fa-solid fa-gift text-success me-2"></i>Free Course
                            </label>
                            <small class="d-block text-muted mt-1">If checked, this course will be available for free</small>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" <?= (old('is_featured', $course['is_featured']) ? 'checked' : '') ?>>
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
                                <i class="fa-solid fa-save me-2"></i>Update Course
                            </button>
                            <a href="<?= base_url('admin/courses') ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sections & Lectures Tab -->
        <div class="tab-pane fade" id="content" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-list me-2"></i>Course Sections & Lectures
                        </h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sectionModal">
                            <i class="fa-solid fa-plus me-2"></i>Add Section
                        </button>
                    </div>

                    <?php $sections = $sections ?? []; ?>
                    <?php if (empty($sections)): ?>
                        <div class="text-center py-5">
                            <i class="fa-solid fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No sections yet</h5>
                            <p class="text-muted">Create your first section to start adding course content (lectures/videos).</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sectionModal">
                                <i class="fa-solid fa-plus me-2"></i>Create First Section
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="accordion" id="sectionsAccordion">
                            <?php foreach ($sections as $index => $section): ?>
                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="heading<?= $section['id'] ?>">
                                        <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $section['id'] ?>">
                                            <div class="flex-grow-1">
                                                <i class="fa-solid fa-folder me-2"></i>
                                                <strong><?= esc($section['title']) ?></strong>
                                                <span class="badge bg-info ms-2"><?= count($section['lectures']) ?> Lectures</span>
                                                <?php if (!$section['is_published']): ?>
                                                    <span class="badge bg-secondary ms-1">Draft</span>
                                                <?php endif; ?>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse<?= $section['id'] ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" data-bs-parent="#sectionsAccordion">
                                        <div class="accordion-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div>
                                                    <?php if ($section['description']): ?>
                                                        <p class="text-muted mb-0"><?= esc($section['description']) ?></p>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-success me-1" data-bs-toggle="modal" data-bs-target="#lectureModal<?= $section['id'] ?>">
                                                        <i class="fa-solid fa-plus me-1"></i>Add Lecture
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#editSectionModal<?= $section['id'] ?>">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                    </button>
                                                    <a href="<?= base_url('admin/sections/' . $course['id'] . '/delete/' . $section['id']) ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       onclick="return confirm('Are you sure? This will delete all lectures in this section!')">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- Lectures List -->
                                            <?php if (empty($section['lectures'])): ?>
                                                <div class="alert alert-info">
                                                    <i class="fa-solid fa-info-circle me-2"></i>
                                                    No lectures in this section. Click "Add Lecture" to add video content.
                                                </div>
                                            <?php else: ?>
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th width="50">#</th>
                                                                <th>Lecture Title</th>
                                                                <th>Type</th>
                                                                <th>Preview</th>
                                                                <th>Status</th>
                                                                <th class="text-center">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($section['lectures'] as $lecture): ?>
                                                                <tr>
                                                                    <td><?= $lecture['order_index'] ?></td>
                                                                    <td>
                                                                        <strong><?= esc($lecture['title']) ?></strong>
                                                                        <?php if ($lecture['description']): ?>
                                                                            <br><small class="text-muted"><?= esc(substr($lecture['description'], 0, 50)) ?>...</small>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        $typeIcons = [
                                                                            'video' => 'fa-video',
                                                                            'article' => 'fa-file-text',
                                                                            'quiz' => 'fa-question-circle',
                                                                            'assignment' => 'fa-tasks',
                                                                            'live' => 'fa-broadcast-tower'
                                                                        ];
                                                                        $typeIcon = $typeIcons[$lecture['content_type']] ?? 'fa-file';
                                                                        ?>
                                                                        <i class="fa-solid <?= $typeIcon ?> me-1"></i>
                                                                        <?= ucfirst($lecture['content_type']) ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if ($lecture['is_preview']): ?>
                                                                            <span class="badge bg-success">
                                                                                <i class="fa-solid fa-eye me-1"></i>Preview
                                                                            </span>
                                                                        <?php else: ?>
                                                                            <span class="badge bg-secondary">Locked</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge bg-<?= $lecture['is_published'] ? 'success' : 'secondary' ?>">
                                                                            <?= $lecture['is_published'] ? 'Published' : 'Draft' ?>
                                                                        </span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#editLectureModal<?= $lecture['id'] ?>">
                                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                                        </button>
                                                                        <a href="<?= base_url('admin/lectures/' . $course['id'] . '/' . $section['id'] . '/delete/' . $lecture['id']) ?>" 
                                                                           class="btn btn-sm btn-danger" 
                                                                           onclick="return confirm('Are you sure?')">
                                                                            <i class="fa-solid fa-trash-can"></i>
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
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Section Modal -->
<div class="modal fade" id="sectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= base_url('admin/sections/' . $course['id'] . '/store') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Section Title *</label>
                        <input type="text" class="form-control" id="title" name="title" required placeholder="e.g., Introduction, Getting Started">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief description of this section"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" checked>
                        <label class="form-check-label" for="is_published">Published</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Section</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Lecture Modals for each section -->
<?php foreach ($sections as $section): ?>
<div class="modal fade" id="lectureModal<?= $section['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Lecture to: <?= esc($section['title']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= base_url('admin/lectures/' . $course['id'] . '/' . $section['id'] . '/store') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title_<?= $section['id'] ?>" class="form-label">Lecture Title *</label>
                        <input type="text" class="form-control" id="title_<?= $section['id'] ?>" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="content_type_<?= $section['id'] ?>" class="form-label">Content Type *</label>
                        <select class="form-select" id="content_type_<?= $section['id'] ?>" name="content_type" required onchange="toggleContentFields(<?= $section['id'] ?>)">
                            <option value="video">Video</option>
                            <option value="article">Article</option>
                            <option value="quiz">Quiz</option>
                            <option value="assignment">Assignment</option>
                            <option value="live">Live Session</option>
                        </select>
                    </div>
                    <div class="mb-3" id="videoFields_<?= $section['id'] ?>">
                        <label for="video_<?= $section['id'] ?>" class="form-label">Upload Video *</label>
                        <input type="file" class="form-control" id="video_<?= $section['id'] ?>" name="video" accept="video/*">
                        <small class="text-muted">MP4, WebM, MOV, AVI - Max 500MB</small>
                    </div>
                    <div class="mb-3" id="videoUrlFields_<?= $section['id'] ?>">
                        <label for="video_url_<?= $section['id'] ?>" class="form-label">Or Video URL (Alternative)</label>
                        <input type="url" class="form-control" id="video_url_<?= $section['id'] ?>" name="video_url" placeholder="https://example.com/video.mp4 or YouTube/Vimeo URL">
                    </div>
                    <div class="mb-3" id="articleFields_<?= $section['id'] ?>" style="display: none;">
                        <label for="article_content_<?= $section['id'] ?>" class="form-label">Article Content</label>
                        <textarea class="form-control" id="article_content_<?= $section['id'] ?>" name="article_content" rows="10"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="description_<?= $section['id'] ?>" class="form-label">Description</label>
                        <textarea class="form-control" id="description_<?= $section['id'] ?>" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="video_duration_<?= $section['id'] ?>" class="form-label">Video Duration (seconds)</label>
                            <input type="number" class="form-control" id="video_duration_<?= $section['id'] ?>" name="video_duration" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="order_index_<?= $section['id'] ?>" class="form-label">Order</label>
                            <input type="number" class="form-control" id="order_index_<?= $section['id'] ?>" name="order_index" value="<?= count($section['lectures']) + 1 ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_preview_<?= $section['id'] ?>" name="is_preview" value="1">
                                <label class="form-check-label" for="is_preview_<?= $section['id'] ?>">
                                    <i class="fa-solid fa-eye text-success me-1"></i>Preview (Free to watch)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_published_<?= $section['id'] ?>" name="is_published" value="1" checked>
                                <label class="form-check-label" for="is_published_<?= $section['id'] ?>">Published</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Lecture</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Edit Section Modals -->
<?php foreach ($sections as $section): ?>
<div class="modal fade" id="editSectionModal<?= $section['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= base_url('admin/sections/' . $course['id'] . '/update/' . $section['id']) ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_title_<?= $section['id'] ?>" class="form-label">Section Title *</label>
                        <input type="text" class="form-control" id="edit_title_<?= $section['id'] ?>" name="title" value="<?= esc($section['title']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description_<?= $section['id'] ?>" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description_<?= $section['id'] ?>" name="description" rows="3"><?= esc($section['description'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_order_index_<?= $section['id'] ?>" class="form-label">Order</label>
                        <input type="number" class="form-control" id="edit_order_index_<?= $section['id'] ?>" name="order_index" value="<?= $section['order_index'] ?>">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="edit_is_published_<?= $section['id'] ?>" name="is_published" value="1" <?= $section['is_published'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="edit_is_published_<?= $section['id'] ?>">Published</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Section</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Edit Lecture Modals -->
<?php foreach ($sections as $section): ?>
    <?php foreach ($section['lectures'] as $lecture): ?>
    <div class="modal fade" id="editLectureModal<?= $lecture['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Lecture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= base_url('admin/lectures/' . $course['id'] . '/' . $section['id'] . '/update/' . $lecture['id']) ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_lecture_title_<?= $lecture['id'] ?>" class="form-label">Lecture Title *</label>
                            <input type="text" class="form-control" id="edit_lecture_title_<?= $lecture['id'] ?>" name="title" value="<?= esc($lecture['title']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_lecture_content_type_<?= $lecture['id'] ?>" class="form-label">Content Type *</label>
                            <select class="form-select" id="edit_lecture_content_type_<?= $lecture['id'] ?>" name="content_type" required onchange="toggleEditContentFields(<?= $lecture['id'] ?>)">
                                <option value="video" <?= $lecture['content_type'] == 'video' ? 'selected' : '' ?>>Video</option>
                                <option value="article" <?= $lecture['content_type'] == 'article' ? 'selected' : '' ?>>Article</option>
                                <option value="quiz" <?= $lecture['content_type'] == 'quiz' ? 'selected' : '' ?>>Quiz</option>
                                <option value="assignment" <?= $lecture['content_type'] == 'assignment' ? 'selected' : '' ?>>Assignment</option>
                                <option value="live" <?= $lecture['content_type'] == 'live' ? 'selected' : '' ?>>Live Session</option>
                            </select>
                        </div>
                        <div class="mb-3" id="edit_videoFields_<?= $lecture['id'] ?>" style="display: <?= $lecture['content_type'] == 'video' ? 'block' : 'none' ?>;">
                            <label for="edit_video_<?= $lecture['id'] ?>" class="form-label">Upload New Video</label>
                            <input type="file" class="form-control" id="edit_video_<?= $lecture['id'] ?>" name="video" accept="video/*">
                            <small class="text-muted">Leave empty to keep current video</small>
                        </div>
                        <div class="mb-3" id="edit_videoUrlFields_<?= $lecture['id'] ?>" style="display: <?= $lecture['content_type'] == 'video' ? 'block' : 'none' ?>;">
                            <label for="edit_video_url_<?= $lecture['id'] ?>" class="form-label">Video URL</label>
                            <input type="url" class="form-control" id="edit_video_url_<?= $lecture['id'] ?>" name="video_url" value="<?= esc($lecture['video_url'] ?? '') ?>" placeholder="https://example.com/video.mp4">
                        </div>
                        <div class="mb-3" id="edit_articleFields_<?= $lecture['id'] ?>" style="display: <?= $lecture['content_type'] == 'article' ? 'block' : 'none' ?>;">
                            <label for="edit_article_content_<?= $lecture['id'] ?>" class="form-label">Article Content</label>
                            <textarea class="form-control" id="edit_article_content_<?= $lecture['id'] ?>" name="article_content" rows="10"><?= esc($lecture['article_content'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_lecture_description_<?= $lecture['id'] ?>" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_lecture_description_<?= $lecture['id'] ?>" name="description" rows="3"><?= esc($lecture['description'] ?? '') ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_lecture_video_duration_<?= $lecture['id'] ?>" class="form-label">Video Duration (seconds)</label>
                                <input type="number" class="form-control" id="edit_lecture_video_duration_<?= $lecture['id'] ?>" name="video_duration" value="<?= $lecture['video_duration'] ?? '' ?>" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_lecture_order_index_<?= $lecture['id'] ?>" class="form-label">Order</label>
                                <input type="number" class="form-control" id="edit_lecture_order_index_<?= $lecture['id'] ?>" name="order_index" value="<?= $lecture['order_index'] ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="edit_lecture_is_preview_<?= $lecture['id'] ?>" name="is_preview" value="1" <?= $lecture['is_preview'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="edit_lecture_is_preview_<?= $lecture['id'] ?>">
                                        <i class="fa-solid fa-eye text-success me-1"></i>Preview (Free to watch)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="edit_lecture_is_published_<?= $lecture['id'] ?>" name="is_published" value="1" <?= $lecture['is_published'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="edit_lecture_is_published_<?= $lecture['id'] ?>">Published</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Lecture</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endforeach; ?>

<script>
// Toggle content fields based on content type
function toggleContentFields(sectionId) {
    const contentType = document.getElementById('content_type_' + sectionId).value;
    const videoFields = document.getElementById('videoFields_' + sectionId);
    const videoUrlFields = document.getElementById('videoUrlFields_' + sectionId);
    const articleFields = document.getElementById('articleFields_' + sectionId);
    
    if (contentType === 'video') {
        videoFields.style.display = 'block';
        videoUrlFields.style.display = 'block';
        articleFields.style.display = 'none';
    } else if (contentType === 'article') {
        videoFields.style.display = 'none';
        videoUrlFields.style.display = 'none';
        articleFields.style.display = 'block';
    } else {
        videoFields.style.display = 'none';
        videoUrlFields.style.display = 'none';
        articleFields.style.display = 'none';
    }
}

// Initialize content fields for all sections
<?php foreach ($sections as $section): ?>
toggleContentFields(<?= $section['id'] ?>);
<?php endforeach; ?>

// Toggle content fields for edit modals
function toggleEditContentFields(lectureId) {
    const contentType = document.getElementById('edit_lecture_content_type_' + lectureId).value;
    const videoFields = document.getElementById('edit_videoFields_' + lectureId);
    const videoUrlFields = document.getElementById('edit_videoUrlFields_' + lectureId);
    const articleFields = document.getElementById('edit_articleFields_' + lectureId);
    
    if (contentType === 'video') {
        videoFields.style.display = 'block';
        videoUrlFields.style.display = 'block';
        articleFields.style.display = 'none';
    } else if (contentType === 'article') {
        videoFields.style.display = 'none';
        videoUrlFields.style.display = 'none';
        articleFields.style.display = 'block';
    } else {
        videoFields.style.display = 'none';
        videoUrlFields.style.display = 'none';
        articleFields.style.display = 'none';
    }
}
</script>

<script>
// Preview thumbnail image
document.getElementById('thumbnail')?.addEventListener('change', function(e) {
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
document.getElementById('promo_video')?.addEventListener('change', function(e) {
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

