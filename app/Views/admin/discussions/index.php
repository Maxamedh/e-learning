<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-column flex-md-row flex-lg-row mt-3">
                <div class="flex-grow-1">
                    <h3 class="mb-2 text-color-2"><?= $title ?></h3>
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

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <strong>Error!</strong> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="card mt-4">
        <div class="card-body">
            <form method="GET" action="<?= base_url('admin/discussions') ?>" class="row g-3">
                <div class="col-md-4">
                    <select name="course_id" class="form-select">
                        <option value="">All Courses</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['id'] ?>" <?= ($courseId ?? '') == $course['id'] ? 'selected' : '' ?>>
                                <?= esc($course['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="post_type" class="form-select">
                        <option value="all" <?= $postType == 'all' ? 'selected' : '' ?>>All Types</option>
                        <option value="question" <?= $postType == 'question' ? 'selected' : '' ?>>Questions</option>
                        <option value="discussion" <?= $postType == 'discussion' ? 'selected' : '' ?>>Discussions</option>
                        <option value="announcement" <?= $postType == 'announcement' ? 'selected' : '' ?>>Announcements</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="<?= esc($search ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Discussions Table -->
    <div class="card mt-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="50">Status</th>
                            <th>Title</th>
                            <th>Course</th>
                            <th>Author</th>
                            <th>Type</th>
                            <th>Replies</th>
                            <th>Views</th>
                            <th>Date</th>
                            <th class="text-center" width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($discussions)): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">No discussions found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($discussions as $discussion): ?>
                                <tr>
                                    <td>
                                        <?php if ($discussion['is_pinned']): ?>
                                            <span class="badge bg-warning" title="Pinned"><i class="fas fa-thumbtack"></i></span>
                                        <?php endif; ?>
                                        <?php if ($discussion['is_resolved']): ?>
                                            <span class="badge bg-success" title="Resolved"><i class="fas fa-check-circle"></i></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= esc($discussion['title']) ?></strong>
                                    </td>
                                    <td><?= esc($discussion['course_title'] ?? 'N/A') ?></td>
                                    <td>
                                        <?= esc(($discussion['first_name'] ?? '') . ' ' . ($discussion['last_name'] ?? '')) ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?= $discussion['is_question'] ? 'Question' : 'Discussion' ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        // Get reply count - check if discussion_replies table exists
                                        try {
                                            $db = \Config\Database::connect();
                                            $tables = $db->listTables();
                                            if (in_array('discussion_replies', $tables)) {
                                                $replyModel = new \App\Models\DiscussionReplyModel();
                                                $replyCount = $replyModel->where('discussion_id', $discussion['id'])->countAllResults();
                                            } else {
                                                // Use discussions table with parent_id
                                                $discussionModel = new \App\Models\DiscussionModel();
                                                $replyCount = $discussionModel->where('parent_id', $discussion['id'])->countAllResults();
                                            }
                                            echo $replyCount;
                                        } catch (\Exception $e) {
                                            // If error, use discussions table
                                            $discussionModel = new \App\Models\DiscussionModel();
                                            $replyCount = $discussionModel->where('parent_id', $discussion['id'])->countAllResults();
                                            echo $replyCount;
                                        }
                                        ?>
                                    </td>
                                    <td>-</td>
                                    <td><?= date('M d, Y', strtotime($discussion['created_at'])) ?></td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= base_url('admin/discussions/view/' . $discussion['id']) ?>" class="btn btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('admin/discussions/toggle-pin/' . $discussion['id']) ?>" class="btn btn-warning" title="<?= $discussion['is_pinned'] ? 'Unpin' : 'Pin' ?>">
                                                <i class="fas fa-thumbtack"></i>
                                            </a>
                                            <a href="<?= base_url('admin/discussions/toggle-resolve/' . $discussion['id']) ?>" class="btn btn-success" title="<?= $discussion['is_resolved'] ? 'Unresolve' : 'Resolve' ?>">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                            <a href="<?= base_url('admin/discussions/delete/' . $discussion['id']) ?>" class="btn btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this discussion?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

