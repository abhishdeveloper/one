<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section bg-light" style="min-height: 80vh;">
    <div class="container">
        <div class="dashboard-layout">
            <?php $current = 'invoices'; include 'sidebar.php'; ?>

            <div class="dashboard-content">
                <h2 style="margin-bottom: 20px;">Pay Invoice #<?= $data['invoice']->id; ?></h2>

                <?php if(isset($_SESSION['flash_message'])): ?>
                    <div style="background-color: #ef4444; color: white; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                        <?= $_SESSION['flash_message']; ?>
                        <?php unset($_SESSION['flash_message']); ?>
                    </div>
                <?php endif; ?>

                <div class="grid-2">
                    <!-- UPI Payment Details -->
                    <div class="card text-center">
                        <h3 style="margin-bottom: 15px;">Scan QR Code via UPI</h3>
                        <p style="margin-bottom: 20px; color: var(--text-muted);">Pay securely using Google Pay, PhonePe, Paytm, or any UPI app.</p>

                        <?php
                            $upiId = $data['settings']['upi_id'] ?? '';
                            $amount = number_format($data['invoice']->amount, 2, '.', '');
                            $name = urlencode($data['settings']['site_name'] ?? 'Admin');
                            $note = urlencode("Invoice " . $data['invoice']->id);

                            if (empty($upiId)):
                        ?>
                            <div style="padding: 20px; background: #ef4444; color: white; border-radius: 4px;">Admin has not configured a UPI ID yet.</div>
                        <?php else:
                            $upiString = "upi://pay?pa={$upiId}&pn={$name}&am={$amount}&cu=INR&tn={$note}";
                            // Using a free reliable QR Code API generator (QuickChart API)
                            $qrUrl = "https://quickchart.io/qr?text=" . urlencode($upiString) . "&size=250&margin=2";
                        ?>
                            <img src="<?= $qrUrl; ?>" alt="UPI QR Code" style="border: 10px solid white; border-radius: 8px; margin-bottom: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">

                            <p style="font-size: 1.2rem; margin-bottom: 5px;"><strong>UPI ID:</strong> <?= htmlspecialchars($upiId); ?></p>
                            <p style="font-size: 1.5rem; color: var(--accent-primary); font-weight: bold;">₹<?= $amount; ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- UTR Submission Form -->
                    <div class="card">
                        <h3 style="margin-bottom: 15px;">Submit Transaction Details</h3>
                        <p style="margin-bottom: 20px; color: var(--text-muted);">After completing the payment using the QR code or UPI ID, please enter the 12-digit UTR (Unique Transaction Reference) or Transaction ID below.</p>

                        <div style="background: var(--bg-main); padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                            <p><strong>Invoice:</strong> <?= htmlspecialchars($data['invoice']->service_name ?? 'Custom Invoice'); ?></p>
                            <p><strong>Amount:</strong> ₹<?= number_format($data['invoice']->amount, 2); ?></p>
                        </div>

                        <form action="<?= URLROOT; ?>/client/submitUtr/<?= $data['invoice']->id; ?>" method="post">
                            <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken(); ?>">

                            <div class="form-group">
                                <label>UTR / Transaction ID</label>
                                <input type="text" name="utr_number" class="form-control" placeholder="e.g. 231456789012" required>
                            </div>

                            <button type="submit" class="btn btn-primary" style="width: 100%;" <?= empty($upiId) ? 'disabled' : ''; ?>>Submit Payment Verification</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
