<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-column flex-md-row flex-lg-row mt-3">
                <div class="flex-grow-1">
                    <h3 class="mb-2 text-color-2"><?= $title ?></h3>
                    <?php if ($unreadCount > 0): ?>
                        <span class="badge bg-danger"><?= $unreadCount ?> unread</span>
                    <?php endif; ?>
                </div>
                <div class="mt-3 mt-md-0">
                    <?php if ($unreadCount > 0): ?>
                        <a href="<?= base_url('admin/notifications/mark-all-read') ?>" class="btn btn-primary">
                            <i class="fas fa-check-double me-2"></i>Mark All as Read
                        </a>
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

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <strong>Error!</strong> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="card mt-4">
        <div class="card-body">
            <form method="GET" action="<?= base_url('admin/notifications') ?>" class="row g-3">
                <div class="col-md-4">
                    <select name="filter" class="form-select">
                        <option value="all" <?= $filter == 'all' ? 'selected' : '' ?>>All Notifications</option>
                        <option value="unread" <?= $filter == 'unread' ? 'selected' : '' ?>>Unread</option>
                        <option value="read" <?= $filter == 'read' ? 'selected' : '' ?>>Read</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="type" class="form-select">
                        <option value="all" <?= $type == 'all' ? 'selected' : '' ?>>All Types</option>
                        <option value="payment" <?= $type == 'payment' ? 'selected' : '' ?>>Payment</option>
                        <option value="course" <?= $type == 'course' ? 'selected' : '' ?>>Course</option>
                        <option value="system" <?= $type == 'system' ? 'selected' : '' ?>>System</option>
                        <option value="announcement" <?= $type == 'announcement' ? 'selected' : '' ?>>Announcement</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="<?= base_url('admin/notifications') ?>" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="card mt-4">
        <div class="card-body">
            <?php if (empty($notifications)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <h5>No notifications found</h5>
                    <p class="text-muted">You're all caught up!</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">Status</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notifications as $notification): ?>
                                <tr class="<?= !$notification['is_read'] ? 'table-warning' : '' ?>">
                                    <td>
                                        <?php if (!$notification['is_read']): ?>
                                            <span class="badge bg-danger">New</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Read</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= esc($notification['title']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="text-muted"><?= esc(substr($notification['message'], 0, 100)) ?><?= strlen($notification['message']) > 100 ? '...' : '' ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?= esc(ucfirst($notification['type'] ?? 'system')) ?></span>
                                    </td>
                                    <td>
                                        <?= date('M d, Y H:i', strtotime($notification['sent_at'] ?? $notification['created_at'] ?? 'now')) ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= base_url('admin/notifications/view/' . $notification['id']) ?>" class="btn btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if (!$notification['is_read']): ?>
                                                <a href="<?= base_url('admin/notifications/mark-read/' . $notification['id']) ?>" class="btn btn-success" title="Mark as Read">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?= base_url('admin/notifications/delete/' . $notification['id']) ?>" class="btn btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this notification?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
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

