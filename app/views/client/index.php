<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section bg-light" style="min-height: 80vh;">
    <div class="container">
        <div class="dashboard-layout">
            <?php $current = 'index'; include 'sidebar.php'; ?>

            <div class="dashboard-content">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?></h2>
                    <a href="<?= URLROOT; ?>/client/buy" class="btn btn-primary">+ New Service</a>
                </div>

                <div class="grid-2" style="margin-bottom: 30px;">
                    <div class="card">
                        <h3>Active Services</h3>
                        <p style="font-size: 2.5rem; color: var(--accent-primary); font-weight: bold; margin-top: 10px;">
                            <?= count(array_filter($data['services'], fn($s) => $s->status == 'active')); ?>
                        </p>
                        <a href="<?= URLROOT; ?>/client/services" style="display: inline-block; margin-top: 10px;">View All Services →</a>
                    </div>
                    <div class="card">
                        <h3>Unpaid Invoices</h3>
                        <p style="font-size: 2.5rem; color: #ef4444; font-weight: bold; margin-top: 10px;">
                            $<?= number_format($data['totalDue'], 2); ?>
                        </p>
                        <a href="<?= URLROOT; ?>/client/invoices" style="display: inline-block; margin-top: 10px;">Pay Now →</a>
                    </div>
                </div>

                <div class="card">
                    <h3 style="margin-bottom: 15px;">Recent Services</h3>
                    <?php if (empty($data['services'])): ?>
                        <p style="color: var(--text-muted);">You don't have any active services yet.</p>
                    <?php else: ?>
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                                    <th style="padding: 12px;">Service</th>
                                    <th style="padding: 12px;">Domain/Details</th>
                                    <th style="padding: 12px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach(array_slice($data['services'], 0, 5) as $service): ?>
                                    <tr style="border-bottom: 1px solid var(--border-color);">
                                        <td style="padding: 12px; font-weight: 500;"><?= htmlspecialchars($service->title); ?></td>
                                        <td style="padding: 12px; color: var(--text-muted);"><?= htmlspecialchars($service->domain_name ?? 'N/A'); ?></td>
                                        <td style="padding: 12px;">
                                            <?php
                                                $color = '#94a3b8';
                                                if($service->status == 'active') $color = '#10b981';
                                                if($service->status == 'pending') $color = '#f59e0b';
                                            ?>
                                            <span style="background: <?= $color; ?>; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem; color: white;"><?= ucfirst($service->status); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
