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
    <div style="background-color: var(--accent-primary); color: white; text-align: center; padding: 10px; font-size: 0.95rem; font-weight: 500;">
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

                <!-- Theme Toggle -->
                <button id="themeToggle" class="theme-toggle" aria-label="Toggle Theme" style="margin-left: 10px;">
                    <!-- Sun Icon (shown in dark mode) -->
                    <svg id="themeIconSun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="display: none;"><path d="M12 18c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6zm0-10c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0-5c.55 0 1 .45 1 1v2c0 .55-.45 1-1 1s-1-.45-1-1V4c0-.55.45-1 1-1zm0 18c-.55 0-1-.45-1-1v-2c0-.55.45-1 1-1s1 .45 1 1v2c0 .55-.45 1-1 1zM5.64 7.05c-.39.39-1.02.39-1.41 0-.39-.39-.39-1.02 0-1.41l1.41-1.41c.39-.39 1.02-.39 1.41 0 .39.39.39 1.02 0 1.41L5.64 7.05zm12.72 12.72c-.39.39-1.02.39-1.41 0-.39-.39-.39-1.02 0-1.41l1.41-1.41c.39-.39 1.02-.39 1.41 0 .39.39.39 1.02 0 1.41l-1.41 1.41zM3 12c0-.55.45-1 1-1h2c.55 0 1 .45 1 1s-.45 1-1 1H4c-.55 0-1-.45-1-1zm18 0c0 .55-.45 1-1 1h-2c-.55 0-1-.45-1-1s.45-1 1-1h2c.55 0 1 .45 1 1zM7.05 18.36c.39-.39.39-1.02 0-1.41-.39-.39-1.02-.39-1.41 0l-1.41 1.41c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0l1.41-1.41zm12.72-12.72c.39-.39.39-1.02 0-1.41-.39-.39-1.02-.39-1.41 0l-1.41 1.41c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0l1.41-1.41z"/></svg>
                    <!-- Moon Icon (shown in light mode) -->
                    <svg id="themeIconMoon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9c0-.46-.04-.92-.1-1.36-.98 1.37-2.58 2.26-4.4 2.26-3.03 0-5.5-2.47-5.5-5.5 0-1.82.89-3.42 2.26-4.4-.44-.06-.9-.1-1.36-.1z"/></svg>
                </button>
            </div>
        </div>
    </header>
    <main>
