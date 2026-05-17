<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section page-header">
    <div class="container">
        <h1>Featured Projects</h1>
        <p>Discover our latest work and success stories</p>
    </div>
</section>

<section class="section bg-light">
    <div class="container">
        <div class="grid-2">
            <?php foreach ($data['projects'] as $project) : ?>
                <div class="project-card">
                    <div class="project-image" style="background-image: url('<?= htmlspecialchars($project->image_url); ?>')"></div>
                    <div class="project-content">
                        <h3><?= htmlspecialchars($project->title); ?></h3>
                        <p class="category"><?= htmlspecialchars($project->category); ?></p>
                        <?php if ($project->description): ?>
                            <p><?= htmlspecialchars($project->description); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
