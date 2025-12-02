<?= $this->extend('layouts/portal') ?>
<?= $this->section('content') ?>

<style>
    .discussions-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    
    .discussion-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: box-shadow 0.2s;
        background: #fff;
    }
    
    .discussion-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .discussion-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }
    
    .discussion-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #6a6f73;
        font-size: 0.875rem;
    }
    
    .badge-pinned {
        background: #ffc107;
        color: #000;
    }
    
    .badge-resolved {
        background: #28a745;
        color: #fff;
    }
    
    .badge-question {
        background: #17a2b8;
        color: #fff;
    }
</style>

<div class="discussions-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-2">Course Discussions</h1>
            <p class="text-muted"><?= esc($course['title']) ?></p>
        </div>
        <a href="<?= base_url('portal/learn/' . $course['id']) ?>" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Course
        </a>
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

    <!-- Create Discussion Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Start a Discussion</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= base_url('portal/discussions/' . $course['id'] . '/create') ?>">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_question" name="is_question" value="1">
                    <label class="form-check-label" for="is_question">Mark as Question</label>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Discussion
                </button>
            </form>
        </div>
    </div>

    <!-- Discussions List -->
    <?php if (empty($discussions)): ?>
        <div class="text-center py-5">
            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
            <h4>No discussions yet</h4>
            <p class="text-muted">Be the first to start a discussion!</p>
        </div>
    <?php else: ?>
        <?php foreach ($discussions as $discussion): ?>
            <div class="discussion-card">
                <div class="discussion-header">
                    <div class="flex-grow-1">
                        <h4 class="mb-2">
                            <a href="<?= base_url('portal/discussions/' . $course['id'] . '/view/' . $discussion['id']) ?>" class="text-decoration-none">
                                <?= esc($discussion['title']) ?>
                            </a>
                        </h4>
                        <div class="mb-2">
                            <?php if ($discussion['is_pinned']): ?>
                                <span class="badge badge-pinned me-2"><i class="fas fa-thumbtack"></i> Pinned</span>
                            <?php endif; ?>
                            <?php if ($discussion['is_resolved']): ?>
                                <span class="badge badge-resolved me-2"><i class="fas fa-check-circle"></i> Resolved</span>
                            <?php endif; ?>
                            <?php if ($discussion['is_question']): ?>
                                <span class="badge badge-question me-2"><i class="fas fa-question-circle"></i> Question</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-muted mb-2"><?= esc(substr($discussion['content'], 0, 200)) ?><?= strlen($discussion['content']) > 200 ? '...' : '' ?></p>
                    </div>
                </div>
                <div class="discussion-meta">
                    <div class="d-flex align-items-center">
                        <?php if (!empty($discussion['profile_picture'])): ?>
                            <img src="<?= esc($discussion['profile_picture']) ?>" alt="Avatar" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                <?= strtoupper(substr($discussion['first_name'] ?? 'U', 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        <span>
                            <strong><?= esc(($discussion['first_name'] ?? '') . ' ' . ($discussion['last_name'] ?? '')) ?></strong>
                            <?php if ($discussion['role'] === 'instructor'): ?>
                                <span class="badge bg-info ms-1">Instructor</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div>
                        <i class="fas fa-comments me-1"></i><?= $discussion['reply_count'] ?? 0 ?> replies
                    </div>
                    <div>
                        <i class="fas fa-clock me-1"></i><?= date('M d, Y', strtotime($discussion['created_at'])) ?>
                    </div>
                    <a href="<?= base_url('portal/discussions/' . $course['id'] . '/view/' . $discussion['id']) ?>" class="btn btn-sm btn-primary">
                        View Discussion <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

