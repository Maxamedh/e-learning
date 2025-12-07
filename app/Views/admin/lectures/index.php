<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-column flex-md-row flex-lg-row mt-3">
                <div class="flex-grow-1">
                    <h3 class="mb-2 text-color-2"><?= $title ?></h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin/courses') ?>">Courses</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('admin/sections/' . $course['id']) ?>"><?= esc($course['title']) ?></a></li>
                            <li class="breadcrumb-item active"><?= $currentSection ? esc($currentSection['title']) : 'Select Section' ?></li>
                        </ol>
                    </nav>
                </div>
                <div class="mt-3 mt-lg-0">
                    <a href="<?= base_url('admin/sections/' . $course['id']) ?>" class="btn btn-secondary me-2">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back to Sections
                    </a>
                    <?php if ($sectionId): ?>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#lectureModal">
                            <i class="fa-solid fa-plus me-2"></i>Add Lecture
                        </button>
                    <?php endif; ?>
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

    <!-- Section Selector -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="mb-3">Select Section to Manage Lectures:</h5>
            <div class="row">
                <?php foreach ($sections as $section): ?>
                    <div class="col-md-3 mb-2">
                        <a href="<?= base_url('admin/lectures/' . $course['id'] . '/' . $section['id']) ?>" 
                           class="btn btn-outline-primary w-100 <?= ($sectionId == $section['id']) ? 'active' : '' ?>">
                            <i class="fa-solid fa-folder me-2"></i>
                            <?= esc($section['title']) ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Lectures List -->
    <?php if ($sectionId): ?>
        <div class="card mt-4">
            <div class="card-body">
                <?php if (empty($lectures)): ?>
                    <div class="text-center py-5">
                        <i class="fa-solid fa-video fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No lectures yet. Create your first lecture to add course content.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Preview</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lectures as $lecture): ?>
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
                                            <a href="<?= base_url('admin/lectures/' . $course['id'] . '/' . $sectionId . '/delete/' . $lecture['id']) ?>" 
                                               class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
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
    <?php endif; ?>
</div>

<!-- Create Lecture Modal -->
<?php if ($sectionId): ?>
<div class="modal fade" id="lectureModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Lecture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= base_url('admin/lectures/' . $course['id'] . '/' . $sectionId . '/store') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Lecture Title *</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="content_type" class="form-label">Content Type *</label>
                        <select class="form-select" id="content_type" name="content_type" required onchange="toggleContentFields()">
                            <option value="video">Video</option>
                            <option value="article">Article</option>
                            <option value="quiz">Quiz</option>
                            <option value="assignment">Assignment</option>
                            <option value="live">Live Session</option>
                        </select>
                    </div>
                    <div class="mb-3" id="videoFields">
                        <label for="video" class="form-label">Upload Video *</label>
                        <input type="file" class="form-control" id="video" name="video" accept="video/*">
                        <small class="text-muted">MP4, WebM, MOV, AVI - Max 500MB</small>
                        <div id="videoPreview" class="mt-2" style="display: none;">
                            <video id="videoPreviewPlayer" controls style="max-width: 300px; max-height: 200px; border-radius: 8px;" src=""></video>
                        </div>
                    </div>
                    <div class="mb-3" id="videoUrlFields">
                        <label for="video_url" class="form-label">Or Video URL (Alternative)</label>
                        <input type="url" class="form-control" id="video_url" name="video_url" placeholder="https://example.com/video.mp4 or YouTube/Vimeo URL">
                    </div>
                    <div class="mb-3" id="articleFields" style="display: none;">
                        <label for="article_content" class="form-label">Article Content</label>
                        <textarea class="form-control" id="article_content" name="article_content" rows="10"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="video_duration" class="form-label">Video Duration (seconds)</label>
                            <input type="number" class="form-control" id="video_duration" name="video_duration" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="order_index" class="form-label">Order</label>
                            <input type="number" class="form-control" id="order_index" name="order_index" value="<?= count($lectures ?? []) + 1 ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_preview" name="is_preview" value="1">
                                <label class="form-check-label" for="is_preview">
                                    <i class="fa-solid fa-eye text-success me-1"></i>Preview (Free to watch)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" checked>
                                <label class="form-check-label" for="is_published">Published</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Lecture</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Edit Lecture Modals -->
