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

    <!-- Filters -->
    <div class="card mt-4">
        <div class="card-body">
            <form method="GET" action="<?= base_url('admin/orders') ?>" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search orders..." value="<?= esc($search ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" <?= ($status ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="completed" <?= ($status ?? '') == 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="failed" <?= ($status ?? '') == 'failed' ? 'selected' : '' ?>>Failed</option>
                        <option value="refunded" <?= ($status ?? '') == 'refunded' ? 'selected' : '' ?>>Refunded</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="<?= base_url('admin/orders') ?>" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card mt-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">No orders found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><strong><?= esc($order['order_number']) ?></strong></td>
                                    <td>
                                        <?= esc(($order['first_name'] ?? '') . ' ' . ($order['last_name'] ?? '')) ?><br>
                                        <small class="text-muted"><?= esc($order['email'] ?? '') ?></small>
                                    </td>
                                    <td>
                                        <strong>$<?= number_format($order['final_amount'], 2) ?></strong>
                                        <?php if ($order['discount_amount'] > 0): ?>
                                            <br><small class="text-muted">Discount: $<?= number_format($order['discount_amount'], 2) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($order['payment_method'] ?? 'N/A') ?></td>
                                    <td>
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
                                    </td>
                                    <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/orders/view/' . $order['id']) ?>" class="btn btn-sm btn-info" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
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

