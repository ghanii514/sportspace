<?= $this->include('layout/template'); ?> <?= $this->section('content'); ?>

<div class="container" style="max-width: 800px;"> <h2 class="recommendation-title">Tambah Data Lapangan Baru</h2>

    <?php if (session()->getFlashdata('errors')) : ?>
        <div class="flash-msg error" style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
            <strong>Validasi Gagal!</strong>
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                    <li><?= esc($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="/lapangan/tambah" method="post" enctype="multipart/form-data" style="background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">

        <?= csrf_field(); ?> <div style="margin-bottom: 15px;">
            <label for="nama" style="font-weight: 600; display: block; margin-bottom: 5px;">Nama Lapangan</label>
            <input type="text" name="nama" id="nama" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;" value="<?= old('name'); ?>">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="alamat" style="font-weight: 600; display: block; margin-bottom: 5px;">Alamat</label>
            <input type="text" name="alamat" id="alamat" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;" value="<?= old('address'); ?>">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="harga" style="font-weight: 600; display: block; margin-bottom: 5px;">Harga per Jam (cth: 150000)</label>
            <input type="number" name="harga" id="harga" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;" value="<?= old('price_per_hour'); ?>">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="deskripsi" style="font-weight: 600; display: block; margin-bottom: 5px;">Deskripsi (Opsional)</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;"><?= old('description'); ?></textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="image" style="font-weight: 600; display: block; margin-bottom: 5px;">Upload Gambar (Max 1MB)</label>
            <input type="file" name="image" id="image" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;">
        </div>

        <button type="submit" class="booking-btn" style="width: auto; padding: 10px 20px;">Simpan Lapangan</button>

    </form>
</div>

<?= $this->renderSection('content'); ?>
<?= $this->include('layout/footer'); ?>