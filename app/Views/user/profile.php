<?= $this->include('layout/template'); ?>

<?= $this->section('content'); ?>


<div class="profile-container">
    <h1 class="profile-title">Profil Saya</h1>

    <div class="profile-card">
        <form action="/profile/update-picture" method="post" enctype="multipart/form-data" id="profilePictureForm">
            <?= csrf_field(); ?>
            <div class="profile-pic-wrapper">
                <img src="/img/user/<?= esc($user->profile_picture ?? 'default_profile.jpg'); ?>" alt="Foto Profil" class="profile-pic">
                <label for="profile_picture_upload" class="profile-pic-edit">[Ubah]</label>
                <input type="file" name="profile_picture" id="profile_picture_upload" style="display: none;" onchange="document.getElementById('profilePictureForm').submit();">
            </div>
        </form>
        <div class="profile-name"><?= esc($user->username); ?></div>
        <div class="profile-email"><?= esc($user->email); ?></div>
    </div>

    <div class="profile-menu-section">
        <div class="menu-title">AKUN SAYA</div>
        <a href="/profile/edit" class="menu-item">Edit Profile</a>
        <a href="/forgot" class="menu-item">Ubah Password</a>
    </div>

    <div class="profile-menu-section">
        <div class="menu-title">AKTIVITAS AKUN</div>
        <a href="/riwayat" class="menu-item">Riwayat Pemesanan</a>
        <a href="#" class="menu-item">Lapangan Favorit</a>
    </div>

    <div class="profile-menu-section">
        <div class="menu-title">BANTUAN</div> 
        <a href="#" class="menu-item">Pusat Bantuan</a>
        <a href="#" class="menu-item">Hubungi Kami</a>
    </div>

    <div class="profile-buttons">
        <a href="/ganti-akun" class="btn-action btn-ganti">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            Ganti Akun
        </a>
        <a href="/logout" class="btn-action btn-logout">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
            Keluar
        </a>
    </div>

</div>


<?= $this->renderSection('content'); ?>
<?= $this->include('layout/footer'); ?>