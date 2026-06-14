<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section bg-light" style="min-height: 80vh;">
    <div class="container">
        <div class="dashboard-layout">
            <?php $current = 'invoices'; require APPROOT . '/app/views/admin/sidebar.php'; ?>

            <div class="dashboard-content">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>Manage Invoices</h2>
                    <button onclick="document.getElementById('newInvoiceForm').style.display='block'" class="btn btn-primary">+ Create Custom Invoice</button>
                </div>

                <?php if(isset($_SESSION['flash_message'])): ?>
                    <div style="background-color: #059669; color: white; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                        <?= $_SESSION['flash_message']; ?>
                        <?php unset($_SESSION['flash_message']); ?>
                    </div>
                <?php endif; ?>

                <div id="newInvoiceForm" class="card" style="display: none; margin-bottom: 30px; border: 1px solid var(--accent-primary);">
                    <h3 style="margin-bottom: 15px;">Create Custom Invoice</h3>
                    <form action="<?= URLROOT; ?>/admin/createInvoice" method="post">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken(); ?>">

                        <div class="form-group">
                            <label>Select Client</label>
                            <select name="user_id" class="form-control" required>
                                <option value="">-- Choose Client --</option>
                                <?php foreach($data['clients'] as $client): ?>
                                    <option value="<?= $client->id; ?>"><?= htmlspecialchars($client->name); ?> (<?= htmlspecialchars($client->email); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Amount (INR)</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="btn btn-primary">Generate Invoice</button>
                            <button type="button" class="btn btn-secondary" onclick="document.getElementById('newInvoiceForm').style.display='none'">Cancel</button>
                        </div>
                    </form>
                </div>

                <div class="card" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                                <th style="padding: 12px;">ID</th>
                                <th style="padding: 12px;">Client</th>
                                <th style="padding: 12px;">Amount</th>
                                <th style="padding: 12px;">Status & UTR</th>
                                <th style="padding: 12px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['invoices'] as $inv): ?>
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 12px;">INV-<?= $inv->id; ?></td>
                                    <td style="padding: 12px;"><?= htmlspecialchars($inv->client_name); ?><br><small style="color: var(--text-muted);"><?= htmlspecialchars($inv->client_email); ?></small></td>
                                    <td style="padding: 12px; font-weight: bold;">₹<?= number_format($inv->amount, 2); ?></td>
                                    <td style="padding: 12px;">
                                        <?php
                                            $color = '#ef4444'; // unpaid
                                            if($inv->status == 'paid') $color = '#10b981';
                                            if($inv->status == 'cancelled') $color = '#94a3b8';
                                            if($inv->status == 'pending_verification') $color = '#f59e0b';
                                        ?>
                                        <span style="background: <?= $color; ?>; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem; color: white; display: inline-block; margin-bottom: 5px;"><?= ucfirst(str_replace('_', ' ', $inv->status)); ?></span>
                                        <?php if($inv->utr_number): ?>
                                            <br><small>UTR: <strong><?= htmlspecialchars($inv->utr_number); ?></strong></small>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 12px;">
                                        <?php if($inv->status == 'pending_verification'): ?>
                                            <div style="display: flex; gap: 5px;">
                                                <form action="<?= URLROOT; ?>/admin/verifyPayment/<?= $inv->id; ?>/approve" method="post" onsubmit="return confirm('Approve this payment?');">
                                                    <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken(); ?>">
                                                    <button type="submit" class="btn btn-outline" style="padding: 4px 8px; font-size: 0.8rem; color: #10b981; border-color: #10b981;">Approve</button>
                                                </form>
                                                <form action="<?= URLROOT; ?>/admin/verifyPayment/<?= $inv->id; ?>/reject" method="post" onsubmit="return confirm('Reject this payment?');">
                                                    <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken(); ?>">
                                                    <button type="submit" class="btn btn-outline" style="padding: 4px 8px; font-size: 0.8rem; color: #ef4444; border-color: #ef4444;">Reject</button>
                                                </form>
                                            </div>
                                        <?php else: ?>
                                            <span style="color: var(--text-muted); font-size: 0.85rem;">-</span>
                                        <?php endif; ?>
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
