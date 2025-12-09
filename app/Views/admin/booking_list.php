<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cek Booking - Admin</title>
    <style>
        /* (Style dasar sama kayak sebelumnya, copy dari promos_list.php) */
        body { font-family: sans-serif; margin: 0; display: flex; height: 100vh; background: #f4f6f9; }
        .sidebar { width: 250px; background: #1e293b; color: white; padding: 20px; display: flex; flex-direction: column; }
        .menu-link { text-decoration: none; color: #cbd5e1; padding: 12px 15px; border-radius: 8px; margin-bottom: 5px; display: block; transition:0.2s; }
        .menu-link:hover, .menu-link.active { background: #334155; color: #39E07A; font-weight: bold; }
        .logout-btn { margin-top: auto; background: #e11d48; color: white; text-align: center; }
        .content { flex: 1; padding: 30px; overflow-y: auto; }

        /* TABEL */
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; vertical-align: middle; }
        th { background: #f8fafc; color: #475569; font-weight: 700; font-size: 0.85em; text-transform: uppercase; }
        tr:hover { background-color: #fcfcfc; }

        /* BADGE STATUS */
        .badge { padding: 6px 10px; border-radius: 20px; font-size: 0.75em; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        .bg-pending { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }
        .bg-paid { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .bg-cancelled { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* TOMBOL AKSI KECIL */
        .btn-mini { padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 0.8em; font-weight: bold; display: inline-block; margin-right: 3px; }
        .btn-acc { background: #39E07A; color: white; }
        .btn-reject { background: #ef4444; color: white; }
        .btn-trash { background: #94a3b8; color: white; }
        
        .alert-success { background: #dcfce7; color: #166534; padding: 15px; margin-bottom: 20px; border-radius: 8px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2 style="margin-top:0; color:#39E07A; text-align:center;">SportSpace Admin</h2>
        <a href="/admin" class="menu-link">Dashboard</a>
        <a href="/admin/fields" class="menu-link">Kelola Lapangan</a>
        <a href="/admin/promos" class="menu-link">Kelola Promo</a>
        <a href="/admin/bookings" class="menu-link active">Cek Booking</a>
        <a href="/" class="menu-link" style="margin-top:30px; border-top:1px solid #334155;">&larr; Lihat Website</a>
        <a href="/logout" class="menu-link logout-btn">Logout</a>
    </div>

    <div class="content">
        <h1 style="color: #1e293b; margin-top: 0;">Data Transaksi Booking</h1>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Penyewa</th>
                    <th>Detail Jadwal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($bookings)): ?>
                    <?php foreach($bookings as $row): ?>
                    <tr>
                        <td style="color:#888;">#<?= $row['id'] ?></td>
                        <td>
                            <strong><?= esc($row['username']) ?></strong><br>
                            <small style="color:#64748b;"><?= esc($row['email']) ?></small>
                        </td>
                        <td>
                            <strong><?= esc($row['nama_lapangan']) ?></strong><br>
                            <?= date('d M Y', strtotime($row['booking_date'])) ?> | 
                            <?= substr($row['start_time'], 0, 5) ?> - <?= substr($row['end_time'], 0, 5) ?>
                        </td>
                        <td style="font-weight:bold;">
                            Rp <?= number_format($row['total_price'], 0, ',', '.') ?>
                        </td>
                        
                        <td>
                            <?php if($row['status'] == 'Pending'): ?>
                                <span class="badge bg-pending">Pending</span>
                            <?php if($row['status'] == 'Success'): ?>
                                <span class="badge bg-paid">Success</span>
                            <?php else: ?>
                                <span class="badge bg-cancelled">Batal</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($row['status'] == 'pending'): ?>
                                <a href="/admin/bookings/confirm/<?= $row['id'] ?>" class="btn-mini btn-acc" title="Verifikasi Lunas" onclick="return confirm('Konfirmasi pembayaran lunas?')">✔ ACC</a>
                                <a href="/admin/bookings/cancel/<?= $row['id'] ?>" class="btn-mini btn-reject" title="Batalkan Booking" onclick="return confirm('Yakin batalkan pesanan ini?')">✖ Batal</a>
                            <?php else: ?>
                                <a href="/admin/bookings/delete/<?= $row['id'] ?>" class="btn-mini btn-trash" onclick="return confirm('Hapus data ini permanen?')">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding:30px; color:#999;">Belum ada booking masuk.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>