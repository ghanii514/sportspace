<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Lapangan - Admin</title>
    <style>
        /* === STYLE UTAMA (SAMA DENGAN DASHBOARD) === */
        body { font-family: sans-serif; margin: 0; display: flex; height: 100vh; background: #f4f6f9; }
        
        /* SIDEBAR */
        .sidebar { width: 250px; background: #1e293b; color: white; padding: 20px; display: flex; flex-direction: column; }
        .sidebar h2 { margin-top: 0; margin-bottom: 30px; color: #39E07A; text-align: center; }
        
        .menu-link { text-decoration: none; color: #cbd5e1; padding: 12px 15px; border-radius: 8px; margin-bottom: 5px; display: block; transition: 0.2s; }
        .menu-link:hover, .menu-link.active { background: #334155; color: #39E07A; font-weight: bold; }
        .logout-btn { margin-top: auto; background: #e11d48; color: white; text-align: center; }

        /* CONTENT */
        .content { flex: 1; padding: 30px; overflow-y: auto; }

        /* === FORM STYLE === */
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            max-width: 800px; /* Lebar maksimal form */
        }

        .form-group { margin-bottom: 20px; }
        
        label { display: block; font-weight: bold; margin-bottom: 8px; color: #334155; }
        
        input[type="text"], 
        input[type="number"], 
        select, 
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box; /* Agar padding gak bikin melebar */
            transition: 0.2s;
        }

        input:focus, textarea:focus, select:focus {
            border-color: #39E07A;
            outline: none;
            box-shadow: 0 0 0 3px rgba(57, 224, 122, 0.1);
        }

        textarea { height: 120px; resize: vertical; }

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

        /* Error Message */
        .text-danger { color: #ef4444; font-size: 0.9em; margin-top: 5px; display: block; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>SportSpace Admin</h2>
        <a href="/admin" class="menu-link">Dashboard</a>
        <a href="/admin/fields" class="menu-link active">Kelola Lapangan</a>
        <a href="/admin/promos" class="menu-link">Kelola Promo</a>
        <a href="/admin/bookings" class="menu-link">Cek Booking</a>
        <a href="/" class="menu-link" style="margin-top:auto; border-top: 1px solid #334155;">&larr; Lihat Website</a>
    </div>

    <div class="content">
        <h1 style="color: #1e293b; margin-top: 0;">Tambah Lapangan Baru</h1>

        <div class="form-card">
            <form action="/admin/fields/save" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>

                <div class="form-group">
                    <label>Nama Lapangan</label>
                    <input type="text" name="nama" placeholder="Contoh: Lapangan Futsal Champions" required>
                </div>

                <div style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Kategori</label>
                        <select name="kategori" required>
                            <option value="Futsal">Futsal</option>
                            <option value="Basket">Basket</option>
                            <option value="Badminton">Badminton</option>
                            <option value="Voli">Voli</option>
                            <option value="Tenis">Tenis</option>
                            <option value="Tenis">Sepak Bola</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Harga per Jam (Rp)</label>
                        <input type="number" name="harga" placeholder="Contoh: 150000" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea name="alamat" placeholder="Masukkan alamat lengkap venue..." required></textarea>
                </div>

                <div class="form-group">
                    <label>Deskripsi & Fasilitas</label>
                    <textarea name="deskripsi" placeholder="Jelaskan fasilitas (Parkir, Toilet, Rumput Sintetis, dll)..."></textarea>
                </div>

                <div class="form-group">
                    <label>Foto Lapangan</label>
                    <input type="file" name="image" accept="image/*" required>
                    <small style="color: #64748b;">Format: JPG/PNG. Maksimal 2MB.</small>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn-save">Simpan Data</button>
                    <a href="/admin/fields" class="btn-cancel">Batal</a>
                </div>

            </form>
        </div>
    </div>

</body>
</html>