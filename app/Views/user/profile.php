<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="profile-container">
    <h1 class="profile-title">Profil Saya</h1>

    <div class="profile-card">
        <form action="/profile/update-picture" method="post" enctype="multipart/form-data" id="profilePictureForm">
            <?= csrf_field(); ?>
                <img src="/img/user/<?= esc($user['profile_picture'] ?? 'default_profile.jpg'); ?>" alt="Foto Profil" class="profile-pic">
                        <label for="profile_picture_upload" class="profile-pic-edit">
                        [Ubah]
                        </label>
                    <input type="file" name="profile_picture" id="profile_picture_upload" style="display: none;" onchange="document.getElementById('profilePictureForm').submit();">
                </form>

            <div class="profile-name"><?= esc($user['username']); ?></div>
        <div class="profile-email"><?= esc($user['email']); ?></div>
    </div>

    <div class="profile-menu">
        <div class="profile-menu-section">
            <div class="menu-title">AKUN SAYA</div>
            <a href="#" class="menu-item">Edit Profile</a>
            <a href="#" class="menu-item">Ubah Password</a>
        </div>

        <div class="profile-menu-section">
            <div class="menu-title">AKTIVITAS AKUN</div>
            <a href="/riwayat" class="menu-item">Riwayat Pemesanan</a>
            <a href="#" class="menu-item">Lapangan Favorit</a>
        </div>

        <div class="profile-menu-section">
            <div class="menu-title">BANTUAN</div> <a href="#" class="menu-item">Pusat Bantuan</a>
            <a href="#" class="menu-item">Hubungi Kami</a>
        </div>
    </div>

    <div class="profile-buttons">
        <a href="#" class="btn-ganti-akun">Ganti akun</a>
        <a href="/logout" class="btn-logout">Keluar / Logout</a>
    </div>

</div>

<?= $this->endSection(); ?>