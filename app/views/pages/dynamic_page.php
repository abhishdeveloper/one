<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section page-header">
    <div class="container">
        <h1><?= htmlspecialchars($data['page']->title); ?></h1>
    </div>
</section>

<section class="section bg-light">
    <div class="container content-area">
        <?= $data['page']->content; ?> <!-- Content usually contains HTML like paragraphs and headers -->
    </div>
</section>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
