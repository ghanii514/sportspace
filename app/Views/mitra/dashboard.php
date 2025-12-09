<?= $this->include('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    /* Container Utama */
    .mitra-container {
        display: flex;
        gap: 30px;
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
        min-height: 600px;
    }

    /* === SIDEBAR MENU (KIRI) === */
    .mitra-sidebar {
        width: 250px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        padding: 20px;
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .mitra-profile {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .mitra-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #39E07A;
        margin-bottom: 10px;
    }

    .menu-list { list-style: none; padding: 0; }
    .menu-item { margin-bottom: 8px; }
    
    .menu-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 15px;
        color: #64748b;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.3s;
    }
    
    .menu-link:hover, .menu-link.active {
        background: #e8fff0; /* Hijau muda banget */
        color: #39E07A; /* Hijau SportSpace */
    }

    /* === CONTENT AREA (KANAN) === */
    .mitra-content {
        flex: 1;
    }

    .page-title { margin-top: 0; margin-bottom: 25px; color: #1e293b; }

    /* STATS CARDS */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
    }

    .stat-label { color: #64748b; font-size: 0.9em; margin-bottom: 5px; }
    .stat-value { font-size: 1.8em; font-weight: 800; color: #1e293b; }
    .stat-value.green { color: #39E07A; }

    /* BOOKING TABLE */
    .booking-section {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .table-responsive { width: 100%; overflow-x: auto; }
    
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 15px; background: #f8fafc; color: #64748b; font-size: 0.9em; }
    td { padding: 15px; border-bottom: 1px solid #f1f5f9; color: #334155; }
    
    .badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8em;
        font-weight: bold;
    }
    .bg-paid { background: #dcfce7; color: #166534; }
    .bg-pending { background: #fef9c3; color: #854d0e; }

    .btn-action {
        padding: 6px 12px;
        background: #39E07A;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.9em;
    }
</style>

<div class="mitra-container">
    
    <aside class="mitra-sidebar">
        <div class="mitra-profile">
            <img src="/img/users/<?= esc($user->profile_picture ?? 'default_profile.jpg') ?>" class="mitra-avatar">
            <h3 style="margin:0; font-size:1.1em;"><?= esc($user->username) ?></h3>
            <span style="font-size:0.9em; color:#888;">Pemilik Lapangan</span>
        </div>

        <ul class="menu-list">
            <li class="menu-item">
                <a href="/mitra" class="menu-link active">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z"></path></svg>
                    Ringkasan
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
                    Kelola Lapangan
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Jadwal Booking
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 1v22m5-18H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z"></path></svg>
                    Laporan Keuangan
                </a>
            </li>
        </ul>
    </aside>

    <main class="mitra-content">
        <h1 class="page-title">Halo, <?= esc($user->username) ?>! 👋</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-label">Pendapatan Bulan Ini</span>
                <span class="stat-value green">Rp <?= number_format($income_month, 0, ',', '.') ?></span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Total Booking</span>
                <span class="stat-value"><?= $total_bookings ?></span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Rating Venue</span>
                <span class="stat-value">⭐ <?= $rating ?>/5.0</span>
            </div>
        </div>

        <div class="booking-section">
            <div class="section-header">
                <h3 style="margin:0;">Pesanan Terbaru</h3>
                <a href="#" style="color:#39E07A; text-decoration:none; font-weight:bold;">Lihat Semua ></a>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Penyewa</th>
                            <th>Lapangan</th>
                            <th>Jadwal Main</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recent_bookings as $booking): ?>
                        <tr>
                            <td>
                                <strong><?= $booking['nama'] ?></strong>
                            </td>
                            <td><?= $booking['lapangan'] ?></td>
                            <td>
                                <?= $booking['tanggal'] ?><br>
                                <span style="font-size:0.85em; color:#888;"><?= $booking['jam'] ?></span>
                            </td>
                            <td>
                                <?php if($booking['status'] == 'paid'): ?>
                                    <span class="badge bg-paid">Lunas</span>
                                <?php else: ?>
                                    <span class="badge bg-pending">Menunggu</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="#" class="btn-action">Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</div>

<?= $this->include('layout/footer'); ?>