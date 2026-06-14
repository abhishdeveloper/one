<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section bg-light" style="min-height: 80vh;">
    <div class="container">
        <div class="dashboard-layout">
            <?php $current = 'index'; require APPROOT . '/app/views/admin/sidebar.php'; ?>

            <div class="dashboard-content">
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
