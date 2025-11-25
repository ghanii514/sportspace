<?= $this->include('layout/template') ?>
<?= $this->section('content'); ?>

<div class="auth-page-content">
    <h2 class="auth-title">SIGN IN</h2>
    <p class="auth-subtitle">SportSpace Account</p>

    <div class="auth-form-card">

        <!-- Pesan dari MythAuth -->
        <?= view('Myth\Auth\Views\_message_block') ?>

        <form action="<?= url_to('login') ?>" method="post" class="auth-form-inner">
            <?= csrf_field() ?>

            <label for="login">E-mail atau Username</label>
            <input type="text"
                   name="login"
                   id="login"
                   value="<?= old('login') ?>"
                   class="<?= session('errors.login') ? 'is-invalid' : '' ?>">

            <?php if (session('errors.login')) : ?>
                <div class="validation-errors">
                    <?= session('errors.login') ?>
                </div>
            <?php endif; ?>

            <label for="password">Password</label>
            <input type="password"
                   name="password"
                   id="password"
                   class="<?= session('errors.password') ? 'is-invalid' : '' ?>">

            <?php if (session('errors.password')) : ?>
                <div class="validation-errors">
                    <?= session('errors.password') ?>
                </div>
            <?php endif; ?>

            <?php if ($config->allowRemembering): ?>
                <div class="remember-area">
                    <input type="checkbox" name="remember" id="remember"
                        <?= old('remember') ? 'checked' : '' ?>>
                    <label for="remember">Ingat Saya</label>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn-submit-green">Login</button>
        </form>

        <div class="auth-bottom-links">
            <?php if ($config->allowRegistration): ?>
                <p><a href="<?= url_to('register') ?>">Belum punya akun?</a></p>
            <?php endif; ?>

            <?php if ($config->activeResetter): ?>
                <p><a href="<?= url_to('forgot') ?>">Lupa Password?</a></p>
            <?php endif; ?>
        </div>

    </div>
</div>

<?= $this->renderSection('main') ?>
<?= $this->include('layout/footer') ?>
