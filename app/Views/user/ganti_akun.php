<?= $this->include('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    body { background-color: #f4f7f6; }
    .switch-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
    }
    .switch-card {
        background-color: #fff;
        width: 100%;
        max-width: 450px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        position: relative;
        padding-bottom: 20px;
    }
    .switch-header {
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .switch-title {
        font-size: 1.2em;
        font-weight: 700;
        color: #333;
        margin: 0;
    }
    .btn-close-modal {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #999;
        font-size: 1.5em;
        line-height: 1;
        text-decoration: none;
    }
    .btn-close-modal:hover { color: #333; }
    .account-list { display: flex; flex-direction: column; }
    .account-item {
        display: flex;
        align-items: center;
        padding: 15px 25px;
        border-bottom: 1px solid #f9f9f9;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
    }
    .account-item:hover { background-color: #fcfcfc; }
    .acc-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
        border: 1px solid #eee;
    }
    .acc-info { flex: 1; }
    .acc-username { display: block; font-weight: 700; color: #333; font-size: 1em; }
    .acc-email { display: block; font-size: 0.85em; color: #888; }
    
    .radio-indicator {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .account-item.active .radio-indicator {
        border-color: #2ECC71;
        background-color: #2ECC71;
    }
    .account-item.active .radio-indicator::after {
        content: "✓";
        color: white;
        font-size: 0.8em;
        font-weight: bold;
    }

    .add-account-btn {
        display: flex;
        align-items: center;
        padding: 15px 25px;
        color: #2ECC71;
        text-decoration: none;
        font-weight: 600;
        margin-top: 5px;
        transition: background 0.2s;
    }
    .add-account-btn:hover { background-color: #f0fdf4; }
    .plus-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 1.5em;
        border: 1px dashed #2ECC71;
        border-radius: 50%;
        color: #2ECC71;
    }
</style>

<div class="switch-wrapper">
    <div class="switch-card">
        
        <div class="switch-header">
            <h3 class="switch-title">Pilih Akun</h3>
            <a href="/profile" class="btn-close-modal">&times;</a>
        </div>

        <div id="accountListContainer" class="account-list"></div>

        <a href="/ganti-akun/tambah" class="add-account-btn">
            <div class="plus-icon">+</div>
            <div>Tambahkan akun lain</div>
        </a>

    </div>
</div>

<script>
    // 1. Ambil ID User saat ini dari PHP
    const currentUserId = <?= json_encode($currentUserId); ?>;
    
    // 2. Ambil data lama dari LocalStorage
    let savedAccounts = JSON.parse(localStorage.getItem('sportspace_accounts') || '[]');

    // ============================================================
    // [FIX] KITA INJECT DATA USER SAAT INI LANGSUNG DARI PHP
    // Agar tidak perlu nunggu refresh atau nunggu script footer jalan
    // ============================================================
    <?php if (logged_in()) : ?>
        const currentUserNow = {
            id: "<?= user()->id ?>",
            username: "<?= esc(user()->username) ?>",
            email: "<?= esc(user()->email) ?>",
            image: "/img/users/<?= esc(user()->profile_picture ?? 'default_profile.jpg') ?>"
        };

        // Cek apakah user ini sudah ada di list?
        const existingIdx = savedAccounts.findIndex(acc => acc.id == currentUserNow.id);

        if (existingIdx > -1) {
            // Kalau ada, update datanya (siapa tau ganti foto)
            savedAccounts[existingIdx] = currentUserNow;
        } else {
            // Kalau belum ada (LOGIN BARU), masukkan paksa ke array sekarang juga!
            savedAccounts.push(currentUserNow);
        }

        // Update localStorage biar sinkron
        localStorage.setItem('sportspace_accounts', JSON.stringify(savedAccounts));
    <?php endif; ?>
    // ============================================================


    // 3. Render Tampilan (Sama seperti sebelumnya)
    const listContainer = document.getElementById('accountListContainer');
    listContainer.innerHTML = ''; 

    if (savedAccounts.length === 0) {
        listContainer.innerHTML = '<div style="padding:20px; text-align:center; color:#999;">Belum ada riwayat akun.</div>';
    } else {
        savedAccounts.forEach(acc => {
            // Cek akun aktif (bandingkan ID)
            // Gunakan '==' agar aman (string vs int)
            const isActive = (currentUserId && acc.id == currentUserId); 
            const activeClass = isActive ? 'active' : '';
            
            // Link Logic: Jika aktif -> diam (#). Jika tidak -> ke login prefill.
            const linkAction = isActive ? '#' : '/login?prefill_email=' + encodeURIComponent(acc.email); 

            const html = `
                <a href="${linkAction}" class="account-item ${activeClass}" onclick="handleSwitch(event, ${isActive})">
                    <img src="${acc.image}" class="acc-avatar" alt="${acc.username}">
                    
                    <div class="acc-info">
                        <span class="acc-username">${acc.username}</span>
                        <span class="acc-email">${acc.email}</span>
                    </div>

                    <div class="radio-indicator"></div>
                </a>
            `;
            listContainer.innerHTML += html;
        });
    }

    function handleSwitch(e, isActive) {
        if(isActive) {
            e.preventDefault(); // Jangan refresh kalau klik akun sendiri
        }
    }
</script>

<?= $this->renderSection('content'); ?>
<?= $this->include('layout/footer'); ?>