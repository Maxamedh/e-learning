<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-column flex-md-row flex-lg-row mt-3">
                <div class="flex-grow-1">
                    <h3 class="mb-2 text-color-2"><?= $title ?></h3>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="<?= base_url('admin/discussions') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Discussions
                    </a>
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

    <!-- Discussion Details -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Discussion Details</h5>
            <div>
                <a href="<?= base_url('admin/discussions/toggle-pin/' . $discussion['id']) ?>" class="btn btn-sm btn-warning">
                    <i class="fas fa-thumbtack me-2"></i><?= $discussion['is_pinned'] ? 'Unpin' : 'Pin' ?>
                </a>
                <a href="<?= base_url('admin/discussions/toggle-resolve/' . $discussion['id']) ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-check-circle me-2"></i><?= $discussion['is_resolved'] ? 'Unresolve' : 'Resolve' ?>
                </a>
                <a href="<?= base_url('admin/discussions/delete/' . $discussion['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this discussion?')">
                    <i class="fas fa-trash me-2"></i>Delete
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-2"><strong>Title:</strong></div>
                <div class="col-md-10"><?= esc($discussion['title']) ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2"><strong>Course:</strong></div>
                <div class="col-md-10">
                    <a href="<?= base_url('admin/courses/view/' . $discussion['course_id']) ?>"><?= esc($discussion['course_title']) ?></a>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2"><strong>Author:</strong></div>
                <div class="col-md-10">
                    <?= esc(($discussion['first_name'] ?? '') . ' ' . ($discussion['last_name'] ?? '')) ?>
                    <span class="badge bg-secondary ms-2"><?= esc(ucfirst($discussion['role'] ?? 'student')) ?></span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2"><strong>Type:</strong></div>
                <div class="col-md-10">
                    <span class="badge bg-info"><?= $discussion['is_question'] ? 'Question' : 'Discussion' ?></span>
                    <?php if ($discussion['is_pinned']): ?>
                        <span class="badge bg-warning ms-2">Pinned</span>
                    <?php endif; ?>
                    <?php if ($discussion['is_resolved']): ?>
                        <span class="badge bg-success ms-2">Resolved</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2"><strong>Content:</strong></div>
                <div class="col-md-10">
                    <div class="border p-3 rounded bg-light">
                        <?= nl2br(esc($discussion['content'])) ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2"><strong>Created:</strong></div>
                <div class="col-md-10"><?= date('F d, Y H:i:s', strtotime($discussion['created_at'])) ?></div>
            </div>
            <?php if ($discussion['updated_at'] && $discussion['updated_at'] !== $discussion['created_at']): ?>
                <div class="row mb-3">
                    <div class="col-md-2"><strong>Updated:</strong></div>
                    <div class="col-md-10"><?= date('F d, Y H:i:s', strtotime($discussion['updated_at'])) ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Replies Section -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Replies (<?= count($replies) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (empty($replies)): ?>
                <p class="text-muted">No replies yet.</p>
            <?php else: ?>
                <?php foreach ($replies as $reply): ?>
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong><?= esc(($reply['first_name'] ?? '') . ' ' . ($reply['last_name'] ?? '')) ?></strong>
                                <span class="badge bg-secondary ms-2"><?= esc(ucfirst($reply['role'] ?? 'student')) ?></span>
                            </div>
                            <div>
                                <small class="text-muted"><?= date('M d, Y H:i', strtotime($reply['created_at'])) ?></small>
                                <a href="<?= base_url('admin/discussions/delete-reply/' . ($reply['reply_id'] ?? $reply['id'])) ?>" class="btn btn-sm btn-danger ms-2" onclick="return confirm('Are you sure you want to delete this reply?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ps-3">
                            <?= nl2br(esc($reply['content'] ?? '')) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

