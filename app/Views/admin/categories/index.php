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
                    <a href="<?= base_url('admin/categories/seed') ?>" class="btn btn-info" onclick="return confirm('This will add sample categories. Continue?')">
                        <i class="fa-solid fa-seedling me-2"></i>Add Sample Categories
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

    <!-- Create Category Form -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Create New Category</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= base_url('admin/categories/store') ?>">
                <?= csrf_field() ?>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="name" class="form-label">Category Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="icon" class="form-label">
                            Icon (FontAwesome class)
                            <i class="fa-solid fa-info-circle text-info ms-1" data-bs-toggle="tooltip" title="FontAwesome icon class name. Examples: fa-book, fa-code, fa-music, fa-camera. Visit fontawesome.com/icons for all icons."></i>
                        </label>
                        <input type="text" class="form-control" id="icon" name="icon" placeholder="e.g., fa-book">
                        <small class="text-muted">
                            <strong>Examples:</strong> fa-book, fa-code, fa-music, fa-camera<br>
                            Visit <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com/icons</a> for all icons
                        </small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="parent_id" class="form-label">
                            Parent Category
                            <i class="fa-solid fa-info-circle text-info ms-1" data-bs-toggle="tooltip" title="Select a parent category to create a sub-category. Leave empty for top-level category."></i>
                        </label>
                        <select class="form-select" id="parent_id" name="parent_id">
                            <option value="">None (Top Level Category)</option>
                            <?php foreach ($categories as $cat): ?>
                                <?php if (empty($cat['parent_id'])): ?>
                                    <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">
                            <strong>Parent Category:</strong> Creates a sub-category under the selected parent.<br>
                            <strong>Example:</strong> "Frontend Development" can be a sub-category of "Web Development"
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-plus me-2"></i>Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card mt-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Icon</th>
                            <th>Parent</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">No categories found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><strong><?= esc($category['name']) ?></strong></td>
                                    <td>
                                        <?php if ($category['icon']): ?>
                                            <i class="<?= esc($category['icon']) ?>"></i>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($category['parent_name'] ?? 'Top Level') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $category['is_active'] ? 'success' : 'danger' ?>">
                                            <?= $category['is_active'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editCategoryModal<?= $category['id'] ?>">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <a href="<?= base_url('admin/categories/delete/' . $category['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                </tr>
                                
                                <!-- Edit Modal -->
                                <div class="modal fade" id="editCategoryModal<?= $category['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Category</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="<?= base_url('admin/categories/update/' . $category['id']) ?>">
                                                <?= csrf_field() ?>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="name_<?= $category['id'] ?>" class="form-label">Name *</label>
                                                        <input type="text" class="form-control" id="name_<?= $category['id'] ?>" name="name" value="<?= esc($category['name']) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="icon_<?= $category['id'] ?>" class="form-label">Icon</label>
                                                        <input type="text" class="form-control" id="icon_<?= $category['id'] ?>" name="icon" value="<?= esc($category['icon'] ?? '') ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="parent_id_<?= $category['id'] ?>" class="form-label">Parent</label>
                                                        <select class="form-select" id="parent_id_<?= $category['id'] ?>" name="parent_id">
                                                            <option value="">None</option>
                                                            <?php foreach ($categories as $cat): ?>
                                                                <?php if (empty($cat['parent_id']) && $cat['id'] != $category['id']): ?>
                                                                    <option value="<?= $cat['id'] ?>" <?= ($category['parent_id'] == $cat['id']) ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description_<?= $category['id'] ?>" class="form-label">Description</label>
                                                        <textarea class="form-control" id="description_<?= $category['id'] ?>" name="description" rows="2"><?= esc($category['description'] ?? '') ?></textarea>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="is_active_<?= $category['id'] ?>" name="is_active" value="1" <?= $category['is_active'] ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="is_active_<?= $category['id'] ?>">Active</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

