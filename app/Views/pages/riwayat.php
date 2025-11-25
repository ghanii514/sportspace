<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container">
    
    <h1 class="riwayat-title">Riwayat Booking</h1>

    <div class="riwayat-tabs">
        <button class="tab-btn active">Akan Datang</button>
        <button class="tab-btn">Selesai</button>
    </div>

    <div class="riwayat-empty-state">
        
        <img src="/img/cat_mascot.png" alt="Belum ada riwayat" class="empty-img">
        
        <h3 class="empty-text">Anda Belum Memiliki Riwayat</h3>
        
        <a href="/" class="btn-cari-lapangan">Cari Lapangan</a>
    </div>

</div>

<?= $this->endSection(); ?>