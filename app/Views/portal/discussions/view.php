<?= $this->extend('layouts/portal') ?>
<?= $this->section('content') ?>

<style>
    .discussion-view-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    
    .discussion-post {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #fff;
    }
    
    .reply-card {
        border-left: 3px solid #0d6efd;
        padding: 1rem;
        margin-bottom: 1rem;
        background: #f8f9fa;
        border-radius: 4px;
    }
</style>

<div class="discussion-view-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-2"><?= esc($discussion['title']) ?></h1>
            <p class="text-muted">
                <a href="<?= base_url('portal/discussions/' . $course['id']) ?>" class="text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i>Back to Discussions
                </a>
                | 
                <a href="<?= base_url('portal/learn/' . $course['id']) ?>" class="text-decoration-none">
                    Back to Course
                </a>
            </p>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Main Discussion Post -->
    <div class="discussion-post">
        <div class="d-flex align-items-start mb-3">
            <?php if (!empty($discussion['profile_picture'])): ?>
                <img src="<?= esc($discussion['profile_picture']) ?>" alt="Avatar" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
            <?php else: ?>
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                    <?= strtoupper(substr($discussion['first_name'] ?? 'U', 0, 1)) ?>
                </div>
            <?php endif; ?>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="mb-1">
                            <?= esc(($discussion['first_name'] ?? '') . ' ' . ($discussion['last_name'] ?? '')) ?>
                            <?php if ($discussion['role'] === 'instructor'): ?>
                                <span class="badge bg-info ms-2">Instructor</span>
                            <?php endif; ?>
                        </h5>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i><?= date('F d, Y H:i', strtotime($discussion['created_at'])) ?>
                        </small>
                    </div>
                    <div>
                        <?php if ($discussion['is_pinned']): ?>
                            <span class="badge bg-warning me-1"><i class="fas fa-thumbtack"></i> Pinned</span>
                        <?php endif; ?>
                        <?php if ($discussion['is_resolved']): ?>
                            <span class="badge bg-success me-1"><i class="fas fa-check-circle"></i> Resolved</span>
                        <?php endif; ?>
                        <?php if ($discussion['is_question']): ?>
                            <span class="badge bg-info"><i class="fas fa-question-circle"></i> Question</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="discussion-content">
                    <?= nl2br(esc($discussion['content'])) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Replies Section -->
    <div class="mb-4">
        <h4 class="mb-3">
            Replies 
            <span class="badge bg-secondary"><?= count($replies) ?></span>
        </h4>

        <?php if (empty($replies)): ?>
            <div class="text-center py-4 bg-light rounded">
                <i class="fas fa-comments fa-2x text-muted mb-2"></i>
                <p class="text-muted mb-0">No replies yet. Be the first to reply!</p>
            </div>
        <?php else: ?>
            <?php foreach ($replies as $reply): ?>
                <div class="reply-card">
                    <div class="d-flex align-items-start">
                        <?php if (!empty($reply['profile_picture'])): ?>
                            <img src="<?= esc($reply['profile_picture']) ?>" alt="Avatar" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-size: 0.875rem;">
                                <?= strtoupper(substr($reply['first_name'] ?? 'U', 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong><?= esc(($reply['first_name'] ?? '') . ' ' . ($reply['last_name'] ?? '')) ?></strong>
                                    <?php if ($reply['role'] === 'instructor'): ?>
                                        <span class="badge bg-info ms-2">Instructor</span>
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i><?= date('M d, Y H:i', strtotime($reply['created_at'])) ?>
                                </small>
                            </div>
                            <div class="reply-content">
                                <?= nl2br(esc($reply['content'] ?? '')) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Reply Form -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Post a Reply</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= base_url('portal/discussions/' . $course['id'] . '/reply/' . $discussion['id']) ?>">
                <input type="hidden" name="discussion_title" value="<?= esc($discussion['title'], 'attr') ?>">
                <div class="mb-3">
                    <label for="content" class="form-label">Your Reply</label>
                    <textarea class="form-control" id="content" name="content" rows="4" required placeholder="Write your reply here..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i>Post Reply
                </button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

