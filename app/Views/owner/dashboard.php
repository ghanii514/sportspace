<?= $this->include('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    /* Layout Utama */
    .owner-container {
        display: flex;
        min-height: 100vh;
        background-color: #f3f4f6;
    }

    /* === SIDEBAR === */
    .sidebar {
        width: 260px;
        background: #fff;
        padding: 20px;
        border-right: 1px solid #e5e7eb;
        position: sticky;
        top: 0;
        height: 100vh;
    }

    .owner-profile {
        text-align: center;
        padding-bottom: 20px;
        border-bottom: 1px solid #f3f4f6;
        margin-bottom: 20px;
    }

    .owner-img {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
        border: 2px solid #39E07A;
    }

    .menu-link {
        display: block;
        padding: 12px 15px;
        margin-bottom: 8px;
        color: #4b5563;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.2s;
    }

    .menu-link:hover, .menu-link.active {
        background-color: #ecfdf5;
        color: #059669; /* Hijau agak gelap */
    }

    /* === CONTENT === */
    .main-content {
        flex: 1;
        padding: 30px;
    }

    /* Kartu Ringkasan */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .card h3 { margin: 0; font-size: 2em; color: #1f2937; }
    .card p { margin: 0; color: #6b7280; font-size: 0.9em; }

    /* Tabel */
    .table-container {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th { text-align: left; padding: 12px; background: #f9fafb; color: #6b7280; font-size: 0.85em; }
    td { padding: 12px; border-bottom: 1px solid #f3f4f6; color: #374151; font-size: 0.95em; }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75em;
        font-weight: bold;
    }
    .bg-pending { background: #fef3c7; color: #92400e; }
    .bg-success { background: #d1fae5; color: #065f46; }
    .bg-cancel { background: #fee2e2; color: #991b1b; }
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
            
            <hr style="border:0; border-top:1px solid #eee; margin: 15px 0;">
            <a href="/logout" class="menu-link" style="color:#ef4444;">Keluar</a>
        </nav>
    </aside>

    <main class="main-content">
        <h2 style="margin-top:0; margin-bottom:20px;">Ringkasan Lapangan</h2>

        <div class="stats-grid">
            <div class="card">
                <p>Total Booking Masuk</p>
                <h3><?= $total_booking ?></h3>
            </div>

            <div class="card" style="border-left: 5px solid #f59e0b;">
                <p>Menunggu Konfirmasi</p>
                <h3><?= $need_confirm ?></h3>
                <?php if($need_confirm > 0): ?>
                    <small style="color:#d97706;">Segera cek menu Booking!</small>
                <?php endif; ?>
            </div>

            <div class="card">
                <p>Total Pendapatan</p>
                <h3 style="color:#059669;">Rp <?= number_format($income, 0, ',', '.') ?></h3>
            </div>
        </div>

        <div class="table-container">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <h3 style="margin:0; font-size:1.2em;">5 Booking Terakhir</h3>
                <a href="/owner/bookings" style="text-decoration:none; color:#39E07A; font-weight:bold; font-size:0.9em;">Lihat Semua ></a>
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
                                <?= date('d M Y', strtotime($r['booking_date'])) ?><br>
                                <small><?= substr($r['start_time'], 0, 5) ?> - <?= substr($r['end_time'], 0, 5) ?></small>
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
                            <td colspan="4" style="text-align:center; padding:20px; color:#888;">
                                Belum ada booking masuk.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>
</div>

<?= $this->renderSection('content'); ?>
<?= $this->include('layout/footer'); ?>