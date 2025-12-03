<?= $this->include('layout/template') ?>
<?= $this->section('main') ?>

<style>
    .navbar-custom {
        background-color: #49e265;
    }

    .search-box {
        border: 2px solid #000;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 16px;
        width: 96%;
    }

    .filter-btn {
        background-color: #49e265;
        border: none;
        padding: 6px 20px;
        margin-top: 20px;
        border-radius: 20px;
        font-weight: 500;
        cursor: pointer;
        margin-left: 100px;
    }

    .faq-box {
        border: 1px solid #000;
        padding: 12px 18px;
        border-radius: 8px;
        margin-bottom: 8px;
        
        background: #fff;
    }
</style>


<div class="container py-5">

    <h2 class="fw-bold mb-4">Pusat Bantuan</h2>

    <input type="text" class="search-box mb-4" placeholder="Cari pertanyaan Anda....">
    <!-- Kategori -->
    <div class="d-flex gap-3 flex-wrap mb-4">
        <button class="filter-btn">Semua</button>
        <button class="filter-btn">Akun Saya</button>
        <button class="filter-btn">Booking</button>
        <button class="filter-btn">Pembayaran</button>
        <button class="filter-btn">Lainnya</button>
    </div>

    <!-- Akun Saya -->
    <h5 class="fw-bold mt-4 mb-2">Akun Saya</h5>

    <div class="faq-box">[ + ] Bagaimana cara membatalkan pesanan saya?</div>
    <div class="faq-box">[ + ] Bagaimana cara membatalkan pesanan saya?</div>
    <div class="faq-box">[ + ] Apakah saya bisa mengubah jadwal booking yang sudah dipesan?</div>

    <!-- Booking dan Pemesanan -->
    <h5 class="fw-bold mt-4 mb-2">Booking dan Pemesanan</h5>

    <div class="faq-box">[ + ] Bagaimana cara membatalkan pesanan saya?</div>
    <div class="faq-box">[ + ] Apakah saya bisa mengubah jadwal booking yang sudah dipesan?</div>

    <!-- Pembayaran -->
    <h5 class="fw-bold mt-4 mb-2">Pembayaran</h5>

    <div class="faq-box">[ + ] Metode pembayaran apa saja yang diterima?</div>

    <!-- Hubungi kami -->
    <div class="mt-5">
        <h4 class="fw-bold">Tidak menemukan jawaban?</h4>

        <a href="#" class="btn mt-4" style="
        background:#49e265;
        border-radius:12px;
        padding:14px 60px;
        font-size:20px;
        font-weight:700;
        display:inline-block;
   ">
            Hubungi kami
        </a>

    </div>

</div>
<?= $this->renderSection('main') ?>
<?= $this->include('layout/footer') ?>