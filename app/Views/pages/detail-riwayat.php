<?= $this->include('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="detail-wrapper">

    <!-- Header -->
    <div class="detail-header">
        <a href="<?= base_url('riwayat') ?>" class="btn-back">←</a>
        <h2>Detail Booking</h2>
    </div>

    <!-- Card -->
    <div class="invoice-card">


        <!-- Invoice Header -->
        <div class="invoice-top">
            Invoice #: <?= $booking['nama_lapangan'] ?>
        </div>
        <!-- Venue Info -->
        <div class="invoice-body">
            <h4 class="venue-name"><?= esc($booking['nama_lapangan']) ?></h4>
            <p class="jadwal">
                <?= date('l, d M Y', strtotime($booking['booking_date'])) ?>,
                <?= esc($booking['start_time']) ?> - <?= esc($booking['end_time']) ?>
            </p>

            <!-- Status -->
            <div class="status-box">
                <span>Status Pembayaran:</span>
                <span class="status-<?= $booking['status'] ?>">🕒 <?= esc(ucfirst($booking['status'])) ?></span>
            </div>

            <!-- Table -->
            <div class="invoice-table">
                <div class="table-head">
                    <div>Deskripsi</div>
                    <div>Jumlah</div>
                    <div>Harga</div>
                </div>

                <?php 
                    // LOGIKA HITUNG HARGA ASLI
                    // Cek apakah kolom discount_amount ada isinya, kalau null anggap 0
                    $diskon = isset($booking['discount_amount']) ? $booking['discount_amount'] : 0;
                    
                    // Harga Asli = Total Akhir + Diskon yang diberikan
                    $hargaAsli = $booking['total_price'] + $diskon;
                ?>

                <div class="table-row">
                    <div>Sewa Lapangan</div>
                    <div>1</div>
                    <div>Rp <?= number_format($hargaAsli, 0, ',', '.') ?></div>
                </div>

                <?php if($diskon > 0): ?>
                <div class="table-row" style="color: #00c853;"> <div>
                        Promo Diskon 
                        <?php if(!empty($booking['promo_code'])): ?>
                            (Kode: <strong><?= esc($booking['promo_code']) ?></strong>)
                        <?php endif; ?>
                    </div>
                    <div></div>
                    <div>- Rp <?= number_format($diskon, 0, ',', '.') ?></div>
                </div>
                <?php endif; ?>

                <div class="table-total">
                    Total Tagihan:
                    <span>Rp <?= number_format($booking['total_price'], 0, ',', '.') ?></span>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="payment-info">
                <p><strong>Metode Pembayaran:</strong> <?= $booking['pembayaran'] ?></p>
                <p><strong>Batas Waktu Pembayaran:</strong>
                    <?= date('d M Y, H:i', strtotime($booking['booking_date'] . ' ' . $booking['start_time'])) ?>
                </p>
            </div>

            <!-- Modal Konfirmasi Pembayaran -->
            <div id="bayarModal" class="modal-overlay">

                <div class="modal-box">
                    <h3>Konfirmasi Pembayaran</h3>

                    <p>Apakah Anda yakin ingin melakukan pembayaran untuk booking ini?</p>

                    <form action="<?= base_url('booking/bayar/'. $booking['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">

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


            <!-- Buttons -->

            <div class="action-buttons">
                <a href="/riwayat" class="btn-cancel">
                    Batalkan
</a>

                <?php if ($booking['status'] === 'pending'): ?>
                    <button type="button" class="btn-pay" onclick="openBayarModal()">
                        Bayar Sekarang
                    </button>
                <?php endif; ?>

            </div>

        </div>
    </div>

</div>

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

<script>
    function openBayarModal() {
        document.getElementById('bayarModal').style.display = 'flex';
    }

    function closeBayarModal() {
        document.getElementById('bayarModal').style.display = 'none';
    }
</script>


<?= $this->renderSection('content'); ?>

<?= $this->include('layout/footer'); ?>