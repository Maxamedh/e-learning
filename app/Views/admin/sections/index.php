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
                            <li class="breadcrumb-item active"><?= esc($course['title']) ?></li>
                        </ol>
                    </nav>
                </div>
                <div class="mt-3 mt-lg-0">
                    <a href="<?= base_url('admin/courses') ?>" class="btn btn-secondary me-2">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back to Courses
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sectionModal">
                        <i class="fa-solid fa-plus me-2"></i>Add Section
                    </button>
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

    <!-- Sections List -->
    <div class="card mt-4">
        <div class="card-body">
            <?php if (empty($sections)): ?>
                <div class="text-center py-5">
                    <i class="fa-solid fa-folder-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No sections yet. Create your first section to start adding course content.</p>
                </div>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($sections as $section): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">
                                        <i class="fa-solid fa-folder me-2"></i>
                                        <?= esc($section['title']) ?>
                                        <?php if (!$section['is_published']): ?>
                                            <span class="badge bg-secondary ms-2">Draft</span>
                                        <?php endif; ?>
                                    </h5>
                                    <?php if ($section['description']): ?>
                                        <p class="mb-1 text-muted"><?= esc($section['description']) ?></p>
                                    <?php endif; ?>
                                    <small class="text-muted">Order: <?= $section['order_index'] ?></small>
                                </div>
                                <div>
                                    <a href="<?= base_url('admin/lectures/' . $course['id'] . '/' . $section['id']) ?>" class="btn btn-sm btn-primary me-1">
                                        <i class="fa-solid fa-video me-1"></i>Manage Lectures
                                    </a>
                                    <button type="button" class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#editSectionModal<?= $section['id'] ?>">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <a href="<?= base_url('admin/sections/' . $course['id'] . '/delete/' . $section['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? This will delete all lectures in this section!')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
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
                        <label for="title_<?= $section['id'] ?>" class="form-label">Section Title *</label>
                        <input type="text" class="form-control" id="title_<?= $section['id'] ?>" name="title" value="<?= esc($section['title']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description_<?= $section['id'] ?>" class="form-label">Description</label>
                        <textarea class="form-control" id="description_<?= $section['id'] ?>" name="description" rows="3"><?= esc($section['description'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="order_index_<?= $section['id'] ?>" class="form-label">Order</label>
                        <input type="number" class="form-control" id="order_index_<?= $section['id'] ?>" name="order_index" value="<?= $section['order_index'] ?>">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_published_<?= $section['id'] ?>" name="is_published" value="1" <?= $section['is_published'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_published_<?= $section['id'] ?>">Published</label>
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

<?= $this->endSection() ?>

