<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-column flex-md-row flex-lg-row mt-3">
                <div class="flex-grow-1">
                    <h3 class="mb-2 text-color-2"><?= $title ?></h3>
                </div>
                <div class="mt-3 mt-lg-0">
                    <a href="<?= base_url('admin/orders') ?>" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back to Orders
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

    <div class="row mt-4">
        <!-- Order Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($order['items'])): ?>
                        <p class="text-muted">No items in this order.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>Final Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order['items'] as $item): ?>
                                        <tr>
                                            <td>
                                                <strong><?= esc($item['course_title'] ?? 'N/A') ?></strong>
                                                <?php if (!empty($item['thumbnail_url'])): ?>
                                                    <br><img src="<?= esc($item['thumbnail_url']) ?>" alt="<?= esc($item['course_title']) ?>" 
                                                             style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px; margin-top: 5px;">
                                                <?php endif; ?>
                                            </td>
                                            <td>$<?= number_format($item['unit_price'], 2) ?></td>
                                            <td>$<?= number_format($item['discount_price'] ?? 0, 2) ?></td>
                                            <td><strong>$<?= number_format($item['final_price'], 2) ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Order Number:</strong><br>
                        <?= esc($order['order_number']) ?>
                    </div>
                    <div class="mb-3">
                        <strong>Date:</strong><br>
                        <?= date('F d, Y H:i', strtotime($order['created_at'])) ?>
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong><br>
                        <?php
                        $statusColors = [
                            'completed' => 'success',
                            'pending' => 'warning',
                            'failed' => 'danger',
                            'refunded' => 'info'
                        ];
                        $statusColor = $statusColors[$order['status']] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?= $statusColor ?>"><?= ucfirst($order['status']) ?></span>
                    </div>
                    <hr>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <span>$<?= number_format($order['total_amount'], 2) ?></span>
                    </div>
                    <?php if ($order['discount_amount'] > 0): ?>
                        <div class="mb-2 d-flex justify-content-between text-danger">
                            <span>Discount:</span>
                            <span>-$<?= number_format($order['discount_amount'], 2) ?></span>
                        </div>
                    <?php endif; ?>
                    <hr>
                    <div class="mb-3 d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong>$<?= number_format($order['final_amount'], 2) ?></strong>
                    </div>
                    <div class="mb-3">
                        <strong>Payment Method:</strong><br>
                        <?= esc($order['payment_method'] ?? 'N/A') ?>
                    </div>
                    <?php if ($order['payment_id']): ?>
                        <div class="mb-3">
                            <strong>Payment ID:</strong><br>
                            <small><?= esc($order['payment_id']) ?></small>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Update Status Form -->
                    <form method="POST" action="<?= base_url('admin/orders/update-status/' . $order['id']) ?>" class="mt-4">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="status" class="form-label">Update Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending - Awaiting Approval</option>
                                <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed - Approve Payment</option>
                                <option value="failed" <?= $order['status'] == 'failed' ? 'selected' : '' ?>>Failed - Reject Payment</option>
                                <option value="refunded" <?= $order['status'] == 'refunded' ? 'selected' : '' ?>>Refunded</option>
                            </select>
                            <small class="text-muted d-block mt-1">
                                <?php if ($order['status'] == 'pending'): ?>
                                    <i class="fas fa-info-circle"></i> When you approve (set to Completed), the student will be able to access the course.
                                <?php elseif ($order['status'] == 'completed'): ?>
                                    <i class="fas fa-check-circle text-success"></i> Payment approved. Student has access to the course.
                                <?php endif; ?>
                            </small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa-solid fa-save me-2"></i>Update Status
                        </button>
                        <?php if ($order['status'] == 'pending'): ?>
                            <button type="button" class="btn btn-success w-100 mt-2" onclick="document.getElementById('status').value='completed'; document.getElementById('status').form.submit();">
                                <i class="fa-solid fa-check me-2"></i>Approve Payment
                            </button>
                            <button type="button" class="btn btn-danger w-100 mt-2" onclick="document.getElementById('status').value='failed'; document.getElementById('status').form.submit();">
                                <i class="fa-solid fa-times me-2"></i>Reject Payment
                            </button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

