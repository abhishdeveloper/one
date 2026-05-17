<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<!-- Hero Section -->
<section class="hero">
    <div class="container hero-content">
        <h1>Transform Your Digital Vision Into Reality</h1>
        <p>Premium web & app development services that drive growth, innovation, and exceptional user experiences</p>
        <div class="hero-buttons">
            <a href="<?= URLROOT; ?>/pages/contact" class="btn btn-primary">Get Started</a>
            <a href="<?= URLROOT; ?>/pages/projects" class="btn btn-secondary">View Our Work</a>
        </div>
    </div>
</section>

<!-- Services Preview -->
<section class="section bg-light">
    <div class="container">
        <div class="section-title">
            <h2>Our Services</h2>
            <p>Comprehensive digital solutions tailored to your business needs</p>
        </div>
        <div class="grid-3">
            <?php foreach (array_slice($data['services'], 0, 3) as $service) : ?>
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
        <div class="text-center mt-2">
            <a href="<?= URLROOT; ?>/pages/services" class="btn btn-outline">View All Services</a>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>What Clients Say</h2>
            <p>Real feedback from real clients</p>
        </div>
        <div class="grid-3">
            <?php foreach ($data['testimonials'] as $testimonial) : ?>
                <div class="card testimonial-card">
                    <div class="avatar"><?= htmlspecialchars($testimonial->initials); ?></div>
                    <p class="feedback">"<?= htmlspecialchars($testimonial->feedback); ?>"</p>
                    <h4><?= htmlspecialchars($testimonial->client_name); ?></h4>
                    <span class="company"><?= htmlspecialchars($testimonial->company); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
