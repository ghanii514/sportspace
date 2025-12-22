<?= $this->section('content'); ?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* === GLOBAL VARIABLES & RESET === */
    :root {
        --primary-color: #10b981; /* Emerald Green */
        --primary-dark: #059669;
        --bg-color: #f3f4f6;
        --text-dark: #1f2937;
        --text-gray: #6b7280;
        --white: #ffffff;
        --sidebar-width: 280px;
    }

    body {
        font-family: 'Poppins', sans-serif; /* Font Baru */
        color: var(--text-dark);
        margin: 0;
        background-color: var(--bg-color);
    }

    .owner-container {
        display: flex;
        min-height: 100vh;
    }

    /* === SIDEBAR === */
    .sidebar {
        width: var(--sidebar-width);
        background: #cbffd3ff;
        padding: 30px 20px;
        border-right: 1px solid #49ff64ff;
        position: sticky;
        top: 0;
        height: 100vh;
        display: flex;
        flex-direction: column;
        box-shadow: 2px 0 5px rgba(0,0,0,0.02);
    }

    .owner-profile {
        text-align: center;
        padding-bottom: 25px;
        border-bottom: 1px solid #f3f4f6;
        margin-bottom: 25px;
    }

    .owner-img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
        border: 3px solid var(--primary-color);
        padding: 2px;
    }

    .owner-profile h4 {
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .menu-link {
        display: flex;
        align-items: center;
        padding: 14px 18px;
        margin-bottom: 8px;
        color: var(--text-gray);
        text-decoration: none;
        border-radius: 12px;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .menu-link:hover {
        background-color: #f0fdf4;
        color: var(--primary-dark);
        transform: translateX(3px);
    }

    .menu-link.active {
        background-color: #ecfdf5;
        color: var(--primary-dark);
        font-weight: 600;
    }

    /* === MAIN CONTENT === */
    .main-content {
        flex: 1;
        padding: 40px;
        overflow-y: auto;
    }

    h2 {
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 25px;
    }

    /* Kartu Ringkasan */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .card {
        background: var(--white);
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03); /* Soft Shadow */
        border: 1px solid #f0f0f0;
        transition: transform 0.2s;
        position: relative;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }

    .card p {
        margin: 0;
        color: var(--text-gray);
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 10px;
    }

    .card h3 {
        margin: 0;
        font-size: 2.2em;
        font-weight: 700;
        color: var(--text-dark);
    }

    /* Tabel yang lebih cantik */
    .table-container {
        background: var(--white);
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid #f0f0f0;
    }

    table {
        width: 100%;
        border-collapse: separate; /* Penting untuk border-radius */
        border-spacing: 0;
        margin-top: 20px;
    }

    th {
        text-align: left;
        padding: 15px;
        background: #f9fafb;
        color: var(--text-gray);
        font-size: 0.85em;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e5e7eb;
    }

    td {
        padding: 16px 15px;
        border-bottom: 1px solid #f3f4f6;
        color: var(--text-dark);
        font-size: 0.95em;
        vertical-align: middle;
    }

    tr:last-child td {
        border-bottom: none;
    }

    /* Status Badges */
    .badge {
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 0.75em;
        font-weight: 600;
        display: inline-block;
    }
    .bg-pending { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .bg-success { background: #ecfdf5; color: #047857; border: 1px solid #d1fae5; }
    .bg-cancel { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }

</style>

<div class="owner-container">
    
    <aside class="sidebar">
        <div class="owner-profile">
            <img src="/img/users/<?= $user->profile_picture ?? 'default_profile.jpg' ?>" class="owner-img">
            <h4 style="margin:0;"><?= esc(user()->username) ?></h4>
            <span style="font-size:0.85em; color:#888;">Pemilik Lapangan</span>
        </div>

        <nav>
            <a href="/owner" class="menu-link active">📊 Dashboard Utama</a>
            <a href="/owner/bookings" class="menu-link">📅 Daftar Booking</a>
            <a href="/owner/chat" class="menu-link">💬 Chat User</a>
            
            <hr style="border:0; border-top:1px solid #eee; margin: 20px 0;">
            <a href="/logout" class="menu-link" style="color:#ef4444;">🚪 Keluar</a>
        </nav>
    </aside>

    <main class="main-content">
        <h2 style="margin-top:0;">Ringkasan Lapangan</h2>

        <div class="stats-grid">
            <div class="card">
                <p>Total Booking Masuk</p>
                <h3><?= $total_booking ?></h3>
            </div>

            <div class="card" style="border-left: 5px solid #f59e0b;">
                <p>Menunggu Konfirmasi</p>
                <h3><?= $need_confirm ?></h3>
                <?php if($need_confirm > 0): ?>
                    <div style="margin-top:5px; font-size:0.8rem; color:#d97706; background:#fffbeb; padding:5px 10px; border-radius:6px; display:inline-block;">
                        ⚠️ Segera cek menu Booking!
                    </div>
                <?php endif; ?>
            </div>

            <div class="card">
                <p>Total Pendapatan</p>
                <h3 style="color:#10b981;">Rp <?= number_format($income, 0, ',', '.') ?></h3>
            </div>
        </div>

        <div class="table-container">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <h3 style="margin:0; font-size:1.2em; font-weight:600;">Booking Terakhir</h3>
                <a href="/owner/bookings" style="text-decoration:none; color:var(--primary-color); font-weight:600; font-size:0.9em; padding:8px 16px; background:#ecfdf5; border-radius:8px; transition:0.2s;">Lihat Semua &rarr;</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Penyewa</th>
                        <th>Lapangan</th>
                        <th>Tanggal Main</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($recent)): ?>
                        <?php foreach($recent as $r): ?>
                        <tr>
                            <td>
                                <strong><?= esc($r['penyewa']) ?></strong>
                            </td>
                            <td><?= esc($r['lapangan']) ?></td>
                            <td>
                                <div style="font-weight:500;"><?= date('d M Y', strtotime($r['booking_date'])) ?></div>
                                <div style="font-size:0.85em; color:#888;"><?= substr($r['start_time'], 0, 5) ?> - <?= substr($r['end_time'], 0, 5) ?></div>
                            </td>
                            <td>
                                <?php if($r['status'] == 'success'): ?>
                                    <span class="badge bg-success">Lunas</span>
                                <?php elseif($r['status'] == 'pending'): ?>
                                    <span class="badge bg-pending">Pending</span>
                                <?php else: ?>
                                    <span class="badge bg-cancel">Batal</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align:center; padding:40px; color:#9ca3af;">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="60" style="opacity:0.5; margin-bottom:10px;"><br>
                                Belum ada booking masuk hari ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>
</div>

<?= $this->renderSection('content'); ?>