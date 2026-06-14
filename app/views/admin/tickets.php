<?php require APPROOT . '/app/views/partials/header.php'; ?>
<div class="container dashboard-layout" style="padding-top: 40px; padding-bottom: 40px;">
    <?php
    $current = 'tickets';
    require APPROOT . '/app/views/admin/sidebar.php';
    ?>
    <div class="dashboard-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Support Tickets</h2>
            <div style="display: flex; gap: 15px;">
                <div class="card" style="padding: 10px 20px; text-align: center;">
                    <h3 style="color: var(--accent-primary); margin: 0;"><?= count(array_filter($data['tickets'], fn($t) => $t->status == 'open')) ?></h3>
                    <small>Open</small>
                </div>
                <div class="card" style="padding: 10px 20px; text-align: center;">
                    <h3 style="color: var(--accent-secondary); margin: 0;"><?= count(array_filter($data['tickets'], fn($t) => $t->status == 'in_progress')) ?></h3>
                    <small>In Progress</small>
                </div>
            </div>
        </div>

        <?php if(isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-success" style="padding: 15px; background: rgba(0,255,0,0.1); border-left: 4px solid #2ecc71; margin-bottom: 20px;">
                <?= $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <div class="card" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border-color);">
                        <th style="padding: 12px;">ID</th>
                        <th style="padding: 12px;">Client</th>
                        <th style="padding: 12px;">Subject</th>
                        <th style="padding: 12px;">Status</th>
                        <th style="padding: 12px;">Priority</th>
                        <th style="padding: 12px;">Last Updated</th>
                        <th style="padding: 12px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['tickets'] as $ticket): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 12px;">#<?= $ticket->id ?></td>
                            <td style="padding: 12px;">
                                <strong><?= htmlspecialchars($ticket->client_name) ?></strong><br>
                                <small style="color: var(--text-muted);"><?= htmlspecialchars($ticket->client_email) ?></small>
                            </td>
                            <td style="padding: 12px;">
                                <?= htmlspecialchars($ticket->subject) ?>
                                <?php if($ticket->client_service_title): ?>
                                    <br><small style="color: var(--accent-primary);">Service: <?= htmlspecialchars($ticket->client_service_title) ?></small>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px;">
                                <span class="badge" style="
                                    background: <?= $ticket->status == 'open' ? '#e74c3c' : ($ticket->status == 'resolved' ? '#2ecc71' : ($ticket->status == 'in_progress' ? '#f39c12' : '#95a5a6')) ?>;
                                    color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem;">
                                    <?= ucfirst(str_replace('_', ' ', $ticket->status)) ?>
                                </span>
                            </td>
                            <td style="padding: 12px;">
                                <span class="badge" style="
                                    background: <?= $ticket->priority == 'high' ? '#e74c3c' : ($ticket->priority == 'medium' ? '#f39c12' : '#3498db') ?>;
                                    color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem;">
                                    <?= ucfirst($ticket->priority) ?>
                                </span>
                            </td>
                            <td style="padding: 12px;"><?= date('M j, Y g:i A', strtotime($ticket->updated_at)) ?></td>
                            <td style="padding: 12px;">
                                <a href="<?= URLROOT ?>/admin/viewTicket/<?= $ticket->id ?>" class="btn btn-primary btn-sm">View Thread</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($data['tickets'])): ?>
                        <tr><td colspan="7" style="padding: 20px; text-align: center; color: var(--text-muted);">No support tickets found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require APPROOT . '/app/views/partials/footer.php'; ?>
