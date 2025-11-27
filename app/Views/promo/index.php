<?= $this->include('layout/template') ?>

<style>
    /* ===== PAGE CONTAINER ===== */
    .promo-page-container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 0 20px;

    }

    /* TITLE */
    .promo-title {
        font-size: 34px;
        font-weight: 800;
        margin-bottom: 25px;
        color: #000;
    }

    /* ===== PROMO CARD ===== */
    .promo-card {
        display: flex;
        background: linear-gradient(90deg,
                #e8fff0 0%,
                /* sangat terang (kiri) */
                #d4ffe0 35%,
                /* soft green */
                #c6ffd0 70%,
                /* green pastel */
                #b1ffb6 100%
                /* hijau lebih pekat (kanan) */
            );
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 25px;
        box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.10);
        transition: 0.25s ease;
    }

    .promo-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.18);
    }

    /* LEFT IMAGE */
    .promo-image {
        width: 35%;
        min-width: 200px;
    }

    .promo-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* RIGHT CONTENT */
    .promo-content {
        padding: 20px 25px;
        width: 65%;
    }

    /* TITLE (DISKON 20% dst) */
    .promo-header {
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    /* DESCRIPTION */
    .promo-desc {
        font-size: 15px;
        color: #333;
        margin-bottom: 10px;
    }

    /* VALID TILL */
    .promo-valid {
        color: #444;
        font-size: 14px;
        margin-bottom: 15px;
    }

    /* BUTTON */
    .promo-btn {
        display: inline-block;
        background: #1bd43e;
        padding: 10px 18px;
        color: #000;
        border-radius: 8px;
        font-weight: bold;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .promo-btn:hover {
        background: #10c031;
    }
</style>
<div class="promo-page-container">

    <h1 class="promo-title">Semua Promo Spesial</h1>

    <?php if (!empty($promo)): ?>
        <?php foreach ($promo as $row): ?>
            <div class="promo-card">

                <!-- IMAGE -->
                <div class="promo-image">
                    <img src="/img/promo/<?= esc($row['image']) ?>" alt="<?= esc($row['promo']) ?>">
                </div>

                <!-- TEXT AREA -->
                <div class="promo-content">

                    <h2 class="promo-header">
                        <?= esc(strtoupper($row['promo'])) ?>
                    </h2>

                    <p class="promo-desc">
                        <?= esc($row['deskripsi']) ?>
                    </p>

                    <p class="promo-valid">
                        Gunakan kode: <strong><?= esc($row['promo_code']) ?></strong>
                    </p>

                    <a href="/promo/detail/<?= esc($row['id']) ?>" class="promo-btn">
                        Lihat Detail & Booking >
                    </a>

                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <p class="no-promo">Belum ada promo yang tersedia saat ini.</p>
    <?php endif; ?>

</div>

<?= $this->include('layout/footer') ?>