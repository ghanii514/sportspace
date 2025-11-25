<?= $this->include('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    /* Semua input form agar ukurannya seragam */
    .auth-form-inner input {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 16px;
        margin-bottom: 14px;
        box-sizing: border-box;
    }

    /* Supaya label rapi */
    .auth-form-inner label {
        display: block;
        font-size: 15px;
        margin-bottom: 6px;
        font-weight: 500;
    }

    /* Tombol tetap hijau sesuai tone awal */
    .btn-submit-green {
        width: 100%;
        padding: 14px;
        border-radius: 10px;
        font-size: 17px;
        font-weight: bold;
        border: none;
        cursor: pointer;
    }
</style>


<div class="auth-page-content">
    <h2 class="auth-title">SIGN UP</h2>
    <p class="auth-subtitle">SportSpace Account</p>

    <div class="auth-form-card">

        <!-- Notifikasi sukses/gagal dari Myth Auth -->
        <?php if (session('msg')) : ?>
            <div class="validation-errors">
                <?= session('msg'); ?>
            </div>
        <?php endif; ?>

        <!-- Error validation MythAuth -->
        <?php if (session('errors')) : ?>
            <div class="validation-errors">
                <ul>
                    <?php foreach (session('errors') as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= url_to('register') ?>" method="post" class="auth-form-inner">
            <?= csrf_field(); ?>

            <label for="username">Username</label>
            <input type="text" name="username" id="username"
                value="<?= old('username') ?>">

            <label for="email">E-mail</label>
            <input type="email" name="email" id="email"
                value="<?= old('email') ?>">

            <label for="password">Password</label>
            <input type="password" name="password" id="password">

            <label for="pass_confirm">Verifikasi Password</label>
            <input type="password" name="pass_confirm" id="pass_confirm">

            <button type="submit" class="btn-submit-green">Sign Up</button>
        </form>
            <div class="auth-redirect-link">
        <a href="<?= url_to('auth/login') ?>">Sudah punya akun?</a>
    </div>
    </div>
    
</div>

<?= $this->renderSection('content') ?>
<?= $this->include('layout/footer') ?>
