<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Admin</title>
    <style>
        body { font-family: sans-serif; margin: 0; display: flex; height: 100vh; background: #f4f6f9; }
        
        .sidebar { width: 250px; background: #1e293b; color: white; padding: 20px; display: flex; flex-direction: column; }
        .sidebar h2 { margin-top: 0; margin-bottom: 20px; color: #39E07A; text-align: center; }
        
        .admin-profile { text-align: center; padding-bottom: 20px; border-bottom: 1px solid #334155; margin-bottom: 20px; }
        .admin-img { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; margin-bottom: 10px; border: 2px solid #39E07A; }
        
        .menu-link { text-decoration: none; color: #cbd5e1; padding: 12px 15px; border-radius: 8px; margin-bottom: 5px; display: block; transition: 0.2s; }
        .menu-link:hover, .menu-link.active { background: #334155; color: #39E07A; font-weight: bold; }
        .logout-btn { margin-top: auto; background: #e11d48; color: white; text-align: center; }

        .content { flex: 1; padding: 30px; overflow-y: auto; }

        .form-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); max-width: 600px; }
        .form-group { margin-bottom: 20px; }
        
        label { display: block; font-weight: bold; margin-bottom: 8px; color: #334155; }
        
        input[type="text"], input[type="email"], input[type="file"] {
            width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; box-sizing: border-box; transition: 0.2s;
        }
        input:focus { border-color: #39E07A; outline: none; box-shadow: 0 0 0 3px rgba(57, 224, 122, 0.1); }

        .btn-group { display: flex; gap: 15px; margin-top: 30px; }
        .btn-save { background: #39E07A; color: white; border: none; padding: 12px 25px; border-radius: 8px; font-size: 1rem; font-weight: bold; cursor: pointer; transition: 0.2s; }
        .btn-save:hover { background: #2dbc66; transform: translateY(-2px); }
        .btn-cancel { background: #e2e8f0; color: #475569; text-decoration: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; display: inline-block; transition: 0.2s; }
        .btn-cancel:hover { background: #cbd5e1; }

        .current-img { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #e2e8f0; margin-bottom: 10px; display: block; }
        .alert-success { background: #dcfce7; color: #166534; padding: 15px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 15px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #fecaca; }
        .text-danger { color: #ef4444; font-size: 0.9em; margin-top: 5px; display: block; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>SportSpace Admin</h2>
        
        <div class="admin-profile">
            <img src="/img/users/<?= user()->profile_picture ?? 'default.png' ?>" class="admin-img">
            <h4 style="margin:0; color:white;"><?= esc(user()->username) ?></h4>
            <span style="font-size:0.85em; color:#94a3b8;">Administrator</span>
        </div>
        
        <a href="/admin" class="menu-link">Dashboard</a>
        <a href="/admin/fields" class="menu-link">Kelola Lapangan</a>
        <a href="/admin/promos" class="menu-link">Kelola Promo</a>
        <a href="/admin/profile" class="menu-link active">Edit Profil</a>
        <a href="/" class="menu-link" style="margin-top:auto; border-top: 1px solid #334155;">&larr; Lihat Website</a>
        <a href="/logout" class="menu-link logout-btn">Logout</a>
    </div>

    <div class="content">
        <h1 style="color: #1e293b; margin-top: 0;">Edit Profil</h1>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert-error">
                <?php foreach(session()->getFlashdata('errors') as $e): ?>
                    <?= esc($e) ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <form action="/admin/profile/update" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>

                <div style="text-align: center; margin-bottom: 25px;">
                    <img src="/img/users/<?= user()->profile_picture ?? 'default.png' ?>" class="current-img">
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?= esc(user()->username) ?>" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= esc(user()->email) ?>" required>
                </div>

                <div class="form-group">
                    <label>Foto Profil (Opsional)</label>
                    <input type="file" name="profile_picture" accept="image/*">
                    <small style="color: #64748b;">Format: JPG/PNG. Maksimal 1MB. Biarkan kosong jika tidak ingin mengganti.</small>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn-save">Simpan Perubahan</button>
                    <a href="/admin" class="btn-cancel">Batal</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
