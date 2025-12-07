<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-column flex-md-row flex-lg-row mt-3">
                <div class="flex-grow-1">
                    <h3 class="mb-2 text-color-2"><?= esc($discussion['title'] ?? 'Discussion') ?></h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('instructor/discussions') ?>">Discussions</a></li>
                            <li class="breadcrumb-item active"><?= esc($discussion['title'] ?? 'View') ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5><?= esc($discussion['title'] ?? 'No Title') ?></h5>
                            <small class="text-muted">
                                By <?= esc($discussion['first_name'] . ' ' . $discussion['last_name']) ?> • 
                                <?= date('M d, Y H:i', strtotime($discussion['created_at'])) ?>
                            </small>
                        </div>
                        <div>
                            <?php if ($discussion['is_pinned']): ?>
                                <span class="badge bg-warning">Pinned</span>
                            <?php endif; ?>
                            <?php if ($discussion['is_resolved']): ?>
                                <span class="badge bg-success">Resolved</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p><?= nl2br(esc($discussion['content'])) ?></p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Replies (<?= count($replies) ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($replies)): ?>
                        <p class="text-muted">No replies yet</p>
                    <?php else: ?>
                        <?php foreach ($replies as $reply): ?>
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <strong><?= esc($reply['first_name'] . ' ' . $reply['last_name']) ?></strong>
                                        <?php if (isset($reply['role']) && $reply['role'] === 'instructor'): ?>
                                            <span class="badge bg-primary ms-2">Instructor</span>
                                        <?php endif; ?>
                                        <br>
                                        <small class="text-muted"><?= date('M d, Y H:i', strtotime($reply['created_at'])) ?></small>
                                        <p class="mt-2 mb-0"><?= nl2br(esc($reply['content'] ?? '')) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Course Information</h5>
                    <p><strong>Course:</strong> <?= esc($discussion['course_title']) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

