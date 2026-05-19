<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section bg-light" style="min-height: 80vh;">
    <div class="container">
        <div style="display: flex; gap: 30px;">
            <div class="card" style="width: 250px; flex-shrink: 0; align-self: flex-start;">
                <h3 style="margin-bottom: 20px;">Admin Menu</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/admin/index" style="display: block; padding: 10px; background: var(--bg-main); border-radius: 4px;">Dashboard</a></li>
                    <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/admin/users" style="display: block; padding: 10px; background: var(--bg-main); border-radius: 4px;">Manage Users</a></li>
                    <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/admin/settings" style="display: block; padding: 10px; background: var(--bg-main); border-radius: 4px;">Site Settings</a></li>
                </ul>
            </div>

            <div style="flex-grow: 1;">
                <h2 style="margin-bottom: 20px;">Admin Dashboard</h2>
                <div class="grid-3">
                    <div class="card text-center">
                        <h3>Total Clients</h3>
                        <p style="font-size: 2.5rem; color: var(--accent-primary); font-weight: bold; margin-top: 10px;"><?= $data['clientCount']; ?></p>
                    </div>
                    <div class="card text-center">
                        <h3>Active Services</h3>
                        <p style="font-size: 2.5rem; color: var(--accent-primary); font-weight: bold; margin-top: 10px;"><?= $data['activeServicesCount']; ?></p>
                    </div>
                    <div class="card text-center">
                        <h3>Open Tickets</h3>
                        <p style="font-size: 2.5rem; color: #ef4444; font-weight: bold; margin-top: 10px;"><?= $data['openTicketsCount']; ?></p>
                    </div>
                </div>

                <div class="card mt-2">
                    <h3>Welcome back, <?= htmlspecialchars($_SESSION['user_name']); ?>!</h3>
                    <p style="margin-top: 10px;">Use the sidebar to manage users, configure settings, and oversee the hosting platform.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
