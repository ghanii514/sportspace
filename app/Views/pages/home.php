<?= $this->include('layout/template'); ?>

<?= $this->section('content'); ?>

<?= $this->section('promo_banner'); ?>
<div class="promo-banner-area">
    <div class="container"> 
        <h2 class="recommendation-title" style="color: #000; margin-bottom: 20px;">
            Rekomendasi Promosi
        </h2>
        <div class="promo-list">
            <?php if (!empty($promo)) : ?>
                <?php foreach ($promo as $promo) : ?>
                    <a href="#" class="promo-card-horizontal">
                        <img src="/img/promo/<?= esc($promo['image'] ?? 'default.jpg'); ?>" alt="<?= esc($promo['title'] ?? $promo['promo']); ?>">
                        <div class="promo-text-overlay">
                            <h3><?= esc($promo['title'] ?? $promo['promo']); ?></h3>
                            <p><?= esc($promo['description'] ?? $promo['deskripsi']); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else : ?>
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
        <?php if (!empty($fields)) : ?>
            <?php foreach ($fields as $field) : ?>
                <div class="field-card">
                    <img src="/img/fields/<?= esc($field['image'] ?? 'default.jpg'); ?>" alt="<?= esc($field['nama'] ?? $field['name']); ?>">
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
        <?php else : ?>
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

</div> 
<?= $this->renderSection('content'); ?>
<?= $this->include('layout/footer'); ?>