<?php require APPROOT . '/app/views/partials/header.php'; ?>
<div class="container dashboard-layout" style="padding-top: 40px; padding-bottom: 40px;">
    <?php
    $current = 'tickets';
    require APPROOT . '/app/views/client/sidebar.php';
    ?>
    <div class="dashboard-content" style="display: flex; flex-direction: column; gap: 20px;">

        <!-- Ticket Header -->
        <div class="card ticket-header-controls" style="display: flex; justify-content: space-between; align-items: flex-start; padding: 20px;">
            <div>
                <a href="<?= URLROOT ?>/client/tickets" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem; margin-bottom: 10px; display: inline-block;">&larr; Back to Tickets</a>
                <h2 style="margin: 0; display: flex; align-items: center; gap: 10px;">
                    <?= htmlspecialchars($data['ticket']->subject) ?>
                    <span class="badge" style="
                        background: <?= $data['ticket']->status == 'open' ? '#e74c3c' : ($data['ticket']->status == 'resolved' ? '#2ecc71' : ($data['ticket']->status == 'in_progress' ? '#f39c12' : '#95a5a6')) ?>;
                        color: white; padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: normal;">
                        <?= ucfirst(str_replace('_', ' ', $data['ticket']->status)) ?>
                    </span>
                </h2>
                <div style="margin-top: 10px; color: var(--text-muted); font-size: 0.9rem;">
                    Ticket ID: <strong>#<?= $data['ticket']->id ?></strong> <br>
                    Created: <?= date('M j, Y g:i A', strtotime($data['ticket']->created_at)) ?>
                </div>
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
            <div class="card ticket-message-card" style="border-left: 4px solid var(--accent-secondary); margin-right: 40px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
                    <strong>You <span style="color: var(--text-muted); font-size: 0.8rem; font-weight: normal;">(Client)</span></strong>
                    <small style="color: var(--text-muted);"><?= date('M j, Y g:i A', strtotime($data['ticket']->created_at)) ?></small>
                </div>
                <div style="white-space: pre-wrap; line-height: 1.5;"><?= htmlspecialchars($data['ticket']->message) ?></div>
            </div>

            <!-- Replies -->
            <?php foreach($data['replies'] as $reply): ?>
                <?php $isAdmin = $reply->role == 'admin'; ?>
                <div class="card ticket-message-card" style="border-left: 4px solid <?= $isAdmin ? 'var(--accent-primary)' : 'var(--accent-secondary)' ?>; margin-left: <?= $isAdmin ? '40px' : '0' ?>; margin-right: <?= $isAdmin ? '0' : '40px' ?>;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
                        <strong><?= htmlspecialchars($reply->user_name) ?> <span style="color: var(--text-muted); font-size: 0.8rem; font-weight: normal;">(<?= $isAdmin ? 'Admin Support' : 'You' ?>)</span></strong>
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
                <form action="<?= URLROOT ?>/client/replyTicket/<?= $data['ticket']->id ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken(); ?>">
                    <textarea name="message" class="form-control" rows="4" placeholder="Type your response here..." required></textarea>
                    <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Send Reply &rarr;</button>
                </form>
            </div>
        <?php else: ?>
            <div class="card" style="text-align: center; color: var(--text-muted); font-style: italic;">
                This ticket has been closed. If you need further assistance, please open a new ticket.
            </div>
        <?php endif; ?>

    </div>
</div>
<?php require APPROOT . '/app/views/partials/footer.php'; ?>
