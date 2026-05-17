<?php require_once APPROOT . '/app/views/partials/header.php'; ?>

<section class="section bg-light" style="min-height: 80vh; display: flex; align-items: center;">
    <div class="container" style="max-width: 500px; margin: 0 auto;">
        <div class="card">
            <h2 class="text-center" style="margin-bottom: 20px;">Create an Account</h2>

            <form action="<?= URLROOT; ?>/index.php?url=auth/register" method="post">
                <input type="hidden" name="csrf_token" value="<?= Security::generateCsrfToken(); ?>">

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?= $data['name']; ?>">
                    <span style="color: #ef4444; font-size: 0.85rem;"><?= $data['name_err']; ?></span>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= $data['email']; ?>">
                    <span style="color: #ef4444; font-size: 0.85rem;"><?= $data['email_err']; ?></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control">
                    <span style="color: #ef4444; font-size: 0.85rem;"><?= $data['password_err']; ?></span>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control">
                    <span style="color: #ef4444; font-size: 0.85rem;"><?= $data['confirm_password_err']; ?></span>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Register</button>
            </form>

            <div style="text-align: center; margin: 20px 0;">
                <span style="color: var(--text-muted);">OR</span>
            </div>

            <a href="<?= URLROOT; ?>/auth/google" class="btn btn-secondary" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px;">
                <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/><path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/><path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/><path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/></svg>
                Sign up with Google
            </a>

            <div style="text-align: center; margin-top: 20px;">
                <p>Already have an account? <a href="<?= URLROOT; ?>/auth/login">Login</a></p>
            </div>
        </div>
    </div>
</section>

<?php require_once APPROOT . '/app/views/partials/footer.php'; ?>
