<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="auth-page-content">
    <h2 class="auth-title">FORGOT PASSWORD</h2>
    <p class="auth-subtitle">Reset akses akun Anda</p>

    <div class="auth-form-card">

        <!-- Notifikasi sukses -->
        <?php if (session('message')) : ?>
            <div class="validation-success">
                <?= session('message') ?>
            </div>
        <?php endif; ?>

        <!-- Error dari Myth/Auth -->
        <?php if (session('errors')) : ?>
            <div class="validation-errors">
                <ul>
                    <?php foreach (session('errors') as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= url_to('auth/forgot') ?>" method="post" class="auth-form-inner">
            <?= csrf_field() ?>

            <label for="email">E-mail</label>
            <input type="email" name="email" id="email"
                value="<?= old('email') ?>">

            <button type="submit" class="btn-submit-green">Kirim Reset Link</button>
        </form>
    </div>
</div>

<?= $this->renderSection('content') ?>
<?= $this->include('layout/footer') ?>