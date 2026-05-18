    </main>
    <footer class="footer">
        <div class="container footer-container">
            <div class="footer-brand">
                <h2><?= $data['settings']['site_name'] ?? SITENAME; ?></h2>
                <p>Your trusted partner in digital transformation. We create innovative solutions that drive business growth and success.</p>
            </div>
            <div class="footer-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="<?= URLROOT; ?>/index.php?url=pages/services">Services</a></li>
                    <li><a href="<?= URLROOT; ?>/index.php?url=pages/projects">Projects</a></li>
                    <li><a href="<?= URLROOT; ?>/index.php?url=pages/contact">Contact</a></li>
                    <li><a href="<?= URLROOT; ?>/index.php?url=pages/faqs">FAQs</a></li>
                    <li><a href="<?= URLROOT; ?>/index.php?url=pages/privacy">Privacy Policy</a></li>
                    <li><a href="<?= URLROOT; ?>/index.php?url=pages/terms">Terms & Conditions</a></li>
                </ul>
            </div>
            <div class="footer-contact">
                <h3>Contact Info</h3>
                <p>Email: <?= $data['settings']['contact_email'] ?? ''; ?></p>
                <p>Phone: <?= $data['settings']['contact_phone_1'] ?? ''; ?></p>
                <p>Location: <?= $data['settings']['contact_location'] ?? ''; ?></p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y'); ?> <?= $data['settings']['site_name'] ?? SITENAME; ?>. All rights reserved. Made with ❤️ in India.</p>
        </div>
    </footer>

    <!-- Floating Action Shortcut -->
    <div style="position: fixed; bottom: 20px; left: 20px; z-index: 9999;">
        <a href="<?= URLROOT; ?>/index.php?url=client/buy" class="btn btn-primary" style="border-radius: 50px; padding: 15px 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.2); font-weight: bold;">
            🚀 Order New Service
        </a>
    </div>

    <?php require_once APPROOT . '/app/views/partials/chat.php'; ?>
    <script src="<?= URLROOT; ?>/js/main.js"></script>
</body>
</html>
