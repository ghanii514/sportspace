<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="auth-page-content">
    <h2 class="auth-title">SIGN IN</h2>
    <p class="auth-subtitle">SportSpace Account</p>

    <div class="auth-form-card"> <?php if (session()->getFlashdata('success')) : ?>
            <div class="flash-msg success">
                <?= session()->getFlashdata('success'); ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('msg')) : ?>
            <div class="flash-msg error">
                <?= session()->getFlashdata('msg'); ?>
            </div>
        <?php endif; ?>
        <form action="/login" method="post" class="auth-form-inner">
            <?= csrf_field(); ?>
            
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email">
            
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            
            <button type="submit" class="btn-submit-green">Sign In</button>
        </form>
    </div> <div class="auth-redirect-link">
        <a href="/register">Belum punya akun?</a>
    </div>
    </div>

<?= $this->endSection(); ?>