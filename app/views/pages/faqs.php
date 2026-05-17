<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section page-header">
    <div class="container">
        <h1>Frequently Asked Questions</h1>
        <p>Find answers to common questions about our services</p>
    </div>
</section>

<section class="section bg-light">
    <div class="container">
        <div class="faqs-list">
            <?php foreach ($data['faqs'] as $faq) : ?>
                <div class="faq-item card mt-1">
                    <h3><?= htmlspecialchars($faq->question); ?></h3>
                    <p><?= htmlspecialchars($faq->answer); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
