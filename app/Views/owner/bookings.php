<?= $this->section('content'); ?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-color: #10b981; 
        --text-dark: #1f2937;
        --sidebar-width: 280px;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f3f4f6;
        margin: 0;
        color: var(--text-dark);
    }

    .owner-container { display: flex; min-height: 100vh; }

    .sidebar {
        width: var(--sidebar-width);
        background: #cbffd3ff;
        padding: 30px 20px;
        border-right: 1px solid #49ff64ff;
        position: sticky; top: 0; height: 100vh;
        display: flex; flex-direction: column;
        box-shadow: 2px 0 5px rgba(0,0,0,0.02);
    }

    .owner-profile { text-align: center; padding-bottom: 25px; border-bottom: 1px solid #f3f4f6; margin-bottom: 25px; }
    .owner-img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 3px solid var(--primary-color); padding: 2px; }
    .menu-link { display: block; padding: 14px 18px; margin-bottom: 8px; color: #6b7280; text-decoration: none; border-radius: 12px; font-weight: 500; transition: 0.3s; }
    .menu-link:hover, .menu-link.active { background-color: #ecfdf5; color: #059669; font-weight: 600; }

    .main-content { flex: 1; padding: 40px; overflow-x: auto; }

    .table-container {
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid #f0f0f0;
    }

    table { width: 100%; border-collapse: separate; border-spacing: 0; }

    th {
        text-align: left; padding: 16px; 
        background: #f9fafb; color: #6b7280; 
        font-size: 0.8em; font-weight: 700; text-transform: uppercase;
        border-bottom: 2px solid #e5e7eb;
    }

    td {
        padding: 16px; border-bottom: 1px solid #f3f4f6;
        font-size: 0.9em; vertical-align: middle;
    }

    tr:hover td { background-color: #f9fafb; }

    .badge { padding: 6px 12px; border-radius: 50px; font-size: 0.75em; font-weight: 600; display: inline-block; white-space: nowrap; }
    .bg-pending { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .bg-success { background: #ecfdf5; color: #047857; border: 1px solid #d1fae5; }
    .bg-cancel { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }

    .btn { text-decoration: none; padding: 6px 10px; border-radius: 6px; font-size: 0.85em; transition: 0.2s; display: inline-block;}
    .btn-sm { padding: 4px 8px; }
    .btn-success { background: #10b981; color: white; }
    .btn-success:hover { background: #059669; }
    .btn-danger { background: #ef4444; color: white; }
    .btn-danger:hover { background: #dc2626; }
    .link-blue { color: #2563eb; font-weight: 500; text-decoration: none; }
    .link-blue:hover { text-decoration: underline; }
</style>

<div class="owner-container">

    <aside class="sidebar">
        <div class="owner-profile">
            <img src="/img/fields/<?= $venue_image ?>" class="owner-img">
            <h4 style="margin:0;"><?= esc($venue_names) ?></h4>
            <span style="font-size:0.85em; color:#888;">Pemilik Lapangan</span>
        </div>

        <nav>
            <a href="/owner" class="menu-link">Dashboard Utama</a>
            <a href="/owner/bookings" class="menu-link active">Daftar Booking</a>
            <a href="/owner/chat" class="menu-link">Chat User</a>

            <hr style="border:0; border-top:1px solid #eee; margin: 20px 0;">
            <a href="/logout" class="menu-link" style="color:#ef4444;">Keluar</a>
        </nav>
    </aside>

    <main class="main-content">
        <h2 style="margin-top:0; margin-bottom:25px; font-weight:700;">Daftar Booking Lapangan</h2>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Booking</th>
                        <th>Penyewa</th>
                        <th>Tanggal & Jam</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $no = 1; foreach ($bookings as $b): ?>
                    <tr>
                        <td style="color:#9ca3af;"><?= $no++ ?></td>
                        <td>
                            <div style="font-weight:600;"><?= esc($b['name']) ?></div>
                            <small style="color:#888;"><?= esc($b['nama_lapangan']) ?></small>
                        </td>
                        <td><?= esc($b['penyewa']) ?></td>

                        <td>
                            <div style="font-weight:500;"><?= esc($b['booking_date']) ?></div>
                            <small class="badge" style="background:#f3f4f6; color:#4b5563; border:0; margin-top:4px;">
                                <?= substr($b['start_time'], 0, 5) ?> - <?= substr($b['end_time'], 0, 5) ?>
                            </small>
                        </td>

                        <td>
                            <div style="font-weight:600; color:#059669;">Rp <?= number_format($b['total_price'], 0, ',', '.') ?></div>
                            <?php if($b['discount_amount'] > 0): ?>
                                <small style="color:#ef4444; font-size:0.75em;">Disc: -<?= number_format($b['discount_amount'], 0, ',', '.') ?></small>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if ($b['status'] == 'pending'): ?>
                                <span class="badge bg-pending">Pending</span>
                            <?php elseif ($b['status'] == 'success'): ?>
                                <span class="badge bg-success">Lunas (<?= strtoupper($b['pembayaran']) ?>)</span>
                            <?php else: ?>
                                <span class="badge bg-cancel">Batal</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if ($b['bukti_bayar']): ?>
                                <a href="<?= base_url('img/bukti/' . $b['bukti_bayar']) ?>" target="_blank" class="link-blue">Lihat Bukti ↗</a>
                            <?php else: ?>
                                <span style="color:#ccc;">-</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if ($b['status'] == 'pending' && $b['bukti_bayar']): ?>
                                <div style="display:flex; gap:5px;">
                                    <a href="/owner/approve/<?= $b['id'] ?>" class="btn btn-sm btn-success">Setujui</a>
                                    <a href="/owner/reject/<?= $b['id'] ?>" class="btn btn-sm btn-danger">Tolak</a>
                                </div>
                            <?php else: ?>
                                <span style="color:#ccc;">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </main>
</div>

<?= $this->renderSection('content'); ?>
