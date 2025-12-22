<?= $this->include('layout/template') ?>
<?= $this->section('main') ?>

<style>
    .about {
        display: flex;
        justify-content: center; 
        align-items: center;
    }

    .table-img {
        width: 60%; 
        height: auto; 
    }

    .h1 {
        text-align: "center";
    }
</style>

<div class="container py-5">

    <div class="hero-box mb-5"></div>
    <h1 style="text-align: center; ">Tentang Kami</h1>
    <div class="about">
        <img src="/img/about/sportspace.jpeg" class="table-img" alt="Foto">
    </div>
    
    <h3 class="fw-bold">Visi </h3>
    <p class="mt-3">
        Menjadi platform digital terdepan yang memudahkan akses masyarakat dalam mencari dan memesan fasilitas olahraga, guna mendorong gaya hidup sehat
        dan aktif.
    </p>

    
    <h3 class="fw-bold mt-5">Misi</h3>
    <p class="mt-3">
       Menyediakan layanan pemesanan lapangan olahraga yang cepat, praktis, dan akurat dengan informasi ketersediaan realtime, menghadirkan sistem manajemen yang memudahkan pengelola lapangan, serta memberikan pengalaman pemesanan yang aman, nyaman, dan mendorong masyarakat untuk lebih aktif berolahraga.
    </p>

     <h3 class="fw-bold mt-5">Tujuan</h3>
    <p class="mt-3">
        SportSpace bertujuan untuk menghadirkan layanan pemesanan lapangan yang akan mempercepat proses booking, akan mempermudah pengguna dalam menemukan fasilitas olahraga terbaik, akan meningkatkan efisiensi pengelola dalam mengatur jadwal dan transaksi, serta akan membangun ekosistem olahraga digital yang mendukung aktivitas masyarakat secara berkelanjutan.
    </p>

</div>
<?= $this->renderSection('main') ?>
<?= $this->include('layout/footer') ?>
