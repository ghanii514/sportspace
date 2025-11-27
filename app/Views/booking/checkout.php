<?= $this->include("layout/template") ?>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        background: #ffffff;
        color: #000;
    }

    /* NAVBAR */
    .navbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #66ff80;
        padding: 10px 20px;
    }

    .logo {
        font-size: 22px;
        font-weight: bold;
    }

    .dot {
        color: black;
    }

    .search-box input {
        padding: 8px;
        width: 250px;
        border-radius: 5px;
        border: none;
    }

    .menu {
        list-style: none;
        display: flex;
        gap: 20px;
    }

    .menu li {
        cursor: pointer;
    }

    .signin {
        font-weight: bold;
        cursor: pointer;
    }

    /* TITLE */
    .title {
        margin-left: 40px;
        margin-top: 30px;
    }

    /* MAIN CONTENT */
    .container {
        display: flex;
        gap: 20px;
        padding: 20px 40px;
    }

    .box {
        background: #c8ffd0;
        padding: 20px;
        border-radius: 10px;
    }

    .left-box {
        width: 40%;
    }

    .right-box {
        width: 55%;
    }

    .summary {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .sub {
        list-style: none;
        margin-left: 20px;
    }

    .promo-box {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .promo-box input {
        flex: 1;
        padding: 8px;
        border-radius: 5px;
        border: none;
    }

    .promo-box button {
        padding: 8px 12px;
        background: #1a7bfd;
        border: none;
        color: white;
        border-radius: 5px;
    }

    /* PRICING */
    .price p {
        display: flex;
        justify-content: space-between;
    }

    .total {
        display: flex;
        justify-content: space-between;
        font-weight: bold;
        font-size: 18px;
    }

    /* PAYMENT BUTTON */
    .pay-container {
        text-align: center;
        margin-top: 20px;
    }

    .pay-btn {
        background: #00c84a;
        padding: 12px 25px;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
    }

    .safe {
        color: gray;
        font-size: 13px;
        margin-top: 5px;
    }

    /* FOOTER */
    footer {
        margin-top: 40px;
        text-align: center;
        padding: 20px;
        background: #e8ffe6;
    }

    .footer-menu a {
        margin: 0 10px;
        text-decoration: none;
        color: black;
    }

    .social span {
        margin: 0 5px;
    }
</style>
<!-- TITLE -->
<h1 class="title">Pemesanan</h1>

<form action="<?= base_url('/booking/save') ?>" method="post">

    <div class="container">
    
        <!-- LEFT SECTION -->
        <div class="box left-box">
            <h2>Detail Kontak</h2>
            <p><?= user()->username ?><br>
                <?= user()->email ?><br>

                <input type="hidden" name="username" value="<?= user()->username ?>"/>
                <input type="hidden" name="email" value="<?= user()->email ?>"/>
                
                <h2>Metode Pembayaran</h2>
                <ul>
                    <li>Virtual Account</li>
                    <li class="dropdown">
                        <div class="dropdown-header">
                            <span>E-Wallet</span>
                            <span class="arrow">▼</span>
                        </div>
                        
                        <ul class="dropdown-menu">
                            <li><input type="radio" name="gopay" id="gopay"><label for="gopay">GoPay</label></li>
                            <li><input type="radio" name="ovo" id="ovo"><label for="ovo">OVO</label></li>
                            <li><input type="radio" name="dana" id="dana"><label for="dana">Dana</label></li>
                        <li><input type="radio" name="cash" id="cash"><label for="dana">Cash</label></li>
                    </ul>
                </li>
                
            </ul>
            
            <h2>Punya Kode Promo?</h2>
            <div class="promo-box">
                <input type="text" name="kodepromo" placeholder="Masukkan Kode Promo">
                <button>Terapkan</button>
            </div>
        </div>
        
        
        
        <!-- RIGHT SECTION -->
        <div class="box right-box">
            <h2>Ringkasan Pesanan</h2>
            
            <img src="<?=base_url('img/fields/'.$field['image']) ?>" alt="" width="80%" height="30%">
            <div class="summary">
                <div>
                    <strong><?= $field['nama']?></strong><br>
                    <?= $field['alamat']?>
                </div>
            </div>
            
            <h3>Jadwal:</h3>
            <p><?= $booking_data['tanggal'] ?><br>
            <?= $booking_data['jam_mulai'] ?> - <?= $booking_data['jam_selesai']?> WIB <br><?= $booking_data['durasi']?> JAM</p>
            
            <h2>Rincian Biaya</h2>
            <div class="price">
                <p>Harga Sewa (1 Jam) <span><?= $booking_data['harga_sewa'] ?></span></p>
                <p>Diskon (Promo 20%) <span><?= $booking_data['diskon'] ?></span></p>
                <p>Biaya Layanan <span><?= $booking_data['biaya_layanan'] ?></span></p>
            </div>
            
            <hr>
            
            <p class="total">Total Pembayaran <span><?= $booking_data['total_bayar']?></span></p>
        </div>
        
        <input type="hidden" name="id_user" value="<?= user()->id ?>"/>
        <input type="hidden" name="venue_id" value="<?= $booking_data['venue_id'] ?>"/>
        <input type="hidden" name="jadwal" value="<?= $booking_data['tanggal'] ?>"/>
        <input type="hidden" name="mulai" value="<?= $booking_data['jam_mulai'] ?>"/>
        <input type="hidden" name="selesai" value="<?= $booking_data['jam_selesai'] ?>"/>
        <input type="hidden" name="total" value="<?= $booking_data['total_bayar'] ?>"/>
    </div>
    
    <div class="pay-container">
        <button class="pay-btn">Bayar Sekarang</button>
        <p class="safe">Pembayaran aman dan terenkripsi</p>
    </div>
</form>
<!-- MAIN CONTENT -->
<?= $this->include('layout/footer') ?>