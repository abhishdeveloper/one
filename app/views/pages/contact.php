<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <p>Ready to start your next project? Get in touch with us</p>
    </div>
</section>

<section class="section bg-light">
    <div class="container">
        <div class="contact-wrapper">
            <div class="contact-info-boxes">
                <div class="card">
                    <h3>Email</h3>
                    <p><a href="mailto:<?= $data['settings']['contact_email']; ?>"><?= $data['settings']['contact_email']; ?></a></p>
                </div>
                <div class="card">
                    <h3>Phone</h3>
                    <p><a href="tel:<?= str_replace(' ', '', $data['settings']['contact_phone_1']); ?>"><?= $data['settings']['contact_phone_1']; ?></a></p>
                    <p><a href="tel:<?= str_replace(' ', '', $data['settings']['contact_phone_2']); ?>"><?= $data['settings']['contact_phone_2']; ?></a></p>
                    <p><a href="tel:<?= str_replace(' ', '', $data['settings']['contact_phone_3']); ?>"><?= $data['settings']['contact_phone_3']; ?></a></p>
                </div>
                <div class="card">
                    <h3>Location</h3>
                    <p><?= $data['settings']['contact_location']; ?></p>
                </div>
            </div>

            <div class="contact-form card mt-2">
                <h3>Send us a message</h3>
                <form action="#" method="POST">
                    <!-- Basic placeholder form, logic not implemented yet -->
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" class="form-control" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
