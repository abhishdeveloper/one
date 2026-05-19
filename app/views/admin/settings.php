<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section bg-light" style="min-height: 80vh;">
    <div class="container">
        <div style="display: flex; gap: 30px;">
            <div class="card" style="width: 250px; flex-shrink: 0; align-self: flex-start;">
                <h3 style="margin-bottom: 20px;">Admin Menu</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/admin/index" style="display: block; padding: 10px; background: var(--bg-main); border-radius: 4px;">Dashboard</a></li>
                    <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/admin/users" style="display: block; padding: 10px; background: var(--bg-main); border-radius: 4px;">Manage Users</a></li>
                    <li style="margin-bottom: 10px;"><a href="<?= URLROOT; ?>/admin/settings" style="display: block; padding: 10px; background: var(--accent-primary); color: white; border-radius: 4px;">Site Settings</a></li>
                </ul>
            </div>

            <div style="flex-grow: 1;">
                <h2 style="margin-bottom: 20px;">Site Settings</h2>

                <?php if(isset($_SESSION['flash_message'])): ?>
                    <div style="background-color: #059669; color: white; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                        <?= $_SESSION['flash_message']; ?>
                        <?php unset($_SESSION['flash_message']); ?>
                    </div>
                <?php endif; ?>

                <form action="<?= URLROOT; ?>/admin/settings" method="post">
                    <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken(); ?>">

                    <div class="card" style="margin-bottom: 20px;">
                        <h3 style="margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Google OAuth2 Settings</h3>
                        <p style="margin-bottom: 20px; color: var(--text-muted); font-size: 0.9rem;">Configure your Google API keys here to enable "Login with Google" for clients.</p>

                        <div class="form-group">
                            <label>Client ID</label>
                            <input type="text" name="google_client_id" class="form-control" value="<?= htmlspecialchars($data['settings']['google_client_id'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label>Client Secret</label>
                            <input type="password" name="google_client_secret" class="form-control" value="<?= htmlspecialchars($data['settings']['google_client_secret'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label>Authorized Redirect URI (Read-only)</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($data['settings']['google_redirect_uri'] ?? ''); ?>" disabled style="opacity: 0.7; background-color: var(--secondary-color);">
                            <small style="color: var(--text-muted); display: block; margin-top: 5px;">Copy and paste this into your Google Cloud Console authorized redirect URIs.</small>
                        </div>
                    </div>

                    <div class="card" style="margin-bottom: 20px;">
                        <h3 style="margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">SMTP Email Settings</h3>
                        <p style="margin-bottom: 20px; color: var(--text-muted); font-size: 0.9rem;">Configure PHPMailer connection settings to enable system notifications.</p>

                        <div class="form-group">
                            <label>SMTP Host</label>
                            <input type="text" name="smtp_host" class="form-control" value="<?= htmlspecialchars($data['settings']['smtp_host'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>SMTP Port</label>
                            <input type="text" name="smtp_port" class="form-control" value="<?= htmlspecialchars($data['settings']['smtp_port'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>SMTP Username</label>
                            <input type="text" name="smtp_user" class="form-control" value="<?= htmlspecialchars($data['settings']['smtp_user'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>SMTP Password</label>
                            <input type="password" name="smtp_pass" class="form-control" value="<?= htmlspecialchars($data['settings']['smtp_pass'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>From Email Address</label>
                            <input type="email" name="smtp_from_email" class="form-control" value="<?= htmlspecialchars($data['settings']['smtp_from_email'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>From Name</label>
                            <input type="text" name="smtp_from_name" class="form-control" value="<?= htmlspecialchars($data['settings']['smtp_from_name'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="card" style="margin-bottom: 20px;">
                        <h3 style="margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Payment Settings</h3>
                        <p style="margin-bottom: 20px; color: var(--text-muted); font-size: 0.9rem;">Configure UPI details for receiving manual payments.</p>

                        <div class="form-group">
                            <label>Admin UPI ID (e.g. name@bank)</label>
                            <input type="text" name="upi_id" class="form-control" value="<?= htmlspecialchars($data['settings']['upi_id'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="card" style="margin-bottom: 20px;">
                        <h3 style="margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Global Announcement</h3>
                        <p style="margin-bottom: 20px; color: var(--text-muted); font-size: 0.9rem;">Display a floating banner/notice to all site visitors and clients.</p>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="announcement_active" class="form-control">
                                <option value="0" <?= (isset($data['settings']['announcement_active']) && $data['settings']['announcement_active'] == '0') ? 'selected' : ''; ?>>Disabled</option>
                                <option value="1" <?= (isset($data['settings']['announcement_active']) && $data['settings']['announcement_active'] == '1') ? 'selected' : ''; ?>>Active (Visible)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Announcement Text / Notice</label>
                            <textarea name="announcement_text" class="form-control" rows="2"><?= htmlspecialchars($data['settings']['announcement_text'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">Save All Settings</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
