<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section bg-light" style="min-height: 80vh;">
    <div class="container">
        <div class="dashboard-layout">
            <?php $current = 'buy'; include 'sidebar.php'; ?>

            <div class="dashboard-content">
                <h2 style="margin-bottom: 20px;">Available Services</h2>

                <?php
                // Group services by category
                $groupedServices = [];
                foreach($data['available_services'] as $s) {
                    $groupedServices[$s->category][] = $s;
                }
                ?>

                <?php foreach($groupedServices as $category => $services): ?>
                    <h3 style="margin-top: 30px; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid var(--border-color); color: var(--accent-primary);"><?= htmlspecialchars($category); ?></h3>
                    <div class="grid-2">
                        <?php foreach($services as $service): ?>
                            <div class="card" style="display: flex; flex-direction: column;">
                                <h4 style="font-size: 1.2rem; margin-bottom: 10px;"><?= htmlspecialchars($service->title); ?></h4>
                                <div style="font-size: 1.8rem; font-weight: bold; color: white; margin-bottom: 15px;">
                                    ₹<?= number_format($service->price, 0); ?>
                                    <span style="font-size: 0.9rem; color: var(--text-muted); font-weight: normal;">/ <?= $service->billing_cycle; ?></span>
                                </div>
                                <p style="color: var(--text-muted); margin-bottom: 15px; flex-grow: 1;"><?= htmlspecialchars($service->description); ?></p>

                                <ul class="feature-list" style="margin-bottom: 20px;">
                                    <?php
                                    $features = explode(',', $service->features);
                                    foreach ($features as $feature) : ?>
                                        <li><?= htmlspecialchars(trim($feature)); ?></li>
                                    <?php endforeach; ?>
                                </ul>

                                <a href="<?= URLROOT; ?>/client/checkout/<?= $service->id; ?>" class="btn btn-primary text-center" style="width: 100%; margin-top: auto;">Order Now</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
