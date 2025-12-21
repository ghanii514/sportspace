<?= $this->include('layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    .owner-container {
        display: flex;
        min-height: 100vh;
        background-color: #f3f4f6;
    }
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
        color: #059669;
    }
    .main-content {
        flex: 1;
        padding: 30px;
    }
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

    <!-- ==== SIDEBAR (PERTAHANKAN) ==== -->
    <aside class="sidebar">
        <div class="owner-profile">
            <img src="/img/users/<?= user()->profile_picture ?? 'default.png' ?>" class="owner-img">
            <h4 style="margin:0;"><?= esc(user()->username) ?></h4>
            <span style="font-size:0.85em; color:#888;">Pemilik Lapangan</span>
        </div>

        <nav>
            <a href="/owner" class="menu-link">📊 Dashboard Utama</a>
            <a href="/owner/bookings" class="menu-link active">📅 Daftar Booking</a>
            <a href="/owner/chat" class="menu-link">💬 Chat User</a>

            <hr style="border:0; border-top:1px solid #eee; margin: 15px 0;">
            <a href="/logout" class="menu-link" style="color:#ef4444;">Keluar</a>
        </nav>
    </aside>

    <!-- ==== MAIN CONTENT ==== -->
    <main class="main-content">
        <h2 style="margin-top:0; margin-bottom:20px;">Daftar Booking Lapangan</h2>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Booking</th>
                        <th>Penyewa</th>
                        <th>Lapangan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Total</th>
                        <th>Diskon</th>
                        <th>Promo</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $no = 1; foreach ($bookings as $b): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($b['name']) ?></td>
                        <td><?= esc($b['penyewa']) ?></td>
                        <td><?= esc($b['nama_lapangan']) ?></td>

                        <td><?= esc($b['booking_date']) ?></td>

                        <td>
                            <?= substr($b['start_time'], 0, 5) ?> - <?= substr($b['end_time'], 0, 5) ?>
                        </td>

                        <td>Rp <?= number_format($b['total_price'], 0, ',', '.') ?></td>

                        <td>
                            <?= $b['discount_amount'] > 0 
                                ? '-Rp '.number_format($b['discount_amount'], 0, ',', '.') 
                                : '-' ?>
                        </td>

                        <td><?= $b['promo_code'] ?: '-' ?></td>

                        <td><span class="badge bg-success"><?= strtoupper($b['pembayaran']) ?></span></td>

                        <td>
                            <?php if ($b['status'] == 'pending'): ?>
                                <span class="badge bg-pending">Pending</span>
                            <?php elseif ($b['status'] == 'success'): ?>
                                <span class="badge bg-success">Sukses</span>
                            <?php else: ?>
                                <span class="badge bg-cancel">Batal</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if ($b['bukti_bayar']): ?>
                                <a href="<?= base_url('img/bukti/' . $b['bukti_bayar']) ?>" target="_blank">Lihat</a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if ($b['status'] == 'pending' && $b['bukti_bayar']): ?>
                                <a href="/owner/approve/<?= $b['id'] ?>" class="btn btn-sm btn-success">✔</a>
                                <a href="/owner/reject/<?= $b['id'] ?>" class="btn btn-sm btn-danger">✖</a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
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
<?= $this->include('layout/footer'); ?>
