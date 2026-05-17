<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section bg-light" style="min-height: 80vh;">
    <div class="container">
        <div style="display: flex; gap: 30px;">
            <div class="card" style="width: 250px; flex-shrink: 0; align-self: flex-start;">
                <h3 style="margin-bottom: 20px;">Admin Menu</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/index.php?url=admin/index" style="display: block; padding: 10px; background: var(--bg-dark); border-radius: 4px;">Dashboard</a></li>
                    <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/index.php?url=admin/users" style="display: block; padding: 10px; background: var(--primary-color); color: white; border-radius: 4px;">Manage Users</a></li>
                    <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/index.php?url=admin/settings" style="display: block; padding: 10px; background: var(--bg-dark); border-radius: 4px;">Site Settings</a></li>
                </ul>
            </div>

            <div style="flex-grow: 1;">
                <h2 style="margin-bottom: 20px;">Manage Users</h2>

                <?php if(isset($_SESSION['flash_message'])): ?>
                    <div style="background-color: #059669; color: white; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                        <?= $_SESSION['flash_message']; ?>
                        <?php unset($_SESSION['flash_message']); ?>
                    </div>
                <?php endif; ?>

                <div class="card" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                                <th style="padding: 12px;">ID</th>
                                <th style="padding: 12px;">Name</th>
                                <th style="padding: 12px;">Email</th>
                                <th style="padding: 12px;">Role</th>
                                <th style="padding: 12px;">Status</th>
                                <th style="padding: 12px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['users'] as $user): ?>
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 12px;"><?= $user->id; ?></td>
                                    <td style="padding: 12px;"><?= htmlspecialchars($user->name); ?></td>
                                    <td style="padding: 12px;"><?= htmlspecialchars($user->email); ?></td>
                                    <td style="padding: 12px;">
                                        <span style="background: <?= $user->role == 'admin' ? '#ef4444' : '#3b82f6'; ?>; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem;"><?= ucfirst($user->role); ?></span>
                                    </td>
                                    <td style="padding: 12px;"><?= ucfirst($user->status); ?></td>
                                    <td style="padding: 12px;">
                                        <?php if ($user->id != $_SESSION['user_id']): ?>
                                            <form action="<?= URLROOT; ?>/index.php?url=admin/deleteUser/<?= $user->id; ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken(); ?>">
                                                <button type="submit" class="btn btn-outline" style="padding: 4px 8px; font-size: 0.8rem; color: #ef4444; border-color: #ef4444;">Delete</button>
                                            </form>
                                        <?php else: ?>
                                            <span style="color: var(--text-muted); font-size: 0.85rem;">You</span>
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
