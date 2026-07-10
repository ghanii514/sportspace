<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SportSpace</title>
    <style>
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
        .sidebar h2 { margin-top: 0; margin-bottom: 20px; color: #39E07A; text-align: center; }
        .admin-profile { text-align: center; padding-bottom: 20px; border-bottom: 1px solid #334155; margin-bottom: 20px; }
        .admin-img { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; margin-bottom: 10px; border: 2px solid #39E07A; }
        .menu-link {
            text-decoration: none;
            color: #cbd5e1;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 5px;
            display: block;
        }
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

        /* CONTENT */
        .content { flex: 1; padding: 30px; overflow-y: auto; }
        
        .card-container { display: flex; gap: 20px; margin-top: 20px; }
        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            flex: 1;
            text-align: center;
        }
        .card h3 { margin: 0 0 10px 0; color: #64748b; font-size: 1rem; }
        .card .number { font-size: 2.5rem; font-weight: bold; color: #0f172a; margin: 0; }
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
        
        <a href="/admin" class="menu-link active">Dashboard</a>
        <a href="/admin/fields" class="menu-link">Kelola Lapangan</a>
        <a href="/admin/promos" class="menu-link">Kelola Promo</a>
        <a href="/admin/profile" class="menu-link">Edit Profil</a>
        
        <a href="/" class="menu-link" style="margin-top: 30px; border-top: 1px solid #334155;">&larr; Lihat Website</a>
        <a href="/logout" class="menu-link logout-btn">Logout</a>
    </div>

    <div class="content">
        <h1>Selamat Datang, Admin <?= user()->username; ?>!</h1>
        <p>Ringkasan data SportSpace hari ini.</p>

        <div class="card-container">
            <div class="card">
                <h3>Total Lapangan</h3>
                <p class="number"><?= $total_fields ?></p>
            </div>
            <div class="card">
                <h3>Total Promo Aktif</h3>
                <p class="number"><?= $total_promos ?></p>
            </div>
        </div>
    </div>

</body>
</html>