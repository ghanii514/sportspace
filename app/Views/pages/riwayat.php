<?= $this->include('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container riwayat-container">

    <h1 class="riwayat-title">Riwayat Booking</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <div id="popupSuccess" class="popup-success">
            ✅ <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>


    <div class="riwayat-tabs">
        <a href="<?= base_url('riwayat?tab=upcoming') ?>"
            class="tab-btn <?= (!isset($_GET['tab']) || $_GET['tab'] == 'upcoming') ? 'active' : '' ?>">
            Akan Datang
        </a>

        <a href="<?= base_url('riwayat?tab=completed') ?>"
            class="tab-btn <?= (isset($_GET['tab']) && $_GET['tab'] == 'completed') ? 'active' : '' ?>">
            Selesai
        </a>
    </div>

    <?php if (empty($riwayat)): ?>
        <div class="riwayat-empty-state">
            <img src="/img/icons/riwayat.png" class="empty-img" alt="Kosong">
            <h3>Anda Belum Memiliki Riwayat</h3>
            <a href="<?= base_url('/') ?>" class="btn-cari-lapangan">Cari Lapangan</a>
        </div>
    <?php else: ?>

        <?php foreach ($riwayat as $r): ?>
            <div class="riwayat-card">

                <div class="riwayat-left">
                    <h4 class="lapangan-nama"><?= $r['nama_lapangan'] ?></h4>

                    <p class="jadwal">
                        <?= date('l, d M Y', strtotime($r['booking_date'])) ?>
                        - <?= esc($r['start_time']) ?> - <?= esc($r['end_time']) ?>
                    </p>
                </div>

                <div class="riwayat-right">

                    <div class="status-box">
                        <span class="icon-jam">🕒</span>
                        <span class="status-text">Status:
                            <span class="status-<?= $r['status'] ?>"><?= esc($r['status']) ?></span>
                        </span>
                    </div>

                </div>

                <div class="riwayat-btn-group row">

                    <a href="<?= base_url('booking/detail/' . $r['id']) ?>" class="btn-outline">
                        Detail
                    </a>

                    <?php if ($r['status'] === 'pending'): ?>
                        
                        <?php if (empty($r['bukti_bayar'])): ?>
                            
                            <a href="<?= base_url('booking/payment/' . $r['id']) ?>" class="btn-pay">
                                Bayar Sekarang
                            </a>

                        <?php else: ?>
                            
                            <button type="button" class="btn-pay" style="background:#888; cursor:default;" disabled>
                                Menunggu Verifikasi
                            </button>

                        <?php endif; ?>

                        <form action="<?= base_url('booking/batal/' . $r['id']) ?>" method="post">
                            <button onclick="return confirm('Yakin ingin membatalkan booking?')" class="btn-outline">
                                Batalkan
                            </button>
                        </form>

                    <?php endif; ?>

                </div>

            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>

<style>
    /* Container */
    .riwayat-container {
        padding-top: 30px;
    }

    /* Title */
    .riwayat-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    /* Tabs */
    .riwayat-tabs {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
    }

    .popup-success {
        background: #00c853;
        color: white;
        padding: 14px 25px;
        border-radius: 12px;
        font-weight: 600;
        margin-bottom: 20px;
        text-align: center;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }


    .tab-btn {
        padding: 10px 30px;
        border-radius: 12px;
        border: none;
        background: #d4f7df;
        font-weight: bold;
        text-decoration: none; /* Tambahan biar <a> ga ada garis bawah */
        color: #000;
    }

    .tab-btn.active {
        background: #2ecc71;
        color: white;
    }

    /* Card */
    .riwayat-card {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 18px 22px;
        margin-bottom: 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Left section */
    .lapangan-nama {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .jadwal {
        color: #666;
        font-size: 14px;
    }

    /* Right section */
    .riwayat-right {
        text-align: right;
    }

    .status-box {
        font-size: 14px;
        margin-bottom: 8px;
    }

    .status-pending {
        font-weight: bold;
        color: #f39c12;
    }
    .status-cancelled {
        font-weight: bold;
        color: #e70000ff;
    }

    .status-success {
        font-weight: bold;
        color: #02b131ff;
    }

    /* Buttons */
    .riwayat-btn-group {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .btn-outline {
        padding: 6px 22px;
        border: 2px solid #333;
        border-radius: 10px;
        background: transparent;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        color: #000;
        cursor: pointer; /* Tambahan */
        display: inline-block; /* Tambahan */
    }

    .btn-outline:hover {
        background: #f2f2f2;
        color: #000;
    }

    .btn-pay {
        background: #000;
        color: white;
        padding: 10px 30px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        border: none; /* Tambahan */
        font-size: 14px; /* Tambahan biar match sama button asli */
        cursor: pointer; /* Tambahan */
        display: inline-block; /* Tambahan */
    }

</style>

<?= $this->renderSection('content'); ?>
<?= $this->include('layout/footer'); ?>