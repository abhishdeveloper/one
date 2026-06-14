<?php require APPROOT . '/app/views/partials/header.php'; ?>
<div class="container dashboard-layout" style="padding-top: 40px; padding-bottom: 40px;">
    <?php
    $current = 'buy';
    require APPROOT . '/app/views/client/sidebar.php';
    ?>
    <div class="dashboard-content">
        <h2 style="margin-bottom: 20px;">Complete Your Order</h2>

        <div class="card" style="max-width: 600px;">
            <h3 style="margin-bottom: 15px; color: var(--accent-primary);">Order Summary</h3>
            <div style="background: var(--bg-main); padding: 20px; border-radius: 8px; margin-bottom: 25px; border: 1px solid var(--border-color);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px dashed var(--border-color); padding-bottom: 10px;">
                    <span style="font-weight: 500;">Service</span>
                    <strong><?= htmlspecialchars($data['service']->title); ?></strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px dashed var(--border-color); padding-bottom: 10px;">
                    <span style="font-weight: 500;">Billing Cycle</span>
                    <span style="text-transform: capitalize; color: var(--text-muted);"><?= htmlspecialchars($data['service']->billing_cycle); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
                    <span style="font-weight: bold; font-size: 1.1rem;">Total Due</span>
                    <span style="font-weight: bold; font-size: 1.5rem; color: var(--accent-primary);">₹<?= number_format($data['service']->price, 2); ?></span>
                </div>
            </div>

            <form action="<?= URLROOT; ?>/client/checkout/<?= $data['service']->id; ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken(); ?>">

                <div class="form-group">
                    <label>Domain Name / Project Name *</label>
                    <input type="text" name="domain_name" class="form-control" placeholder="e.g., mybusiness.com or Project Alpha" required>
                    <small style="color: var(--text-muted); display: block; margin-top: 5px;">This will be used to identify your service.</small>
                </div>

                <div style="margin-top: 30px; display: flex; gap: 15px; flex-wrap: wrap;">
                    <button type="submit" class="btn btn-primary" style="flex-grow: 1;">Generate Invoice & Checkout</button>
                    <a href="<?= URLROOT; ?>/client/buy" class="btn btn-secondary" style="flex-grow: 1; text-align: center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/app/views/partials/footer.php'; ?>
