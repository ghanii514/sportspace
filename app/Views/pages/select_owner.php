<?= $this->include('layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    .owner-list-container {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
    }

    .owner-card {
        display: flex;
        align-items: center;
        padding: 15px;
        border-radius: 12px;
        background: white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
        margin-bottom: 15px;
        cursor: pointer;
        transition: 0.2s;
    }

    .owner-card:hover {
        background: #f3f4f6;
    }

    .owner-card img {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        margin-right: 15px;
        object-fit: cover;
    }

    .owner-info h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
    }

    .owner-info small {
        color: #555;
    }
</style>

<div class="owner-list-container">
    <h3>Pilih Pihak Lapangan untuk Memulai Chat</h3>
    <br>

    <?php foreach ($owners as $o): ?>
      
        <a href="/chat/start/<?= $o->user_id; ?>" style="text-decoration:none; color:inherit;">
            <div class="owner-card">
                <img src="/img/users/<?= $o->profile_picture ?? 'default.png'; ?>" alt="Foto">
                
                <div class="owner-info">
                    <h4><?= esc($o->username); ?></h4>
                    <small><?= esc($o->nama); ?></small>
                </div>
            </div>
        </a>
    <?php endforeach; ?>

</div>

<?= $this->renderSection('content'); ?>