<?php if ($sectionId && !empty($lectures)): ?>
    <?php foreach ($lectures as $lecture): ?>
    <div class="modal fade" id="editLectureModal<?= $lecture['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Lecture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= base_url('admin/lectures/' . $course['id'] . '/' . $sectionId . '/update/' . $lecture['id']) ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_lecture_title_<?= $lecture['id'] ?>" class="form-label">Lecture Title *</label>
                            <input type="text" class="form-control" id="edit_lecture_title_<?= $lecture['id'] ?>" name="title" value="<?= esc($lecture['title']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_lecture_content_type_<?= $lecture['id'] ?>" class="form-label">Content Type *</label>
                            <select class="form-select" id="edit_lecture_content_type_<?= $lecture['id'] ?>" name="content_type" required onchange="toggleEditContentFields(<?= $lecture['id'] ?>)">
                                <option value="video" <?= $lecture['content_type'] === 'video' ? 'selected' : '' ?>>Video</option>
                                <option value="article" <?= $lecture['content_type'] === 'article' ? 'selected' : '' ?>>Article</option>
                                <option value="quiz" <?= $lecture['content_type'] === 'quiz' ? 'selected' : '' ?>>Quiz</option>
                                <option value="assignment" <?= $lecture['content_type'] === 'assignment' ? 'selected' : '' ?>>Assignment</option>
                                <option value="live" <?= $lecture['content_type'] === 'live' ? 'selected' : '' ?>>Live Session</option>
                            </select>
                        </div>
                        <div class="mb-3" id="edit_videoFields_<?= $lecture['id'] ?>" style="<?= $lecture['content_type'] !== 'video' ? 'display: none;' : '' ?>">
                            <label for="edit_video_<?= $lecture['id'] ?>" class="form-label">Upload New Video (Leave empty to keep current)</label>
                            <input type="file" class="form-control" id="edit_video_<?= $lecture['id'] ?>" name="video" accept="video/*">
                            <small class="text-muted">MP4, WebM, MOV, AVI - Max 500MB</small>
                            <?php if (!empty($lecture['video_url'])): ?>
                                <div class="mt-2">
                                    <small class="text-muted">Current: </small>
                                    <a href="<?= esc($lecture['video_url']) ?>" target="_blank" class="text-primary"><?= esc(substr($lecture['video_url'], 0, 50)) ?>...</a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3" id="edit_videoUrlFields_<?= $lecture['id'] ?>" style="<?= $lecture['content_type'] !== 'video' ? 'display: none;' : '' ?>">
                            <label for="edit_video_url_<?= $lecture['id'] ?>" class="form-label">Or Video URL (Alternative)</label>
                            <input type="url" class="form-control" id="edit_video_url_<?= $lecture['id'] ?>" name="video_url" value="<?= esc($lecture['video_url'] ?? '') ?>" placeholder="https://example.com/video.mp4 or YouTube/Vimeo URL">
                        </div>
                        <div class="mb-3" id="edit_articleFields_<?= $lecture['id'] ?>" style="<?= $lecture['content_type'] !== 'article' ? 'display: none;' : '' ?>">
                            <label for="edit_article_content_<?= $lecture['id'] ?>" class="form-label">Article Content</label>
                            <textarea class="form-control" id="edit_article_content_<?= $lecture['id'] ?>" name="article_content" rows="10"><?= esc($lecture['article_content'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description_<?= $lecture['id'] ?>" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description_<?= $lecture['id'] ?>" name="description" rows="3"><?= esc($lecture['description'] ?? '') ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_video_duration_<?= $lecture['id'] ?>" class="form-label">Video Duration (seconds)</label>
                                <input type="number" class="form-control" id="edit_video_duration_<?= $lecture['id'] ?>" name="video_duration" value="<?= esc($lecture['video_duration'] ?? '') ?>" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_order_index_<?= $lecture['id'] ?>" class="form-label">Order</label>
                                <input type="number" class="form-control" id="edit_order_index_<?= $lecture['id'] ?>" name="order_index" value="<?= esc($lecture['order_index']) ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="edit_is_preview_<?= $lecture['id'] ?>" name="is_preview" value="1" <?= $lecture['is_preview'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="edit_is_preview_<?= $lecture['id'] ?>">
                                        <i class="fa-solid fa-eye text-success me-1"></i>Preview (Free to watch)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="edit_is_published_<?= $lecture['id'] ?>" name="is_published" value="1" <?= $lecture['is_published'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="edit_is_published_<?= $lecture['id'] ?>">Published</label>
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
<?php endif; ?>

<script>
function toggleContentFields() {
    const contentType = document.getElementById('content_type').value;
    const videoFields = document.getElementById('videoFields');
    const videoUrlFields = document.getElementById('videoUrlFields');
    const articleFields = document.getElementById('articleFields');
    
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

function toggleEditContentFields(lectureId) {
    const contentType = document.getElementById('edit_lecture_content_type_' + lectureId).value;
    const videoFields = document.getElementById('edit_videoFields_' + lectureId);
    const videoUrlFields = document.getElementById('edit_videoUrlFields_' + lectureId);
    const articleFields = document.getElementById('edit_articleFields_' + lectureId);
    
    if (contentType === 'video') {
        if (videoFields) videoFields.style.display = 'block';
        if (videoUrlFields) videoUrlFields.style.display = 'block';
        if (articleFields) articleFields.style.display = 'none';
    } else if (contentType === 'article') {
        if (videoFields) videoFields.style.display = 'none';
        if (videoUrlFields) videoUrlFields.style.display = 'none';
        if (articleFields) articleFields.style.display = 'block';
    } else {
        if (videoFields) videoFields.style.display = 'none';
        if (videoUrlFields) videoUrlFields.style.display = 'none';
        if (articleFields) articleFields.style.display = 'none';
    }
}

// Preview video
document.getElementById('video')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const url = URL.createObjectURL(file);
        document.getElementById('videoPreviewPlayer').src = url;
        document.getElementById('videoPreview').style.display = 'block';
    }
});

toggleContentFields();
</script>

<?= $this->endSection() ?>

