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

    <div class="card mt-4">
        <div class="card-body">
            <?php if (empty($discussions)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No discussions yet</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Discussion</th>
                                <th>Course</th>
                                <th>Author</th>
                                <th>Replies</th>
                                <th>Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($discussions as $discussion): ?>
                                <tr>
                                    <td>
                                        <?php if ($discussion['is_pinned']): ?>
                                            <i class="fas fa-thumbtack text-warning me-2"></i>
                                        <?php endif; ?>
                                        <strong><?= esc($discussion['title'] ?? 'No Title') ?></strong><br>
                                        <small class="text-muted"><?= esc(substr($discussion['content'] ?? '', 0, 100)) ?>...</small>
                                    </td>
                                    <td><?= esc($discussion['course_title']) ?></td>
                                    <td><?= esc($discussion['first_name'] . ' ' . $discussion['last_name']) ?></td>
                                    <td><?= $discussion['reply_count'] ?? 0 ?></td>
                                    <td><?= date('M d, Y', strtotime($discussion['created_at'])) ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('instructor/discussions/view/' . $discussion['id']) ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
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

<?= $this->endSection() ?>

