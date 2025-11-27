<?= $this->include('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    /* ====== MODAL BACKGROUND ====== */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease forwards;
        z-index: 2000;
    }

    /* ====== MODAL BOX ====== */
    .modal-box {
        width: 350px;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        animation: slideUp 0.4s ease forwards;
    }

    /* ====== HEADER ====== */
    .modal-header {
        background: #33dd66;
        /* WARNA HIJAU SPORTSPACE */
        padding: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #000;
        font-weight: bold;
    }

    .modal-title {
        font-size: 18px;
    }

    .modal-close {
        background: transparent;
        border: none;
        font-size: 22px;
        cursor: pointer;
        color: #000;
    }

    /* ====== BODY ====== */
    .modal-body {
        padding: 20px;
        font-size: 15px;
        color: #333;
        text-align: center;
    }

    /* ====== FOOTER ====== */
    .modal-footer {
        padding: 10px 20px 18px;
        text-align: center;
    }

    .modal-btn {
        background: #33dd66;
        /* Hijau SportSpace */
        color: #000;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
    }

    .modal-btn:hover {
        background: #28c758;
    }

    /* ====== ANIMASI ====== */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>

<?php if (session()->getFlashdata('success')): ?>
    <div class="modal-overlay" id="successModal">
        <div class="modal-box">
            <div class="modal-header">
                <span class="modal-title">Berhasil!</span>
                <button class="modal-close" onclick="closeSuccessModal()">×</button>
            </div>
            <div class="modal-body">
                <?= session()->getFlashdata('success') ?>
            </div>
            <div class="modal-footer">
                <button class="modal-btn" onclick="closeSuccessModal()">OK</button>
            </div>
        </div>
    </div>
<?php endif; ?>


<?= $this->section('promo_banner'); ?>
<div class="promo-banner-area">
    <div class="container">
        <h2 class="recommendation-title" style="color: #000; margin-bottom: 20px;">
            Rekomendasi Promosi
        </h2>
        <div class="promo-list">
            <?php if (!empty($promo)): ?>
                <?php foreach ($promo as $promo): ?>
                    <a href="#" class="promo-card-horizontal">
                        <img src="/img/promo/<?= esc($promo['image'] ?? 'default.jpg'); ?>"
                            alt="<?= esc($promo['title'] ?? $promo['promo']); ?>">
                        <div class="promo-text-overlay">
                            <h3><?= esc($promo['title'] ?? $promo['promo']); ?></h3>
                            <p><?= esc($promo['description'] ?? $promo['deskripsi']); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #000;">Belum ada promo yang tersedia saat ini.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->renderSection('content'); ?>

<div class="hero-slider">
</div>

<div class="container">

    <div class="categories">
        <a href="/kategori/futsal" class="category-item">
            <div class="icon-circle"><img src="/img/icons/futsal.png" alt="Futsal"></div>
            <span>Futsal</span>
        </a>
        <a href="/kategori/basket" class="category-item">
            <div class="icon-circle"><img src="/img/icons/basket.png" alt="Basket"></div>
            <span>Basket</span>
        </a>
        <a href="/kategori/badminton" class="category-item">
            <div class="icon-circle"><img src="/img/icons/badminton.png" alt="Badminton"></div>
            <span>Badminton</span>
        </a>
        <a href="/kategori/voli" class="category-item">
            <div class="icon-circle"><img src="/img/icons/voli.png" alt="Voli"></div>
            <span>Voli</span>
        </a>
        <a href="/kategori/tenis" class="category-item">
            <div class="icon-circle"><img src="/img/icons/tenis.png" alt="Tenis"></div>
            <span>Tenis</span>
        </a>
    </div>

    <h2 class="recommendation-title" style="margin-top: 40px;">
        Rekomendasi Lapangan
    </h2>

    <div class="field-list">
        <?php if (!empty($fields)): ?>
            <?php foreach ($fields as $field): ?>
                <div class="field-card">
                    <img src="/img/fields/<?= esc($field['image'] ?? 'default.jpg'); ?>"
                        alt="<?= esc($field['nama'] ?? $field['name']); ?>">
                    <div class="field-card-content">
                        <h3><?= esc($field['nama'] ?? $field['name']); ?></h3>
                        <p><?= esc($field['alamat'] ?? $field['address']); ?></p>
                        <div class="price">
                            Rp <?= number_format($field['harga'] ?? $field['price_per_hour'], 0, ',', '.'); ?> / jam
                        </div>
                        <a href="/lapangan/detail/<?= $field['id']; ?>" class="booking-btn">Booking Sekarang</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Belum ada lapangan yang terdaftar.</p>
        <?php endif; ?>
    </div>

    <div class="pagination-wrapper">
        <?= $pager->links() ?>
    </div>

    <div class="ad-space-banner">
        <div class="ad-label">Space Iklan</div>
        <a href="https://wa.me/6281225057479" target="_blank">
            <img src="/img/ads/banner_iklan.png" alt="Iklan Banner Spesial">
        </a>
    </div>

    <div class="sponsorship-section">
        <h3 class="sponsor-title">Didukung Oleh</h3>
        <div class="sponsor-logos">
            <img src="/img/sponsor/adidas.png" alt="Sponsor 1">
            <img src="/img/sponsor/pocari.png" alt="Sponsor 2">
        </div>
    </div>

    <script>
        function closeSuccessModal() {
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.style.opacity = "0";
                modal.style.transition = "0.3s";
                setTimeout(() => modal.remove(), 300);
            }
        }

        // Optional: Auto-close setelah 3 detik
        setTimeout(() => {
            if (document.getElementById('successModal')) {
                closeSuccessModal();
            }
        }, 3000);
    </script>


</div>
<?= $this->renderSection('content'); ?>
<?= $this->include('layout/footer'); ?>