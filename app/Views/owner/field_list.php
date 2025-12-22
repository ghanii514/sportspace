<?= $this->include('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    /* Kita pakai style yang mirip Dashboard Mitra biar konsisten */
    .mitra-container { display: flex; gap: 30px; max-width: 1200px; margin: 40px auto; padding: 0 20px; min-height: 600px; }
    .mitra-sidebar { width: 250px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 20px; height: fit-content; position: sticky; top: 20px; }
    .mitra-content { flex: 1; }
    
    /* Style Table */
    .card-box { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th { text-align: left; padding: 15px; background: #f8fafc; color: #64748b; }
    td { padding: 15px; border-bottom: 1px solid #f1f5f9; }
    
    .field-img { width: 80px; height: 50px; object-fit: cover; border-radius: 6px; }
    
    .btn-edit { background: #f59e0b; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.9em; margin-right: 5px; }
    .btn-add { background: #39E07A; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block; margin-bottom: 15px; }
    
    /* Sidebar styling (copy dikit dari dashboard biar rapi) */
    .menu-list { list-style: none; padding: 0; }
    .menu-item { margin-bottom: 8px; }
    .menu-link { display: block; padding: 10px; color: #64748b; text-decoration: none; font-weight: 600; }
    .menu-link.active { color: #39E07A; background: #e8fff0; border-radius: 8px; }
</style>

<div class="mitra-container">
    
    <aside class="mitra-sidebar">
        <h3 style="text-align:center; margin-bottom:20px;">Menu Mitra</h3>
        <ul class="menu-list">
            <li class="menu-item"><a href="/mitra" class="menu-link">Ringkasan</a></li>
            <li class="menu-item"><a href="/mitra/fields" class="menu-link active">Kelola Lapangan</a></li>
            <li class="menu-item"><a href="#" class="menu-link">Jadwal Booking</a></li>
        </ul>
    </aside>

    <main class="mitra-content">
        <h2 style="margin-top:0;">Daftar Lapangan Saya</h2>
        
        <div class="card-box">
            <a href="#" class="btn-add">+ Tambah Lapangan</a>

            <?php if(session()->getFlashdata('success')): ?>
                <div style="background:#dcfce7; color:#166534; padding:10px; border-radius:8px; margin-bottom:15px;">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Lapangan</th>
                        <th>Harga / Jam</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($fields)): ?>
                        <?php foreach($fields as $field): ?>
                        <tr>
                            <td>
                                <img src="/img/fields/<?= esc($field['image']) ?>" class="field-img">
                            </td>
                            <td>
                                <strong><?= esc($field['nama']) ?></strong><br>
                                <small style="color:#888;">Owner ID: <?= esc($field['owner_id']) ?></small>
                            </td>
                            <td>Rp <?= number_format($field['harga'], 0, ',', '.') ?></td>
                            <td><?= esc($field['kategori']) ?></td>
                            <td>
                                <a href="#" class="btn-edit">Edit</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding:30px;">
                                Kamu belum mendaftarkan lapangan apapun.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</div>

<?= $this->endSection(); ?>
<?= $this->include('layout/footer'); ?>