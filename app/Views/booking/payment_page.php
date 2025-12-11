<?= $this->include('layout/template'); ?>

<style>
    .pay-card {
        max-width: 500px;
        margin: 40px auto;
        background: #fff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        text-align: center;
        border: 1px solid #e0e0e0;
    }
    .total-tagihan {
        font-size: 2rem;
        font-weight: 800;
        color: #39E07A;
        margin: 10px 0 20px;
    }
    .qris-img {
        width: 200px;
        height: 200px;
        object-fit: contain;
        margin: 15px auto;
        border: 2px dashed #ccc;
        padding: 10px;
        border-radius: 10px;
    }
    .rek-box {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin: 20px 0;
        text-align: left;
    }
    .copy-btn {
        float: right;
        background: none;
        border: 1px solid #ccc;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.8rem;
    }
    .file-input-wrapper {
        margin-top: 20px;
        text-align: left;
    }
    .btn-upload {
        width: 100%;
        background: #000;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        margin-top: 15px;
        cursor: pointer;
    }
    .btn-upload:hover { background: #333; }
</style>

<div class="container">
    <div class="pay-card">
        <h3 style="margin-top:0;">Selesaikan Pembayaran</h3>
        <p style="color:#666;">Silakan transfer sesuai nominal di bawah ini:</p>
        
        <div class="total-tagihan">
            Rp <?= number_format($booking['total_price'], 0, ',', '.') ?>
        </div>

        <div class="rek-box">
            <small>Metode Pembayaran:</small><br>
            <strong><?= strtoupper($booking['pembayaran']) ?></strong>
            
            <hr style="border:0; border-top:1px solid #ddd; margin:10px 0;">

            <?php if(strtolower($booking['pembayaran']) == 'qris' || strtolower($booking['pembayaran']) == 'gopay' || strtolower($booking['pembayaran']) == 'dana'): ?>
                <div style="text-align:center;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" class="qris-img" alt="QRIS">
                    <p style="font-size:0.9rem;">Scan QR di atas menggunakan DANA/Gopay/OVO</p>
                </div>
            <?php else: ?>
                <small>Nomor Rekening / E-Wallet:</small>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:5px;">
                    <strong style="font-size:1.2rem;">0812-3456-7890</strong>
                    <button class="copy-btn" onclick="alert('Nomor disalin!')">Salin</button>
                </div>
                <p style="margin:5px 0 0; font-size:0.9rem;">a.n SportSpace Admin</p>
            <?php endif; ?>
        </div>

        <form action="/booking/upload-bukti" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
            
            <div class="file-input-wrapper">
                <label style="font-weight:bold; display:block; margin-bottom:5px;">Upload Bukti Transfer</label>
                <input type="file" name="bukti_bayar" class="form-control" required accept="image/*" style="width:100%;">
                <small style="color:red;">* Wajib upload screenshot bukti bayar</small>
            </div>

            <button type="submit" class="btn-upload">Konfirmasi & Kirim Bukti</button>
        </form>
        
        <a href="/riwayat" style="display:block; margin-top:15px; text-decoration:none; color:#666;">Bayar Nanti</a>
    </div>
</div>

<?= $this->include('layout/footer'); ?>