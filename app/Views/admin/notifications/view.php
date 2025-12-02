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
                    <a href="<?= base_url('admin/notifications') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Notifications
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

    <!-- Notification Details -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Notification Details</h5>
            <div>
                <?php if (!$notification['is_read']): ?>
                    <a href="<?= base_url('admin/notifications/mark-read/' . $notification['id']) ?>" class="btn btn-sm btn-success">
                        <i class="fas fa-check me-2"></i>Mark as Read
                    </a>
                <?php endif; ?>
                <a href="<?= base_url('admin/notifications/delete/' . $notification['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this notification?')">
                    <i class="fas fa-trash me-2"></i>Delete
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-2"><strong>Status:</strong></div>
                <div class="col-md-10">
                    <?php if (!$notification['is_read']): ?>
                        <span class="badge bg-danger">Unread</span>
                    <?php else: ?>
                        <span class="badge bg-success">Read</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2"><strong>Title:</strong></div>
                <div class="col-md-10"><?= esc($notification['title']) ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2"><strong>Message:</strong></div>
                <div class="col-md-10"><?= nl2br(esc($notification['message'])) ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2"><strong>Type:</strong></div>
                <div class="col-md-10">
                    <span class="badge bg-info"><?= esc(ucfirst($notification['type'] ?? 'system')) ?></span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2"><strong>Date:</strong></div>
                <div class="col-md-10">
                    <?= date('F d, Y H:i:s', strtotime($notification['sent_at'] ?? $notification['created_at'] ?? 'now')) ?>
                </div>
            </div>
            <?php if ($notification['related_entity_type'] && $notification['related_entity_id']): ?>
                <div class="row mb-3">
                    <div class="col-md-2"><strong>Related:</strong></div>
                    <div class="col-md-10">
                        <?php if ($notification['related_entity_type'] === 'order'): ?>
                            <a href="<?= base_url('admin/orders/view/' . $notification['related_entity_id']) ?>" class="btn btn-sm btn-primary">
                                View Order #<?= $notification['related_entity_id'] ?>
                            </a>
                        <?php else: ?>
                            <?= esc(ucfirst($notification['related_entity_type'])) ?> #<?= $notification['related_entity_id'] ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Related Order Details -->
    <?php if (!empty($relatedData) && $notification['related_entity_type'] === 'order'): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Related Order Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-3"><strong>Order Number:</strong></div>
                    <div class="col-md-9"><?= esc($relatedData['order_number'] ?? 'N/A') ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-3"><strong>Status:</strong></div>
                    <div class="col-md-9">
                        <span class="badge bg-<?= $relatedData['status'] === 'completed' ? 'success' : ($relatedData['status'] === 'pending' ? 'warning' : 'danger') ?>">
                            <?= esc(ucfirst($relatedData['status'] ?? 'N/A')) ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-3"><strong>Amount:</strong></div>
                    <div class="col-md-9">$<?= number_format($relatedData['final_amount'] ?? 0, 2) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <a href="<?= base_url('admin/orders/view/' . $relatedData['id']) ?>" class="btn btn-primary">
                            View Full Order Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

