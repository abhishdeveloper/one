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
    <?php if (isset($data['settings']['announcement_active']) && $data['settings']['announcement_active'] == '1' && !empty($data['settings']['announcement_text'])): ?>
    <div style="background-color: var(--primary-color); color: white; text-align: center; padding: 10px; font-size: 0.95rem; font-weight: 500;">
        <?= htmlspecialchars($data['settings']['announcement_text']); ?>
    </div>
    <?php endif; ?>

    <header class="navbar">
        <div class="container nav-container">
            <a href="<?= URLROOT; ?>" class="brand"><?= $data['settings']['site_name'] ?? SITENAME; ?></a>
            <nav>
                <ul class="nav-links">
                    <li><a href="<?= URLROOT; ?>/index.php?url=pages/index">Home</a></li>
                    <li><a href="<?= URLROOT; ?>/index.php?url=pages/services">Services</a></li>
                    <li><a href="<?= URLROOT; ?>/index.php?url=pages/projects">Projects</a></li>
                    <li><a href="<?= URLROOT; ?>/index.php?url=pages/about">About</a></li>
                    <li><a href="<?= URLROOT; ?>/index.php?url=pages/contact">Contact</a></li>
                </ul>
            </nav>
            <div class="auth-links" style="margin-left: 20px;">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="<?= URLROOT; ?>/index.php?url=<?= $_SESSION['user_role'] == 'admin' ? 'admin' : 'client'; ?>/index" class="btn btn-outline" style="padding: 8px 16px;">Dashboard</a>
                    <a href="<?= URLROOT; ?>/index.php?url=auth/logout" class="btn btn-primary" style="padding: 8px 16px; margin-left: 10px;">Logout</a>
                <?php else: ?>
                    <a href="<?= URLROOT; ?>/index.php?url=auth/login" class="btn btn-outline" style="padding: 8px 16px;">Login</a>
                    <a href="<?= URLROOT; ?>/index.php?url=auth/register" class="btn btn-primary" style="padding: 8px 16px; margin-left: 10px;">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main>
