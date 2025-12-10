<?= $this->include('layout/template') ?>
<?= $this->section('main') ?>

<div class="container py-5">

    <div class="hero-box mb-5"></div>
    <h1>Tentang Kami</h1>
    <img src="/img/fields/class="table-img" alt="Foto">
    <!-- Visi Misi -->
    <h3 class="fw-bold">Visi Misi</h3>
    <p class="mt-3">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </p>

    <!-- Tujuan -->
    <h3 class="fw-bold mt-5">Tujuan</h3>
    <p class="mt-3">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </p>

</div>
<?= $this->renderSection('main') ?>
<?= $this->include('layout/footer') ?>
