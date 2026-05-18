<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section bg-light" style="min-height: 80vh;">
    <div class="container">
        <div style="display: flex; gap: 30px;">
            <?php $current = 'services'; include 'sidebar.php'; ?>

            <div style="flex-grow: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>Manage Services</h2>
                    <a href="<?= URLROOT; ?>/index.php?url=admin/addService" class="btn btn-primary">+ Add New Service</a>
                </div>

                <?php if(isset($_SESSION['flash_message'])): ?>
                    <div style="background-color: #059669; color: white; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                        <?= $_SESSION['flash_message']; ?>
                        <?php unset($_SESSION['flash_message']); ?>
                    </div>
                <?php endif; ?>

                <div class="card" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                                <th style="padding: 12px;">ID</th>
                                <th style="padding: 12px;">Title</th>
                                <th style="padding: 12px;">Category</th>
                                <th style="padding: 12px;">Price & Cycle</th>
                                <th style="padding: 12px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['services'] as $service): ?>
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 12px;"><?= $service->id; ?></td>
                                    <td style="padding: 12px; font-weight: 500;"><?= htmlspecialchars($service->title); ?></td>
                                    <td style="padding: 12px; color: var(--text-muted);"><?= htmlspecialchars($service->category); ?></td>
                                    <td style="padding: 12px;">₹<?= number_format($service->price, 2); ?> <small style="color: var(--text-muted);">/ <?= $service->billing_cycle; ?></small></td>
                                    <td style="padding: 12px;">
                                        <div style="display: flex; gap: 5px;">
                                            <a href="<?= URLROOT; ?>/index.php?url=admin/editService/<?= $service->id; ?>" class="btn btn-outline" style="padding: 4px 8px; font-size: 0.8rem;">Edit</a>
                                            <form action="<?= URLROOT; ?>/index.php?url=admin/deleteService/<?= $service->id; ?>" method="post" onsubmit="return confirm('Delete this service permanently?');">
                                                <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken(); ?>">
                                                <button type="submit" class="btn btn-outline" style="padding: 4px 8px; font-size: 0.8rem; color: #ef4444; border-color: #ef4444;">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
