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
                    <li><a href="<?= URLROOT; ?>/pages/services">Services</a></li>
                    <li><a href="<?= URLROOT; ?>/pages/projects">Projects</a></li>
                    <li><a href="<?= URLROOT; ?>/pages/contact">Contact</a></li>
                    <li><a href="<?= URLROOT; ?>/pages/faqs">FAQs</a></li>
                    <li><a href="<?= URLROOT; ?>/pages/privacy">Privacy Policy</a></li>
                    <li><a href="<?= URLROOT; ?>/pages/terms">Terms & Conditions</a></li>
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
    <script src="<?= URLROOT; ?>/js/main.js"></script>
</body>
</html>
