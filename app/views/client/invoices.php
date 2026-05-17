<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section bg-light" style="min-height: 80vh;">
    <div class="container">
        <div style="display: flex; gap: 30px;">
            <?php $current = 'invoices'; include 'sidebar.php'; ?>

            <div style="flex-grow: 1;">
                <h2 style="margin-bottom: 20px;">Invoices & Billing</h2>

                <?php if(isset($_SESSION['flash_message'])): ?>
                    <div style="background-color: #10b981; color: white; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                        <?= $_SESSION['flash_message']; ?>
                        <?php unset($_SESSION['flash_message']); ?>
                    </div>
                <?php endif; ?>

                <div class="card" style="overflow-x: auto;">
                    <?php if(empty($data['invoices'])): ?>
                        <p style="color: var(--text-muted); text-align: center; padding: 20px;">You have no invoices.</p>
                    <?php else: ?>
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                                    <th style="padding: 12px;">Invoice #</th>
                                    <th style="padding: 12px;">Description</th>
                                    <th style="padding: 12px;">Due Date</th>
                                    <th style="padding: 12px;">Amount</th>
                                    <th style="padding: 12px;">Status</th>
                                    <th style="padding: 12px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['invoices'] as $inv): ?>
                                    <tr style="border-bottom: 1px solid var(--border-color);">
                                        <td style="padding: 12px;">INV-<?= str_pad($inv->id, 5, '0', STR_PAD_LEFT); ?></td>
                                        <td style="padding: 12px;"><?= htmlspecialchars($inv->service_name ?? 'General Charge'); ?></td>
                                        <td style="padding: 12px;"><?= date('M d, Y', strtotime($inv->due_date)); ?></td>
                                        <td style="padding: 12px; font-weight: bold;">$<?= number_format($inv->amount, 2); ?></td>
                                        <td style="padding: 12px;">
                                            <?php
                                                $color = '#ef4444'; // unpaid
                                                if($inv->status == 'paid') $color = '#10b981';
                                                if($inv->status == 'cancelled') $color = '#94a3b8';
                                            ?>
                                            <span style="background: <?= $color; ?>; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem; color: white;"><?= ucfirst($inv->status); ?></span>
                                        </td>
                                        <td style="padding: 12px;">
                                            <?php if($inv->status == 'unpaid'): ?>
                                                <a href="<?= URLROOT; ?>/index.php?url=client/pay/<?= $inv->id; ?>" class="btn btn-primary" style="padding: 4px 12px; font-size: 0.85rem;" onclick="return confirm('Simulate paying this invoice?');">Pay Now</a>
                                            <?php else: ?>
                                                <span style="color: var(--text-muted); font-size: 0.85rem;">Paid on <?= date('M d', strtotime($inv->paid_date)); ?></span>
                                            <?php endif; ?>
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
