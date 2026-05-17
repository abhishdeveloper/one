<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['settings']['site_name'] ?? SITENAME; ?></title>
    <link rel="stylesheet" href="<?= URLROOT; ?>/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="navbar">
        <div class="container nav-container">
            <a href="<?= URLROOT; ?>" class="brand"><?= $data['settings']['site_name'] ?? SITENAME; ?></a>
            <nav>
                <ul class="nav-links">
                    <li><a href="<?= URLROOT; ?>/pages/index">Home</a></li>
                    <li><a href="<?= URLROOT; ?>/pages/services">Services</a></li>
                    <li><a href="<?= URLROOT; ?>/pages/projects">Projects</a></li>
                    <li><a href="<?= URLROOT; ?>/pages/about">About</a></li>
                    <li><a href="<?= URLROOT; ?>/pages/contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
