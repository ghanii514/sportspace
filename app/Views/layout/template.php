<?php helper('auth'); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'SportSpace'; ?></title>
    <link rel="stylesheet" href="/css/style.css"> 
</head>
<body> 

    <nav class="navbar">
        <a href="/" class="logo-area">
            SportSpace.com
        </a>

        <div class="search-nav-area">
            <form action="/search" method="get" class="search-bar">
                <span class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input type="text" name="q" placeholder="Cari Lapangan..." value="<?= esc(service('request')->getGet('q')); ?>">
                <button type="submit" style="display: none;"></button>
            </form> 
            
            <div class="nav-menu">
                <ul>
                    <li><a href="/">Beranda</a></li>
                    <li><a href="/promo">Promo</a></li>
                    <li><a href="/chat">Chat</a></li>
                    <li><a href="/riwayat">Riwayat</a></li>
                </ul>
            </div>
        </div>

        <div class="auth-area">
            <?php if (logged_in()) : ?>
                <a href="/profile" class="signup-link">
                    Hi, <?= esc(user()->username); ?>
                </a>
                <a href="/logout" class="login-prompt">Logout</a>
            <?php else : ?>
                <a href="<?= url_to('register') ?>" class="signup-link">Sign Up</a>
                <a href="<?= url_to('login') ?>" class="login-prompt">Already have account?</a>
            <?php endif; ?>
        </div>    
    </nav>
    
    <?= $this->renderSection('promo_banner'); ?>
    

    <?php if (logged_in()) : ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Siapkan data user yang sedang login saat ini
            const currentUser = {
                id: "<?= user()->id ?>",
                username: "<?= esc(user()->username) ?>",
                email: "<?= esc(user()->email) ?>",
                // Pastikan path gambarnya benar
                image: "/img/users/<?= esc(user()->profile_picture ?? 'default_profile.jpg') ?>" 
            };

            // 2. Ambil data lama dari memori browser
            let accounts = JSON.parse(localStorage.getItem('sportspace_accounts') || '[]');

            // 3. Cek apakah user ini sudah ada di daftar?
            const existingIndex = accounts.findIndex(acc => acc.id == currentUser.id);

            if (existingIndex > -1) {
                // Kalau sudah ada, UPDATE datanya (misal user baru ganti foto profil)
                accounts[existingIndex] = currentUser;
            } else {
                // Kalau belum ada, TAMBAHKAN ke daftar
                accounts.push(currentUser);
            }

            // 4. Simpan kembali ke memori browser
            localStorage.setItem('sportspace_accounts', JSON.stringify(accounts));
        });
    </script>
    <?php endif; ?>

    <script>
        if (window.location.pathname.includes('/login')) {
            const prefillEmail = localStorage.getItem('prefill_email');
            if (prefillEmail) {
                const emailInput = document.querySelector('input[name="login"]');
                if (emailInput) {
                    emailInput.value = prefillEmail;
                    localStorage.removeItem('prefill_email'); 
                }
            }
        }
    </script>
    
</body>
</html>