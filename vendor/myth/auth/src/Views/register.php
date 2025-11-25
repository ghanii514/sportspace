<?= $this->include('layout/template') ?>
<?= $this->section('main') ?>
<div class="auth-page-content">

    <h2 class="auth-title">SIGN UP</h2>
    <p class="auth-subtitle">SportSpace Account</p>

    <div class="auth-form-card">

        <!-- Notifikasi error/sukses dari Myth Auth -->
        <?= view('Myth\Auth\Views\_message_block') ?>

        <form action="<?= url_to('register') ?>" method="post" class="auth-form-inner">
            <?= csrf_field() ?>

            <!-- Email -->
            <label for="email">E-mail</label>
            <input type="email"
                   name="email"
                   id="email"
                   value="<?= old('email') ?>"
                   class="<?= session('errors.email') ? 'is-invalid' : '' ?>">

            <?php if (session('errors.email')) : ?>
                <div class="validation-errors">
                    <?= session('errors.email') ?>
                </div>
            <?php endif; ?>

            <!-- Username -->
            <label for="username">Username</label>
            <input type="text"
                   name="username"
                   id="username"
                   value="<?= old('username') ?>"
                   class="<?= session('errors.username') ? 'is-invalid' : '' ?>">

            <?php if (session('errors.username')) : ?>
                <div class="validation-errors">
                    <?= session('errors.username') ?>
                </div>
            <?php endif; ?>

            <!-- Password -->
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

            <!-- Konfirmasi Password -->
            <label for="pass_confirm">Verifikasi Password</label>
            <input type="password"
                   name="pass_confirm"
                   id="pass_confirm"
                   class="<?= session('errors.pass_confirm') ? 'is-invalid' : '' ?>">

            <?php if (session('errors.pass_confirm')) : ?>
                <div class="validation-errors">
                    <?= session('errors.pass_confirm') ?>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn-submit-green">Sign Up</button>
        </form>

        <div class="auth-bottom-links">
            <p>Sudah punya akun?  
                <a href="<?= url_to('login') ?>">Login</a>
            </p>
        </div>

    </div>
</div>

<?= $this->renderSection('main') ?>
<?= $this->include('layout/footer') ?>

