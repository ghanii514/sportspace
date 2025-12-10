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

                <!-- Left Info -->
                <div class="riwayat-left">
                    <h4 class="lapangan-nama"><?= $r['nama_lapangan'] ?></h4>

                    <p class="jadwal">
                        <?= date('l, d M Y', strtotime($r['booking_date'])) ?>
                        - <?= esc($r['start_time']) ?> - <?= esc($r['end_time']) ?>
                    </p>
                </div>

                <!-- Right Info -->
                <div class="riwayat-right">

                    <div class="status-box">
                        <span class="icon-jam">🕒</span>
                        <span class="status-text">Status:
                            <span class="status-<?= $r['status'] ?>"><?= esc($r['status']) ?></span>
                        </span>
                    </div>


                </div>

            <!-- Modal Konfirmasi Pembayaran -->
            <div id="bayarModal" class="modal-overlay">

                <div class="modal-box">
                    <h3>Konfirmasi Pembayaran</h3>

                    <p>Apakah Anda yakin ingin melakukan pembayaran untuk booking ini?</p>

                    <form action="<?= base_url('booking/bayar/'. $r['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <input type="hidden" name="booking_id" value="<?= $r['id'] ?>">

                        <div class="modal-actions">
                            <button type="button" class="btn-cancel" onclick="closeBayarModal()">
                                Batal
                            </button>

                            <button type="submit" class="btn-confirm">
                                Ya, Bayar
                            </button>
                        </div>
                    </form>
                </div>

            </div>

                <div class="riwayat-btn-group row">

                    <a href="<?= base_url('booking/detail/' . $r['id']) ?>" class="btn-outline">
                        Detail
                    </a>

                    <?php if ($r['status'] === 'pending'): ?>
       
                        <button type="button" class="btn-pay" onclick="openBayarModal()">
                        Bayar Sekarang
                    </button>
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


<script>
    function openBayarModal() {
        document.getElementById('bayarModal').style.display = 'flex';
    }

    function closeBayarModal() {
        document.getElementById('bayarModal').style.display = 'none';
    }
</script>

<style>
    /* Container */
    .riwayat-container {
        padding-top: 30px;
    }

    /* Title */
    .riwayat-title {
        font-size: 28px;
        font-weight: bold;
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
        font-weight: bold;
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
    }

    .btn-outline:hover {
        background: #f2f2f2;
        color: #000;
    }

    .btn-bayar {
        background-color: #00c853;
        /* green utama */
        color: #ffffff;
        padding: 10px 28px;
        border-radius: 12px;
        /* biar agak rounded */
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        display: inline-block;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-bayar:hover {
        background-color: #00b248;
        /* green lebih gelap saat hover */
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    <style>
    .detail-wrapper {
        max-width: 700px;
        margin: 30px auto;
        padding: 10px;
    }

    .detail-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .btn-back {
        text-decoration: none;
        font-size: 20px;
        font-weight: bold;
        color: #000;
    }

    .invoice-card {
        border: 2px solid #dcdcdc;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
    }

    .invoice-top {
        background: #6e6e6e;
        color: white;
        padding: 14px 20px;
        font-weight: bold;
    }

    .invoice-body {
        padding: 20px;
    }

    .venue-name {
        font-size: 18px;
        font-weight: bold;
    }

    .jadwal {
        color: #555;
        margin-bottom: 15px;
    }

    .status-box {
        background: #fff3e0;
        padding: 10px 14px;
        border-radius: 8px;
        font-weight: 500;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
    }

    .status-pending {
        font-weight: bold;
        color: #f9a825;
    }
    .status-success {
        font-weight: bold;
        color: #02b131ff;
    }

    .invoice-table {
        border-top: 1px solid #ccc;
        margin-bottom: 15px;
    }

    .table-head,
    .table-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        padding: 10px 0;
        font-weight: 600;
    }

    .table-row {
        font-weight: normal;
        border-top: 1px solid #eee;
    }

    .table-total {
        border-top: 1px solid #ccc;
        padding: 15px 0 5px;
        font-weight: bold;
        text-align: right;
    }

    .payment-info {
        border-top: 1px solid #ccc;
        margin-top: 10px;
        padding-top: 15px;
        font-size: 14px;
    }

    .action-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 25px;
        cursor: default;
    }

    .btn-cancel {
        border: 2px solid #333;
        background: #fff;
        padding: 8px 28px;
        border-radius: 10px;
        text-decoration: none;
        color: #333;
        font-weight: 500;
    }

    .btn-pay {
        background: #000;
        color: white;
        padding: 10px 30px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
    }


    /* Modal Overlay */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    /* Modal Box */
    .modal-box {
        background: #fff;
        width: 320px;
        border-radius: 15px;
        padding: 20px 22px;
        text-align: center;
        animation: popIn 0.2s ease;
    }

    @keyframes popIn {
        from {
            transform: scale(0.9);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .modal-box h3 {
        margin-bottom: 10px;
    }

    .modal-box p {
        color: #555;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .modal-actions {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .btn-confirm {
        background: #00c853;
        color: white;
        border: none;
        padding: 10px 22px;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
    }

    .btn-cancel {
        background: transparent;
        border: 2px solid #888;
        padding: 8px 20px;
        border-radius: 10px;
        font-weight: 500;
        cursor: pointer;
    }

</style>

<?= $this->renderSection('content'); ?>

<?= $this->include('layout/footer'); ?>