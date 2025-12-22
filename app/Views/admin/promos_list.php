<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Promo - Admin</title>
    <style>
        /* === STYLE UTAMA (SAMA PERSIS DENGAN FIELDS & DASHBOARD) === */
        body { font-family: sans-serif; margin: 0; display: flex; height: 100vh; background: #f4f6f9; }
        
        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background: #1e293b;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        .sidebar h2 { margin-top: 0; margin-bottom: 30px; color: #39E07A; text-align: center; }
        
        .menu-link {
            text-decoration: none;
            color: #cbd5e1;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 5px;
            display: block;
            transition: 0.2s;
        }
        
        /* Hover & Active Effect */
        .menu-link:hover, .menu-link.active {
            background: #334155;
            color: #39E07A;
            font-weight: bold;
        }

        .logout-btn {
            margin-top: auto;
            background: #e11d48;
            color: white;
            text-align: center;
        }
        .logout-btn:hover { background: #be123c; }

        /* CONTENT AREA */
        .content { flex: 1; padding: 30px; overflow-y: auto; }

        /* === STYLE KHUSUS TABEL & TOMBOL === */
        
        /* Tabel */
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; vertical-align: middle; }
        th { background: #f8fafc; color: #475569; font-weight: 700; text-transform: uppercase; font-size: 0.85em; letter-spacing: 0.5px; }
        tr:last-child td { border-bottom: none; }
        tr:hover { background-color: #fcfcfc; }

        /* Gambar Promo (Sedikit lebih lebar dari lapangan) */
        .table-img {
            width: 80px;
            height: 45px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #eee;
        }

        /* Tombol Aksi */
        .btn { padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 0.9em; display: inline-block; font-weight: 600; transition: 0.2s; }
        
        .btn-add { 
            background: #39E07A; 
            color: white; 
            margin-bottom: 25px; 
            padding: 12px 20px;
            font-size: 1em;
            box-shadow: 0 4px 6px rgba(57, 224, 122, 0.3);
        }
        .btn-add:hover { background: #2dbc66; transform: translateY(-2px); }

        .btn-edit { background: #f59e0b; color: white; margin-right: 5px; }
        .btn-edit:hover { background: #d97706; }

        .btn-delete { background: #ef4444; color: white; }
        .btn-delete:hover { background: #dc2626; }

        /* Badge Kode Promo */
        .code-badge {
            background: #e0f2fe;
            color: #0284c7;
            padding: 5px 10px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            border: 1px dashed #7dd3fc;
            font-size: 0.9em;
        }

        /* Badge Diskon (Baru) */
        .discount-badge {
            background: #dcfce7;
            color: #166534;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9em;
        }

        /* Alert Sukses */
        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #bbf7d0;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>SportSpace Admin</h2>
        
        <a href="/admin" class="menu-link">Dashboard</a>
        <a href="/admin/fields" class="menu-link">Kelola Lapangan</a>
        
        <a href="/admin/promos" class="menu-link active">Kelola Promo</a>
        
        <a href="/" class="menu-link" style="margin-top: 30px; border-top: 1px solid #334155;">&larr; Lihat Website</a>
        <a href="/logout" class="menu-link logout-btn">Logout</a>
    </div>

    <div class="content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 style="color: #1e293b; margin: 0;">Daftar Promo</h1>
        </div>

        <a href="/admin/promos/create" class="btn btn-add">+ Tambah Promo Baru</a>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th width="100">Gambar</th>
                    <th>Judul Promo</th>
                    <th>Deskripsi</th>
                    <th>Kode Promo</th>
                    <th>Diskon</th> <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($promos)): ?>
                    <?php foreach($promos as $item): ?>
                    <tr>
                        <td>
                            <img src="/img/promo/<?= esc($item['image'] ?? 'default.jpg') ?>" class="table-img" alt="Promo">
                        </td>
                        <td>
                            <strong style="font-size: 1.05em;"><?= esc($item['promo']) ?></strong>
                        </td>
                        <td>
                            <span style="color: #64748b; font-size: 0.9em;">
                                <?= esc(substr($item['deskripsi'], 0, 50)) ?>...
                            </span>
                        </td>
                        <td>
                            <span class="code-badge"><?= esc($item['promo_code']) ?></span>
                        </td>
                        
                        <td>
                            <span class="discount-badge">
                                <?= esc($item['jumlah_diskon']) ?>%
                            </span>
                        </td>
                        <td>
                            <a href="/admin/promos/edit/<?= $item['id'] ?>" class="btn btn-edit">Edit</a>
                            <a href="/admin/promos/delete/<?= $item['id'] ?>" class="btn btn-delete" onclick="return confirm('Yakin hapus promo ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px; color: #94a3b8;">
                            Belum ada data promo saat ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>