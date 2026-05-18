<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section bg-light" style="min-height: 80vh;">
    <div class="container">
        <div style="display: flex; gap: 30px;">
            <?php $current = 'services'; include 'sidebar.php'; ?>

            <div style="flex-grow: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2><?= isset($data['service']) ? 'Edit Service' : 'Add New Service'; ?></h2>
                    <a href="<?= URLROOT; ?>/index.php?url=admin/services" class="btn btn-secondary">Back to Services</a>
                </div>

                <div class="card">
                    <form action="<?= URLROOT; ?>/index.php?url=admin/<?= isset($data['service']) ? 'editService/'.$data['service']->id : 'addService'; ?>" method="post">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken(); ?>">

                        <div class="form-group">
                            <label>Service Title</label>
                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($data['service']->title ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" class="form-control" required>
                                <?php $cats = ['Web Development', 'App Development', 'Digital Marketing', 'Hosting', 'Other']; ?>
                                <?php foreach($cats as $cat): ?>
                                    <option value="<?= $cat; ?>" <?= (isset($data['service']) && $data['service']->category == $cat) ? 'selected' : ''; ?>><?= $cat; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label>Price (INR)</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($data['service']->price ?? '0.00'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Billing Cycle</label>
                                <select name="billing_cycle" class="form-control" required>
                                    <option value="one-time" <?= (isset($data['service']) && $data['service']->billing_cycle == 'one-time') ? 'selected' : ''; ?>>One-time</option>
                                    <option value="monthly" <?= (isset($data['service']) && $data['service']->billing_cycle == 'monthly') ? 'selected' : ''; ?>>Monthly</option>
                                    <option value="yearly" <?= (isset($data['service']) && $data['service']->billing_cycle == 'yearly') ? 'selected' : ''; ?>>Yearly</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($data['service']->description ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Features (Comma separated)</label>
                            <textarea name="features" class="form-control" rows="2"><?= htmlspecialchars($data['service']->features ?? ''); ?></textarea>
                            <small style="color: var(--text-muted); display: block; margin-top: 5px;">e.g. Responsive Design, SEO Optimized, Fast Loading</small>
                        </div>

                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="<?= htmlspecialchars($data['service']->sort_order ?? '0'); ?>">
                        </div>

                        <button type="submit" class="btn btn-primary"><?= isset($data['service']) ? 'Update Service' : 'Create Service'; ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
