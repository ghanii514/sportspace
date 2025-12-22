<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Promo - Admin</title>
    <style>
        /* === STYLE UTAMA (KONSISTEN DENGAN HALAMAN LAIN) === */
        body { font-family: sans-serif; margin: 0; display: flex; height: 100vh; background: #f4f6f9; }
        
        .sidebar { width: 250px; background: #1e293b; color: white; padding: 20px; display: flex; flex-direction: column; }
        .sidebar h2 { margin-top: 0; margin-bottom: 30px; color: #39E07A; text-align: center; }
        
        .menu-link { text-decoration: none; color: #cbd5e1; padding: 12px 15px; border-radius: 8px; margin-bottom: 5px; display: block; transition: 0.2s; }
        .menu-link:hover, .menu-link.active { background: #334155; color: #39E07A; font-weight: bold; }
        .logout-btn { margin-top: auto; background: #e11d48; color: white; text-align: center; }

        .content { flex: 1; padding: 30px; overflow-y: auto; }

        /* FORM STYLE */
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            max-width: 800px;
        }

        .form-group { margin-bottom: 20px; }
        
        label { display: block; font-weight: bold; margin-bottom: 8px; color: #334155; }
        
        input[type="text"], 
        input[type="number"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: 0.2s;
        }

        input:focus, textarea:focus {
            border-color: #39E07A;
            outline: none;
            box-shadow: 0 0 0 3px rgba(57, 224, 122, 0.1);
        }

        textarea { height: 100px; resize: vertical; }

        /* PREVIEW GAMBAR LAMA */
        .current-img-box {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            display: inline-block;
            background: #f8fafc;
        }
        .current-img-box img {
            max-width: 200px;
            border-radius: 5px;
            display: block;
            margin-bottom: 5px;
        }
        .current-img-box small { color: #64748b; }

        /* TOMBOL */
        .btn-group { display: flex; gap: 15px; margin-top: 30px; }
        
        .btn-save {
            background: #39E07A; color: white; border: none; padding: 12px 25px;
            border-radius: 8px; font-size: 1rem; font-weight: bold; cursor: pointer;
            transition: 0.2s;
        }
        .btn-save:hover { background: #2dbc66; transform: translateY(-2px); }

        .btn-cancel {
            background: #e2e8f0; color: #475569; text-decoration: none; padding: 12px 25px;
            border-radius: 8px; font-weight: bold; display: inline-block;
            transition: 0.2s;
        }
        .btn-cancel:hover { background: #cbd5e1; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>SportSpace Admin</h2>
        <a href="/admin" class="menu-link">Dashboard</a>
        <a href="/admin/fields" class="menu-link">Kelola Lapangan</a>
        <a href="/admin/promos" class="menu-link active">Kelola Promo</a>
        <a href="/" class="menu-link" style="margin-top:auto; border-top: 1px solid #334155;">&larr; Lihat Website</a>
    </div>

    <div class="content">
        <h1 style="color: #1e293b; margin-top: 0;">Edit Data Promo</h1>

        <div class="form-card">
            <form action="/admin/promos/update/<?= $promo['id'] ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                
                <input type="hidden" name="id" value="<?= $promo['id'] ?>">
                <input type="hidden" name="old_image" value="<?= $promo['image'] ?>">

                <div class="form-group">
                    <label>Judul Promo</label>
                    <input type="text" name="promo" value="<?= esc($promo['promo']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Kode Promo (Voucher)</label>
                    <input type="text" name="promo_code" value="<?= esc($promo['promo_code']) ?>" required style="font-family: monospace; letter-spacing: 1px;">
                </div>

                <div class="form-group">
                    <label>Jumlah Diskon (%)</label>
                    <input type="number" name="jumlah_diskon" value="<?= esc($promo['jumlah_diskon']) ?>" min="1" max="100" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi Promo</label>
                    <textarea name="deskripsi" required><?= esc($promo['deskripsi']) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Banner Promo</label>
                    
                    <?php if(!empty($promo['image'])): ?>
                        <div class="current-img-box">
                            <img src="<?= base_url('img/promo/' . $promo['image']) ?>" alt="Gambar Lama">
                            <small>Gambar saat ini</small>
                        </div>
                    <?php endif; ?>

                    <input type="file" name="image" accept="image/*">
                    <small style="color: #64748b;">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn-save">Simpan Perubahan</button>
                    <a href="/admin/promos" class="btn-cancel">Batal</a>
                </div>

            </form>
        </div>
    </div>

</body>
</html>