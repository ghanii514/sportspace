<?= $this->include('layout/template') ?>
<?= $this->section('content'); ?>

<div class="auth-page-content">

    <h2 class="auth-title"><?= lang('Auth.forgotPassword') ?></h2>
    <p class="auth-subtitle">Reset Password Akun Anda</p>

    <div class="auth-form-card">

        <!-- Notifikasi Myth Auth -->
        <?= view('Myth\Auth\Views\_message_block') ?>

        <p><?= lang('Auth.enterEmailForInstructions') ?></p>

        <form action="<?= url_to('forgot') ?>" method="post" class="auth-form-inner">
            <?= csrf_field() ?>

            <!-- Email -->
            <label for="email" style="color:white;"><?= lang('Auth.emailAddress') ?></label>
            <input type="email"
                   name="email"
                   id="email"
                   class="<?= session('errors.email') ? 'is-invalid' : '' ?>"
                   placeholder="<?= lang('Auth.email') ?>">

            <?php if (session('errors.email')) : ?>
                <div class="validation-errors">
                    <?= session('errors.email') ?>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn-submit-green"> <?= lang('Auth.sendInstructions') ?> </button>
        </form>

        <div class="auth-bottom-links">
            <p>
                <a href="<?= url_to('login') ?>" style="color:#a7f3d0;">← Kembali ke Halaman Login</a>
            </p>
        </div>

    </div>

</div>



<?= $this->renderSection('main') ?>
<?= $this->include('layout/footer') ?>