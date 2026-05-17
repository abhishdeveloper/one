<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section page-header">
    <div class="container">
        <h1>Our Services</h1>
        <p>Comprehensive digital solutions tailored to your business needs</p>
    </div>
</section>

<section class="section bg-light">
    <div class="container">
        <div class="grid-3">
            <?php foreach ($data['services'] as $service) : ?>
                <div class="card">
                    <h3><?= htmlspecialchars($service->title); ?></h3>
                    <p><?= htmlspecialchars($service->description); ?></p>
                    <ul class="feature-list">
                        <?php
                        $features = explode(',', $service->features);
                        foreach ($features as $feature) : ?>
                            <li><?= htmlspecialchars(trim($feature)); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
