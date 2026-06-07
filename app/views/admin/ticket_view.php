<?php require APPROOT . '/app/views/partials/header.php'; ?>
<div class="dashboard-container" style="display: flex; gap: 20px; padding: 20px; max-width: 1200px; margin: 0 auto;">
    <?php
    $current = 'tickets';
    require APPROOT . '/app/views/admin/sidebar.php';
    ?>
    <div class="content" style="flex-grow: 1; display: flex; flex-direction: column; gap: 20px;">

        <!-- Ticket Header & Controls -->
        <div class="card" style="display: flex; justify-content: space-between; align-items: flex-start; padding: 20px;">
            <div>
                <a href="<?= URLROOT ?>/admin/tickets" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem; margin-bottom: 10px; display: inline-block;">&larr; Back to Tickets</a>
                <h2 style="margin: 0; display: flex; align-items: center; gap: 10px;">
                    #<?= $data['ticket']->id ?>: <?= htmlspecialchars($data['ticket']->subject) ?>
                    <span class="badge" style="
                        background: <?= $data['ticket']->status == 'open' ? '#e74c3c' : ($data['ticket']->status == 'resolved' ? '#2ecc71' : ($data['ticket']->status == 'in_progress' ? '#f39c12' : '#95a5a6')) ?>;
                        color: white; padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: normal;">
                        <?= ucfirst(str_replace('_', ' ', $data['ticket']->status)) ?>
                    </span>
                </h2>
                <div style="margin-top: 10px; color: var(--text-muted); font-size: 0.9rem;">
                    Client: <strong><?= htmlspecialchars($data['ticket']->client_name) ?></strong> (<?= htmlspecialchars($data['ticket']->client_email) ?>) <br>
                    Created: <?= date('M j, Y g:i A', strtotime($data['ticket']->created_at)) ?>
                </div>
            </div>

            <div style="background: var(--bg-main); padding: 15px; border-radius: 8px; border: 1px solid var(--border-color);">
                <form action="<?= URLROOT ?>/admin/updateTicketStatus/<?= $data['ticket']->id ?>" method="POST" style="display: flex; gap: 10px; align-items: center;">
                    <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken(); ?>">
                    <label style="font-weight: bold; font-size: 0.9rem;">Update Status:</label>
                    <select name="status" class="form-control" style="width: auto; padding: 5px 10px;" onchange="this.form.submit()">
                        <option value="open" <?= $data['ticket']->status == 'open' ? 'selected' : '' ?>>Open</option>
                        <option value="in_progress" <?= $data['ticket']->status == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="resolved" <?= $data['ticket']->status == 'resolved' ? 'selected' : '' ?>>Resolved</option>
                        <option value="closed" <?= $data['ticket']->status == 'closed' ? 'selected' : '' ?>>Closed</option>
                    </select>
                </form>
            </div>
        </div>

        <?php if(isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-success" style="padding: 15px; background: rgba(0,255,0,0.1); border-left: 4px solid #2ecc71;">
                <?= $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Thread -->
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <!-- Original Message -->
            <div class="card" style="border-left: 4px solid var(--accent-secondary);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
                    <strong><?= htmlspecialchars($data['ticket']->client_name) ?> <span style="color: var(--text-muted); font-size: 0.8rem; font-weight: normal;">(Client)</span></strong>
                    <small style="color: var(--text-muted);"><?= date('M j, Y g:i A', strtotime($data['ticket']->created_at)) ?></small>
                </div>
                <div style="white-space: pre-wrap; line-height: 1.5;"><?= htmlspecialchars($data['ticket']->message) ?></div>
            </div>

            <!-- Replies -->
            <?php foreach($data['replies'] as $reply): ?>
                <?php $isAdmin = $reply->role == 'admin'; ?>
                <div class="card" style="border-left: 4px solid <?= $isAdmin ? 'var(--accent-primary)' : 'var(--accent-secondary)' ?>; margin-left: <?= $isAdmin ? '0' : '40px' ?>; margin-right: <?= $isAdmin ? '40px' : '0' ?>;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
                        <strong><?= htmlspecialchars($reply->user_name) ?> <span style="color: var(--text-muted); font-size: 0.8rem; font-weight: normal;">(<?= $isAdmin ? 'Admin Support' : 'Client' ?>)</span></strong>
                        <small style="color: var(--text-muted);"><?= date('M j, Y g:i A', strtotime($reply->created_at)) ?></small>
                    </div>
                    <div style="white-space: pre-wrap; line-height: 1.5;"><?= htmlspecialchars($reply->message) ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Reply Box -->
        <?php if($data['ticket']->status != 'closed'): ?>
            <div class="card" style="margin-top: 10px;">
                <h3 style="margin-bottom: 15px; font-size: 1.1rem;">Post a Reply</h3>
                <form action="<?= URLROOT ?>/admin/replyTicket/<?= $data['ticket']->id ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken(); ?>">
                    <textarea name="message" class="form-control" rows="5" placeholder="Type your response here..." required></textarea>
                    <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Send Reply &rarr;</button>
                </form>
            </div>
        <?php else: ?>
            <div class="card" style="text-align: center; color: var(--text-muted); font-style: italic;">
                This ticket has been closed. No further replies can be added.
            </div>
        <?php endif; ?>

    </div>
</div>
<?php require APPROOT . '/app/views/partials/footer.php'; ?>
