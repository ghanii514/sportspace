<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'SportSpace'; ?></title>
    <link rel="stylesheet" href="/css/style.css"> 
</head>
<body> <nav class="navbar">

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

            </form> <div class="nav-menu">
                <ul>
                    <li><a href="/">Beranda</a></li>
                    <li><a href="/promo">Promo</a></li>
                    <li><a href="/chat">Chat</a></li>
                    <li><a href="/riwayat">Riwayat</a></li>
                </ul>
            </div>
        </div>
        <div class="auth-area">
            <?php if (session()->get('logged_in')) : ?>

                <a href="/profile" class="signup-link">
                    Hi, <?= esc(session()->get('username')); ?>
                </a>

                <a href="/logout" class="login-prompt">Logout</a>

            <?php else : ?>
                <a href="/register" class="signup-link">Sign Up</a>
                <a href="/login" class="login-prompt">Sudah punya akun?</a>
            <?php endif; ?>
        </div>       

    </nav>
    <?= $this->renderSection('promo_banner'); ?>
    <main>
        <?= $this->renderSection('content'); ?>
    </main>

</main>

    <footer>
        <div class="footer-container">
            
            <div class="footer-col-copyright">
                &copy; <?= date('Y'); ?> SportSpace.com
            </div>

            <div class="footer-col-links">
                <div class="link-row">
                    <a href="/tentang">Tentang kami</a>
                    <a href="/bantuan">Bantuan</a>
                    <a href="/hubungi">Hubungi kami</a>
                </div>
                <div class="link-row">
                    <a href="/kebijakan">Kebijakan & Privasi</a>
                    <a href="/syarat">Syarat & Ketentuan</a>
                </div>
            </div>

            <div class="footer-col-social">
                <span>Ikuti kami</span>
                <a href="https://instagram.com" target="_blank" class="bi bi-instagram"></a>
                <a href="https://facebook.com" target="_blank" class="bi bi-facebook"></a>
            </div>

        </div>
    </footer>
    
</body>
</html>