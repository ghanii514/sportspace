<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container">
    
    <h2 class="recommendation-title">
        Hasil pencarian untuk: "<?= esc($keyword); ?>"
    </h2>

    <div class="field-list">
        
        <?php if (!empty($fields)) : ?>
            
            <?php foreach ($fields as $field) : ?>
                <div class="field-card">
                    <img src="/img/fields/<?= esc($field['image'] ?? 'default.jpg'); ?>" alt="<?= esc($field['nama']); ?>">
                    <div class="field-card-content">
                        <h3><?= esc($field['nama']); ?></h3>
                        <p><?= esc($field['alamat']); ?></p>
                        <div class="harga">
                            Rp <?= number_format($field['harga'], 0, ',', '.'); ?> / jam
                        </div><br>
                        <a href="/lapangan/detail/<?= $field['id']; ?>" class="booking-btn">Booking Sekarang</a>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else : ?>
            
            <p>Lapangan dengan kata kunci "<?= esc($keyword); ?>" tidak ditemukan.</p>

        <?php endif; ?>
    </div>

</div> <?= $this->endSection(); ?>