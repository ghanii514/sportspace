<?php helper('auth'); ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= $title ?? 'SportSpace'; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaPpG1h5y2kB+Pv5jZnN9c8XG0qQp6BqXx4W2l5q7iCJv5zlj8v+0O8wS5m" crossorigin="anonymous">

    <!-- Bootstrap Icons (opsional, untuk ikon) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/style.css" />

    <style>
        /* ===== NAVBAR UTAMA ===== */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #3ee56b;
            padding: 10px 30px;
            height: 85px;
            box-sizing: border-box;
        }

        /* ===== LOGO AREA ===== */
        .logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-area img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .logo-text {
            font-size: 28px;
            font-weight: 800;
            color: #000;
            white-space: nowrap;
        }

        /* ===== SEARCH + MENU SECTION ===== */
        .search-nav-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        /* SEARCH BAR */
        .search-bar {
            position: relative;
            display: flex;
            align-items: center;
            background: #fff;
            padding: 7px 10px 7px 35px;
            border-radius: 8px;
            width: 350px;
            height: 10px;
        }

        .search-bar input {
            width: 350px;
            height: 10px;
            border: none;
            outline: none;
            font-size: 14px;
        }

        .search-icon {
            position: absolute;
            left: 10px;
            color: #777;
        }

        /* MENU NAVIGASI */
        .nav-menu {
            list-style: none;
            display: flex;
            gap: 25px;
            padding: 0;
            margin: 0;
        }

        .nav-menu li a {
            text-decoration: none;
            color: #000;
            font-weight: 600;
            font-size: 15px;
        }

        .nav-menu li a:hover {
            color: #005900;
        }

        /* ===== AUTH AREA ===== */
        .auth-area {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 5px;
        }

        .signup-link {
            font-weight: bold;
            text-decoration: none;
            color: #000;
        }

        .login-prompt {
            font-size: 13px;
            color: #333;
            text-decoration: none;
        }
    </style>
</head>

<style>
    /* ===== NAVBAR UTAMA ===== */
    .navbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #3ee56b;
        padding: 10px 30px;
        height: 85px;
        box-sizing: border-box;
    }

    /* ===== LOGO AREA ===== */
    .logo-area {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logo-area img {
        width: 70px;
        height: 70px;
        object-fit: contain;
    }

    .logo-text {
        font-size: 28px;
        font-weight: 800;
        color: #000;
        white-space: nowrap;
    }

    /* ===== SEARCH + MENU SECTION ===== */
    .search-nav-area {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    /* SEARCH BAR */
    .search-bar {
        position: relative;
        display: flex;
        align-items: center;
        background: #fff;
        padding: 7px 10px 7px 35px;
        border-radius: 8px;
        width: 350px;
        height: 10px;
    }

    .search-bar input {
        width: 350px;
        height: 10px;
        border: none;
        outline: none;
        font-size: 14px;
    }

    .search-icon {
        position: absolute;
        left: 10px;
        color: #777;
    }

    /* MENU NAVIGASI */
    .nav-menu {
        list-style: none;
        display: flex;
        gap: 25px;
        padding: 0;
        margin: 0;
    }

    .nav-menu li a {
        text-decoration: none;
        color: #000;
        font-weight: 600;
        font-size: 15px;
    }

    .nav-menu li a:hover {
        color: #005900;
    }

    /* ===== AUTH AREA ===== */
    .auth-area {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 5px;
    }

    .signup-link {
        font-weight: bold;
        text-decoration: none;
        color: #000;
    }

    .login-prompt {
        font-size: 13px;
        color: #333;
        text-decoration: none;
    }
</style>

<body>
    <nav class="navbar">
        <!-- LOGO AREA -->
        <div class="logo-area">
            <img src="<?= base_url('LOGO_SS.png') ?>" alt="Logo">
            <span class="logo-text">SportSpace.com</span>
        </div>

        <!-- SEARCH + MENU -->
        <div class="search-nav-area">

            <!-- SEARCH BAR -->
            <form action="/search" method="get" class="search-bar">
                <span class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input type="text" name="q" placeholder="Cari Lapangan..."
                    value="<?= esc(service('request')->getGet('q')); ?>">
            </form>

            <!-- MENU -->
            <ul class="nav-menu">
                <li><a href="/">Beranda</a></li>
                <li><a href="/promo">Promo</a></li>
                <li><a href="/chat">Chat</a></li>
                <li><a href="/riwayat">Riwayat</a></li>
            </ul>
        </div>

        <!-- AUTH AREA -->
        <div class="auth-area">
            <?php if (logged_in()): ?>
                <a href="/profile" class="signup-link">Hi, <?= esc(user()->username); ?></a>
                <a href="/logout" class="login-prompt">Logout</a>
            <?php else: ?>
                <a href="<?= url_to('register') ?>" class="signup-link">Sign Up</a>
                <a href="<?= url_to('login') ?>" class="login-prompt">Already have account?</a>
            <?php endif; ?>
        </div>
    </nav>


    <?= $this->renderSection('promo_banner'); ?>


    <?php if (logged_in()): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // 1. Siapkan data user yang sedang login saat ini
                const currentUser = {
                    id: "<?= user()->id ?>",
                    username: "<?= esc(user()->username) ?>",
                    email: "<?= esc(user()->email) ?>",
                    // Pastikan path gambarnya benar
                    image: "/img/user/<?= esc(user()->profile_picture ?? 'default_profile.jpg') ?>"
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