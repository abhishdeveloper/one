<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section bg-light" style="min-height: 80vh;">
    <div class="container">
        <div class="dashboard-layout">
            <?php $current = 'tickets'; include 'sidebar.php'; ?>

            <div class="dashboard-content">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>Support Tickets</h2>
                    <button onclick="document.getElementById('newTicketForm').style.display='block'" class="btn btn-primary">Open New Ticket</button>
                </div>

                <?php if(isset($_SESSION['flash_message'])): ?>
                    <div style="background-color: #10b981; color: white; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                        <?= $_SESSION['flash_message']; ?>
                        <?php unset($_SESSION['flash_message']); ?>
                    </div>
                <?php endif; ?>

                <div id="newTicketForm" class="card" style="display: none; margin-bottom: 30px; border: 1px solid var(--accent-primary);">
                    <h3 style="margin-bottom: 15px;">Open New Ticket</h3>
                    <form action="<?= URLROOT; ?>/client/tickets" method="post">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken(); ?>">

                        <div class="form-group">
                            <label>Related Service (Optional)</label>
                            <select name="client_service_id" class="form-control">
                                <option value="">-- General Support --</option>
                                <?php foreach($data['active_services'] as $s): ?>
                                    <option value="<?= $s->id; ?>"><?= htmlspecialchars($s->title); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="btn btn-primary">Submit Ticket</button>
                            <button type="button" class="btn btn-secondary" onclick="document.getElementById('newTicketForm').style.display='none'">Cancel</button>
                        </div>
                    </form>
                </div>

                <div class="card" style="overflow-x: auto;">
                    <?php if(empty($data['tickets'])): ?>
                        <p style="color: var(--text-muted); text-align: center; padding: 20px;">You have no support tickets.</p>
                    <?php else: ?>
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                                    <th style="padding: 12px;">ID</th>
                                    <th style="padding: 12px;">Subject</th>
                                    <th style="padding: 12px;">Status</th>
                                    <th style="padding: 12px;">Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['tickets'] as $ticket): ?>
                                    <tr style="border-bottom: 1px solid var(--border-color);">
                                        <td style="padding: 12px;">#<?= $ticket->id; ?></td>
                                        <td style="padding: 12px; font-weight: 500;"><a href="<?= URLROOT; ?>/client/viewTicket/<?= $ticket->id; ?>" style="color: inherit; text-decoration: none;"><?= htmlspecialchars($ticket->subject); ?></a></td>
                                        <td style="padding: 12px;">
                                            <?php
                                                $color = '#3b82f6'; // open/in_progress
                                                if($ticket->status == 'resolved' || $ticket->status == 'closed') $color = '#94a3b8';
                                            ?>
                                            <span style="background: <?= $color; ?>; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem; color: white;"><?= ucfirst(str_replace('_', ' ', $ticket->status)); ?></span>
                                        </td>
                                        <td style="padding: 12px; color: var(--text-muted); font-size: 0.9rem;"><?= date('M d, H:i', strtotime($ticket->updated_at)); ?></td>
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
