<?= $this->include('layout/template'); ?>

<style>
    /* Container Utama */
    .chat-container {
        width: 800px;
        margin: 30px auto;
        background: #fff;
        min-height: 500px;
        border-right: 1px solid #eee;
        border-left: 1px solid #eee;
    }

    .chat-header {
        padding: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .chat-header h1 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 800;
        color: #000;
    }

    /* List Item Chat */
    .chat-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .chat-item {
        display: flex;
        padding: 20px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
        color: inherit;
    }

    .chat-item:hover {
        background-color: #f9f9f9;
    }

    /* Gambar Bulat */
    .chat-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 20px;
        border: 1px solid #ddd;
    }

    .chat-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .chat-top {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .venue-name {
        font-weight: bold;
        font-size: 1.1rem;
        color: #000;
    }

    .chat-date {
        font-size: 0.85rem;
        color: #666;
    }

    .chat-snippet {
        color: #555;
        font-size: 0.95rem;
        /* Biar teks panjang jadi ... */
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .empty-chat {
        text-align: center;
        padding: 50px;
        color: #999;
    }
</style>

<div class="chat-container">
    <div class="chat-header">
        <h1>Chat Pihak Lapangan</h1>
    </div>

    <div class="chat-list">
        <?php if(!empty($messages)): ?>
            <?php foreach($messages as $msg): ?>
                
                <a href="/chat/detail/<?= $msg['id'] ?>" class="chat-item" style="text-decoration:none; color:inherit; display:flex;">
                    <img src="/img/fields/<?= $msg['gambar_lapangan'] ?>" 
                            class="chat-avatar" 
                            onerror="this.src='https://via.placeholder.com/60'">
                            
                    <div class="chat-content">
                        <div class="chat-top">
                            <span class="venue-name"><?= esc($msg['nama_lapangan']) ?></span>
                            <span class="chat-date"><?= date('d/m/y', strtotime($msg['created_at'])) ?></span>
                        </div>
                        <div class="chat-snippet">
                            <?= esc($msg['message']) ?>
                        </div>
                    </div>    
                </a>
        </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-chat">
                <p>Belum ada pesan masuk.</p>
                <small>Pesan akan masuk otomatis saat booking dikonfirmasi.</small>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function showDetail(nama, pesan) {
        // Ganti baris baru (\n) jadi <br> buat SweetAlert atau Alert biasa
        alert("Pesan dari: " + nama + "\n\n" + pesan);
    }
</script>

<?= $this->include('layout/footer'); ?>