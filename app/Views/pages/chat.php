<?= $this->include.('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    .field-card {
        transition: all 0.25s ease-in-out;
        border-radius: 12px;
        overflow: hidden;
        border: none;
    }

    .field-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }

    .field-image {
        height: 200px; 
        object-fit: cover; 
        border-bottom: 1px solid #eee;
    }

    .field-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #222;
    }

    .field-category {
        font-size: 0.9rem;
        
        color: #6c757d;
    }

    .field-address {
        font-size: 0.95rem;
        color: #555;
    }

    .field-price {
        font-size: 1.05rem;
        font-weight: 700;
        color: #28a745;
    }

    .btn-chat {
        background: #28a745;
        border: none;
        padding: 10px;
        font-weight: 600;
        transition: all 0.25s ease;
        border-radius: 8px;
    }

    .btn-chat:hover {
        background: #1f8a38;
        transform: scale(1.03);
    }
</style>

<div class="container mt-4">

    <h3 class="mb-4 fw-bold">Daftar Lapangan</h3>

    <div class="row g-4">

        <?php foreach ($fields as $lap): ?>
            <div class="col-md-4">
                <div class="card field-card shadow-sm h-100">
                    
                    <?php if ($lap['image']): ?>
                        <img src="/img/fields/<?= $lap['image']; ?>" 
                             class="card-img-top field-image">
                    <?php else: ?>
                        <div class="bg-secondary text-white text-center py-5">
                            Tidak ada foto
                        </div>
                    <?php endif; ?>

                    <div class="card-body">
                        <h5 class="field-title"><?= esc($lap['nama']); ?></h5>
                        <p class="field-category mb-1"><?= esc($lap['kategori']); ?></p>
                        <p class="field-address mb-2"><?= esc($lap['alamat']); ?></p>
                        <p class="field-price">Rp <?= number_format($lap['harga'], 0, ',', '.'); ?>/jam</p>
                    </div>

                    <div class="card-footer bg-white border-0">
                        <a href="/chat/lapangan/<?= $lap['id']; ?>" 
                           class="btn btn-chat w-100">
                            Chat Admin Lapangan
                        </a>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>

    </div>

</div>

<?= $this->renderSection('content'); ?>
