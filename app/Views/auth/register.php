<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="auth-page-content">
    <h2 class="auth-title">SIGN UP</h2>
    <p class="auth-subtitle">SportSpace Account</p>

    <div class="auth-form-card">
        
        <?php if (isset($validation)) : ?>
            <div class="validation-errors">
                <?= $validation->listErrors(); ?>
            </div>
        <?php endif; ?>

        <form action="/register" method="post" class="auth-form-inner">
            <?= csrf_field(); ?>
            
            <label for="username">Username</label>
            <input type="username" name="username" id="username" value="<?= set_value('username'); ?>">

            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" value="<?= set_value('email'); ?>">
            
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            
            <label for="pass_confirm">Verifikasi Password</label>
            <input type="password" name="pass_confirm" id="pass_confirm">
            
            <button type="submit" class="btn-submit-green">Sign Up</button>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>
