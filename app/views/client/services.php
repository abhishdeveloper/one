<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section bg-light" style="min-height: 80vh;">
    <div class="container">
        <div style="display: flex; gap: 30px;">
            <?php $current = 'services'; include 'sidebar.php'; ?>

            <div style="flex-grow: 1;">
                <h2 style="margin-bottom: 20px;">My Services</h2>

                <?php if (empty($data['services'])): ?>
                    <div class="card text-center" style="padding: 40px;">
                        <p style="color: var(--text-muted); margin-bottom: 20px;">You don't have any services yet.</p>
                        <a href="<?= URLROOT; ?>/index.php?url=client/buy" class="btn btn-primary">Browse Services</a>
                    </div>
                <?php else: ?>
                    <div class="grid-2">
                        <?php foreach($data['services'] as $service): ?>
                            <div class="card" style="border-top: 4px solid <?= $service->status == 'active' ? '#10b981' : ($service->status == 'pending' ? '#f59e0b' : '#ef4444'); ?>">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                                    <h3><?= htmlspecialchars($service->title); ?></h3>
                                    <span style="background: <?= $service->status == 'active' ? '#10b981' : ($service->status == 'pending' ? '#f59e0b' : '#ef4444'); ?>; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem; color: white;">
                                        <?= ucfirst($service->status); ?>
                                    </span>
                                </div>

                                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 15px;"><?= htmlspecialchars($service->description); ?></p>

                                <div style="background: var(--bg-main); padding: 10px; border-radius: 4px; font-size: 0.9rem;">
                                    <p style="margin-bottom: 5px;"><strong>Domain/Target:</strong> <?= htmlspecialchars($service->domain_name ?? 'Not specified'); ?></p>
                                    <p style="margin-bottom: 5px;"><strong>Billing:</strong> $<?= number_format($service->price, 2); ?> (<?= ucfirst($service->billing_cycle); ?>)</p>
                                    <?php if($service->next_due_date): ?>
                                        <p><strong>Next Due:</strong> <?= date('M d, Y', strtotime($service->next_due_date)); ?></p>
                                    <?php endif; ?>
                                </div>

                                <?php
                                    // Calculate simple progress bar logic based on status
                                    $progress = 0;
                                    $progColor = '#ef4444';
                                    if ($service->status == 'pending') { $progress = 30; $progColor = '#f59e0b'; }
                                    if ($service->status == 'active') { $progress = 100; $progColor = '#10b981'; }
                                    if ($service->status == 'suspended' || $service->status == 'cancelled') { $progress = 100; }
                                ?>
                                <div style="margin-top: 15px; margin-bottom: 15px;">
                                    <div style="display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 5px; color: var(--text-muted);">
                                        <span>Order Status Progress</span>
                                        <span><?= $progress; ?>%</span>
                                    </div>
                                    <div style="width: 100%; background-color: var(--bg-main); border-radius: 4px; height: 8px; overflow: hidden;">
                                        <div style="width: <?= $progress; ?>%; background-color: <?= $progColor; ?>; height: 100%;"></div>
                                    </div>
                                </div>

                                <?php if($service->status == 'active'): ?>
                                    <div style="margin-top: auto;">
                                        <a href="<?= URLROOT; ?>/index.php?url=client/tickets" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.85rem;">Get Support</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
