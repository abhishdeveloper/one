<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section bg-light" style="min-height: 80vh;">
    <div class="container">
        <div style="display: flex; gap: 30px;">
            <?php $current = 'audit_logs'; include 'sidebar.php'; ?>

            <div style="flex-grow: 1;">
                <h2 style="margin-bottom: 20px;">Client Logbook (Audit Logs)</h2>
                <p style="color: var(--text-muted); margin-bottom: 20px;">A permanent record of all client activities across the platform.</p>

                <div class="card" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                                <th style="padding: 12px;">Timestamp</th>
                                <th style="padding: 12px;">Client</th>
                                <th style="padding: 12px;">Action Recorded</th>
                                <th style="padding: 12px;">IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['logs'])): ?>
                                <tr><td colspan="4" style="padding: 20px; text-align: center; color: var(--text-muted);">No logs recorded yet.</td></tr>
                            <?php else: ?>
                                <?php foreach($data['logs'] as $log): ?>
                                    <tr style="border-bottom: 1px solid var(--border-color);">
                                        <td style="padding: 12px; font-size: 0.9rem; color: var(--text-muted);"><?= date('Y-m-d H:i:s', strtotime($log->created_at)); ?></td>
                                        <td style="padding: 12px;">
                                            <strong><?= htmlspecialchars($log->name); ?></strong><br>
                                            <small style="color: var(--text-muted);"><?= htmlspecialchars($log->email); ?></small>
                                        </td>
                                        <td style="padding: 12px; font-weight: 500; color: var(--primary-color);"><?= htmlspecialchars($log->action); ?></td>
                                        <td style="padding: 12px; font-family: monospace; font-size: 0.9rem;"><?= htmlspecialchars($log->ip_address); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
