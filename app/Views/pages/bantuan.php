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

    /* FAQ BOX */
    .faq-item {
        border: 1px solid #000;
        border-radius: 8px;
        margin-bottom: 8px;
        background: #fff;
        padding: 12px 18px;
        cursor: pointer;
    }

    .faq-question {
        font-weight: 600;
    }

    .faq-answer {
        display: none;
        padding-top: 10px;
        color: #444;
        line-height: 1.5;
    }
</style>

<div class="container py-5">

    <h2 class="fw-bold mb-4">Pusat Bantuan</h2>

    <!-- Akun Saya -->
    <h5 class="fw-bold mt-4 mb-2">Akun Saya</h5>

    <div class="faq-item">
        <div class="faq-question">[ + ] Bagaimana cara membatalkan pesanan saya?</div>
        <div class="faq-answer">
            Anda dapat membatalkan pesanan melalui menu "Riwayat Booking" kemudian pilih pesanan dan klik tombol "Batalkan".
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">[ + ] Bagaimana cara mengubah informasi akun?</div>
        <div class="faq-answer">
            Silakan buka halaman Profil lalu tekan tombol Edit untuk mengubah informasi akun Anda.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">[ + ] Apakah saya bisa mengubah jadwal booking yang sudah dipesan?</div>
        <div class="faq-answer">
            Perubahan jadwal dapat dilakukan jika status booking masih pending dan lapangan masih tersedia.
        </div>
    </div>

    <!-- Booking -->
    <h5 class="fw-bold mt-4 mb-2">Booking dan Pemesanan</h5>

    <div class="faq-item">
        <div class="faq-question">[ + ] Bagaimana cara membuat booking?</div>
        <div class="faq-answer">
            Pilih lapangan, pilih tanggal dan jam, lalu klik tombol "Booking Sekarang".
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">[ + ] Apa yang terjadi jika saya telat datang?</div>
        <div class="faq-answer">
            Jika keterlambatan lebih dari 15 menit, pemesanan dapat dibatalkan otomatis.
        </div>
    </div>

    <!-- Pembayaran -->
    <h5 class="fw-bold mt-4 mb-2">Pembayaran</h5>

    <div class="faq-item">
        <div class="faq-question">[ + ] Metode pembayaran apa saja yang diterima?</div>
        <div class="faq-answer">
            Kami menerima pembayaran melalui DANA, OVO, Gopay, dan Cash.
        </div>
    </div>

    <!-- Hubungi kami -->
    <div class="mt-5">
        <h4 class="fw-bold">Tidak menemukan jawaban?</h4>

        <a href="https://wa.link/64ivyn" class="btn mt-4" style="
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

<!-- === SCRIPT DROPDOWN FAQ === -->
<script>
document.querySelectorAll('.faq-item').forEach(item => {
    item.addEventListener('click', function() {
        let answer = this.querySelector('.faq-answer');
        let question = this.querySelector('.faq-question');

        if (answer.style.display === "block") {
            answer.style.display = "none";
            question.innerHTML = question.innerHTML.replace("-", "+");
        } else {
            answer.style.display = "block";
            question.innerHTML = question.innerHTML.replace("+", "-");
        }
    });
});
</script>

<?= $this->renderSection('main') ?>
<?= $this->include('layout/footer') ?>
