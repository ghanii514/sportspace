    <!-- === SIDE BAR (SAMA PERSIS DENGAN DASHBOARD) === -->
    <aside class="sidebar">
        <div class="owner-profile">
            <img src="/img/fields/<?= $venue_image ?>" class="owner-img">
            <h4 style="margin:0;"><?= esc($venue_names) ?></h4>
            <span style="font-size:0.85em; color:#888;">Pemilik Lapangan</span>
        </div>

        <nav>
            <a href="/owner" class="menu-link">Dashboard Utama</a>
            <a href="/owner/bookings" class="menu-link">Daftar Booking</a>
            <a href="/owner/chat" class="menu-link active">Chat User</a>
            
            <hr style="border:0; border-top:1px solid #eee; margin: 15px 0;">
            <a href="/logout" class="menu-link" style="color:#ef4444;">Keluar</a>
        </nav>
    </aside>