<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="hero-slider">
    </div>

<div class="container"> <div class="categories">
        <a href="/kategori/futsal" class="category-item">
            <div class="icon-circle">
                <img src="/img/icons/futsal.png" alt="Futsal">
            </div>
            <span>Futsal</span>
        </a>
        <a href="/kategori/basket" class="category-item">
            <div class="icon-circle">
                <img src="/img/icons/basket.png" alt="Basket">
            </div>
            <span>Basket</span>
        </a>
        <a href="/kategori/badminton" class="category-item">
            <div class="icon-circle">
                <img src="/img/icons/badminton.png" alt="Badminton">
            </div>
            <span>Badminton</span>
        </a>
        <a href="/kategori/voli" class="category-item">
            <div class="icon-circle">
                <img src="/img/icons/voli.png" alt="Voli">
            </div>
            <span>Voli</span>
        </a>
        <a href="/kategori/tenis" class="category-item">
            <div class="icon-circle">
                <img src="/img/icons/tenis.png" alt="Tenis">
            </div>
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
                    <img src="/img/fields/<?= esc($field['image'] ?? 'default.jpg'); ?>" alt="<?= esc($field['nama']); ?>">
                    <div class="field-card-content">
                        <h3><?= esc($field['nama']); ?></h3>
                        <p><?= esc($field['alamat']); ?></p>
                        <div class="price">
                            Rp <?= number_format($field['harga'], 0, ',', '.'); ?> / jam
                        </div>
                        <a href="/lapangan/detail/<?= $field['id']; ?>" class="booking-btn">Booking Sekarang</a>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else : ?>
            <p>Belum ada lapangan yang terdaftar.</p>
        <?php endif; ?>
    </div> </div> <?= $this->endSection(); ?>
<?= $this->section('promo_banner'); ?>

<div class="promo-banner-area">
    <div class="container"> <h2 class="recommendation-title" style="color: #000; margin-bottom: 20px;">
            Rekomendasi Promosi
        </h2>

        <div class="promo-list">
            <?php if (!empty($promos)) : ?>
                <?php foreach ($promos as $promo) : ?>

                    <a href="#" class="promo-card-horizontal">
                        <img src="/img/promos/<?= esc($promo['image'] ?? 'default.jpg'); ?>" alt="<?= esc($promo['promo']); ?>">
                        
                        <div class="promo-text-overlay">
                            <h3><?= esc($promo['promo']); ?></h3>
                            <p><?= esc($promo['deskripsi']); ?></p>
                        </div>
                    </a>

                <?php endforeach; ?>
            <?php else : ?>
                <p style="color: #000;">Belum ada promo yang tersedia saat ini.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>