<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section bg-light" style="min-height: 80vh;">
    <div class="container">
        <div style="display: flex; gap: 30px;">
            <?php $current = 'buy'; include 'sidebar.php'; ?>

            <div style="flex-grow: 1;">
                <h2 style="margin-bottom: 20px;">Order New Service</h2>

                <div class="card">
                    <form action="<?= URLROOT; ?>/index.php?url=client/buy" method="post">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken(); ?>">

                        <div class="form-group">
                            <label>Select Service</label>
                            <select name="service_id" class="form-control" required style="cursor: pointer;">
                                <option value="">-- Choose a Service --</option>
                                <?php foreach($data['available_services'] as $s): ?>
                                    <option value="<?= $s->id; ?>"><?= htmlspecialchars($s->title); ?> - $19.99/mo</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Domain Name / Project Name</label>
                            <input type="text" name="domain_name" class="form-control" placeholder="e.g. mycompany.com" required>
                        </div>

                        <div style="background: var(--bg-dark); padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                            <h4 style="margin-bottom: 10px;">Order Summary</h4>
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 10px; margin-bottom: 10px;">
                                <span>Setup Fee</span>
                                <span>$0.00</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-weight: bold;">
                                <span>Total Due Today</span>
                                <span>$19.99</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">Place Order & Generate Invoice</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
