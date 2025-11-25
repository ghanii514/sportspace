<?= $this->include('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="auth-page-content">
    <h2 class="auth-title">SIGN IN</h2>
    <p class="auth-subtitle">SportSpace Account</p>

    <div class="auth-form-card">

        <!-- Flash Message -->
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="flash-msg success">
                <?= session()->getFlashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('message')) : ?>
            <div class="flash-msg error">
                <?= session()->getFlashdata('message'); ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="flash-msg error">
                <?= session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>

        <!-- FORM LOGIN -->
        <form action="<?= url_to('login') ?>" method="post" class="auth-form-inner">
            <?= csrf_field() ?>

            <!-- Email -->
            <label for="login">E-mail</label>
            <input type="email" name="login" id="login"
                   value="<?= old('login') ?>" placeholder="Masukkan email">

            <!-- Password -->
            <label for="password">Password</label>
            <input type="password" name="password" id="password"
                   placeholder="Masukkan password">

            <!-- Remember Me (optional) -->
            <div class="remember-section">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Ingat Saya</label>
            </div>

            <button type="submit" class="btn-submit-green">Sign In</button>
        </form>
        <div class="auth-redirect-link">
            <a href="<?= url_to('auth/register') ?>">Belum punya akun?</a>
        </div>
        <div class="auth-redirect-link">
            <a href="<?= url_to('auth/forgot') ?>">Lupa Password?</a>
        </div>
    </div>

</div>

<?= $this->renderSection('content') ?>
<?= $this->include('layout/footer') ?>
