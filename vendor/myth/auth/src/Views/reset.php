<?= $this->include('layout/template') ?>
<?= $this->section('main') ?>
<div class="auth-page-content">
    <h2 class="auth-title"><?= lang('Auth.resetYourPassword') ?></h2>
    <p class="auth-subtitle"><?= lang('Auth.enterCodeEmailPassword') ?></p>

    <div class="auth-form-card">

        <?= view('Myth\Auth\Views\_message_block') ?>

        <form action="<?= url_to('reset-password') ?>" method="post" class="auth-form-inner">
            <?= csrf_field() ?>

            <!-- TOKEN -->
            <label for="token"><?= lang('Auth.token') ?></label>
            <input type="text" 
                   name="token" 
                   id="token"
                   class="<?php if (session('errors.token')) : ?>is-invalid<?php endif ?>"
                   value="<?= old('token', $token ?? '') ?>">

            <?php if (session('errors.token')) : ?>
                <div class="validation-errors">
                    <?= session('errors.token') ?>
                </div>
            <?php endif ?>

            <!-- EMAIL -->
            <label for="email"><?= lang('Auth.email') ?></label>
            <input type="email" 
                   name="email" 
                   id="email"
                   class="<?php if (session('errors.email')) : ?>is-invalid<?php endif ?>"
                   value="<?= old('email') ?>">

            <?php if (session('errors.email')) : ?>
                <div class="validation-errors">
                    <?= session('errors.email') ?>
                </div>
            <?php endif ?>

            <!-- PASSWORD -->
            <label for="password"><?= lang('Auth.newPassword') ?></label>
            <input type="password" 
                   name="password" 
                   id="password"
                   class="<?php if (session('errors.password')) : ?>is-invalid<?php endif ?>">

            <?php if (session('errors.password')) : ?>
                <div class="validation-errors">
                    <?= session('errors.password') ?>
                </div>
            <?php endif ?>

            <!-- CONFIRM PASSWORD -->
            <label for="pass_confirm"><?= lang('Auth.newPasswordRepeat') ?></label>
            <input type="password" 
                   name="pass_confirm" 
                   id="pass_confirm"
                   class="<?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>">

            <?php if (session('errors.pass_confirm')) : ?>
                <div class="validation-errors">
                    <?= session('errors.pass_confirm') ?>
                </div>
            <?php endif ?>

            <button type="submit" class="btn-submit-green">
                <?= lang('Auth.resetPassword') ?>
            </button>

        </form>
    </div>
</div>


<?= $this->renderSection('main') ?>
<?= $this->include('layout/footer') ?>
