<?= $this->include("layout/template") ?>

<style>
    /* --- GLOBAL STYLE --- */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #ffffff;
        color: #333;
        margin: 0;
    }

    .title {
        margin: 30px auto 20px;
        max-width: 1000px;
        padding-left: 20px;
        color: #1e293b;
    }

    /* --- LAYOUT UTAMA --- */
    .container {
        display: flex;
        gap: 30px;
        padding: 0 20px;
        max-width: 1000px;
        margin: 0 auto 50px;
        align-items: flex-start;
    }

    /* --- BOX HIJAU --- */
    .box {
        background: #c8ffd0;
        padding: 30px;
        border-radius: 15px;
        border: 1px solid #aefabe;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .left-box { flex: 3; }
    .right-box { flex: 2; }

    h2 {
        font-size: 1.2rem;
        margin-top: 0;
        margin-bottom: 15px;
        color: #064e3b;
        border-bottom: 2px solid #aefabe;
        padding-bottom: 8px;
    }

    /* --- USER INFO --- */
    .user-info {
        background: rgba(255,255,255, 0.5);
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* --- LIST PEMBAYARAN --- */
    ul.payment-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .payment-item {
        margin-bottom: 10px;
        background: #ffffff;
        padding: 12px 15px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        border: 1px solid #bcf0c4;
        cursor: pointer;
        transition: 0.2s;
    }

    .payment-item:hover { border-color: #39E07A; }

    .payment-item input {
        margin-right: 15px;
        accent-color: #39E07A;
        transform: scale(1.2);
    }

    .payment-item label {
        cursor: pointer;
        width: 100%;
        font-weight: 500;
    }

    /* --- PROMO CODE --- */
    .promo-box {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .promo-box input {
        flex: 1;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        outline: none;
    }
    
    .promo-box input:focus { border-color: #39E07A; }

    .promo-box button {
        padding: 12px 20px;
        background: #1a7bfd;
        border: none;
        color: white;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.2s;
    }
    
    .promo-box button:hover { background: #0d6efd; }

    /* --- KANAN: SUMMARY --- */
    .field-image {
        width: 100%; 
        height: 160px; 
        object-fit: cover; 
        border-radius: 10px; 
        margin-bottom: 15px;
        background-color: #ddd;
    }

    .field-info strong {
        font-size: 1.1rem;
        display: block;
        margin-bottom: 5px;
    }
    
    .schedule-detail {
        margin: 15px 0;
        padding: 10px;
        background: rgba(255,255,255,0.6);
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        font-weight: bold;
        font-size: 1.2rem;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px dashed #99e6a0;
        color: #000;
    }

    /* --- TOMBOL BAYAR --- */
    .pay-container {
        text-align: center;
        margin-top: 30px;
        max-width: 1000px;
        margin: 20px auto;
        padding: 0 20px;
    }

    .pay-btn {
        background: #00c84a;
        padding: 15px 50px;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: 0.2s;
        width: 100%;
        max-width: 400px;
    }

    .pay-btn:hover {
        background: #00b342;
        transform: translateY(-2px);
    }

    .safe {
        color: gray;
        font-size: 13px;
        margin-top: 10px;
    }

    @media (max-width: 768px) {
        .container { flex-direction: column-reverse; }
        .left-box, .right-box { width: 100%; flex: auto; }
    }
</style>

<h1 class="title">Konfirmasi Pemesanan</h1>

<form action="<?= base_url('/booking/save') ?>" method="post">

    <div class="container">
    
        <div class="box left-box">
            <h2>Detail Kontak</h2>
            <div class="user-info">
                <strong><?= user()->username ?></strong><br>
                <?= user()->email ?>
                
                <input type="hidden" name="username" value="<?= user()->username ?>"/>
                <input type="hidden" name="email" value="<?= user()->email ?>"/>
            </div>
            
            <h2>Metode Pembayaran</h2>
            <ul class="payment-list">
                <li class="payment-item">
                    <input type="radio" name="pembayaran" id="GoPay" value="GoPay" checked>
                    <label for="GoPay">GoPay</label>
                </li>
                <li class="payment-item">
                    <input type="radio" name="pembayaran" id="ovo" value="OVO">
                    <label for="ovo">OVO</label>
                </li>
                <li class="payment-item">
                    <input type="radio" name="pembayaran" id="dana" value="Dana">
                    <label for="dana">DANA</label>
                </li>
                <li class="payment-item">
                    <input type="radio" name="pembayaran" id="cash" value="Cash">
                    <label for="cash">Bayar di Tempat (Cash)</label>
                </li>
            </ul>
            
            <h2 style="margin-top: 30px;">Kode Promo</h2>
            <div class="promo-box">
                <input type="text" name="kodepromo" id="input-kode" placeholder="Punya kode promo? Masukkan di sini">
                <button type="button" id="btn-pakai">Pakai</button>
            </div>
            <small id="pesan-promo" style="display:block; margin-top:5px; font-weight:bold;"></small>
        </div>
        
        <div class="box right-box">
            <h2>Ringkasan Pesanan</h2>
            
            <img src="<?= base_url('img/fields/'.$field['image']) ?>" alt="Lapangan" class="field-image">
            
            <div class="field-info">
                <strong><?= $field['nama']?></strong>
                <span style="color:#555; font-size:0.9rem;"><?= $field['alamat']?></span>
            </div>
            
            <div class="schedule-detail">
                📅 <?= $booking_data['tanggal'] ?><br>
                ⏰ <?= $booking_data['jam_mulai'] ?> - <?= $booking_data['jam_selesai']?> WIB (<?= $booking_data['durasi']?> Jam)
            </div>
            
            <h2>Rincian Biaya</h2>
                <div class="price">
                    <div class="price-row">
                        <span>Harga Sewa</span>
                        <span id="display-harga-sewa" data-harga="<?= $booking_data['harga_sewa'] ?>">
                            Rp <?= number_format($booking_data['harga_sewa'], 0, ',', '.') ?>
                        </span>
                    </div>

                    <div class="price-row" id="row-diskon" style="color: #064e3b; display: none;">
                        <span>Diskon Promo (<span id="label-persen">0</span>%)</span>
                        <span>- Rp <span id="display-diskon">0</span></span>
                    </div>

                    <div class="price-row">
                        <span>Biaya Layanan</span>
                        <span id="display-layanan" data-layanan="<?= $booking_data['biaya_layanan'] ?>">
                            Rp <?= number_format($booking_data['biaya_layanan'], 0, ',', '.') ?>
                        </span>
                    </div>

                    <div class="total-row">
                        <span>Total Pembayaran</span>
                        <span id="display-total">Rp <?= number_format($booking_data['total_bayar'], 0, ',', '.') ?></span>
                    </div>
                </div>
                
                </div>
        
        <input type="hidden" name="id_user" value="<?= user()->id ?>"/>
        <input type="hidden" name="venue_id" value="<?= $booking_data['venue_id'] ?>"/>
        <input type="hidden" name="jadwal" value="<?= $booking_data['tanggal'] ?>"/>
        <input type="hidden" name="mulai" value="<?= $booking_data['jam_mulai'] ?>"/>
        <input type="hidden" name="selesai" value="<?= $booking_data['jam_selesai'] ?>"/>
        
        <input type="hidden" name="total" id="input-total" value="<?= $booking_data['total_bayar'] ?>"/>
        <input type="hidden" name="discount_amount" id="input-diskon" value="0"/>
    </div>
    
    <div class="pay-container">
        <button class="pay-btn">Bayar Sekarang</button>
        <p class="safe">🔒 Pembayaran aman dan terenkripsi</p>
    </div>

</form>

<script>
document.getElementById('btn-pakai').addEventListener('click', function() {
    
    let kode = document.getElementById('input-kode').value;
    let hargaSewa = document.getElementById('display-harga-sewa').getAttribute('data-harga');
    let biayaLayanan = document.getElementById('display-layanan').getAttribute('data-layanan');
    let pesanBox = document.getElementById('pesan-promo');

    if(kode === '') {
        alert("Isi kode promo dulu dong!");
        return;
    }

    let btn = this;
    btn.innerHTML = "Cek...";
    btn.disabled = true;

    let formData = new FormData();
    formData.append('kode_promo', kode);
    formData.append('harga_sewa', hargaSewa);

    fetch('<?= base_url('booking/check-promo') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = "Pakai";
        btn.disabled = false;

        if(data.status === 'success') {
            
            pesanBox.innerHTML = "Yeay! " + data.message;
            pesanBox.style.color = "green";

            document.getElementById('row-diskon').style.display = 'flex';
            document.getElementById('label-persen').innerText = data.persen;
            document.getElementById('display-diskon').innerText = formatRupiah(data.diskon_rupiah);

            let totalBaru = (parseInt(hargaSewa) - parseInt(data.diskon_rupiah)) + parseInt(biayaLayanan);

            document.getElementById('display-total').innerText = "Rp " + formatRupiah(totalBaru);
            
            // UPDATE INPUT HIDDEN
            document.getElementById('input-total').value = totalBaru;
            document.getElementById('input-diskon').value = data.diskon_rupiah;

        } else {
            pesanBox.innerHTML = data.message;
            pesanBox.style.color = "red";
            document.getElementById('row-diskon').style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btn.innerHTML = "Pakai";
        btn.disabled = false;
        alert("Terjadi kesalahan sistem.");
    });
});

function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID').format(angka);
}
</script>

<?= $this->include('layout/footer') ?>