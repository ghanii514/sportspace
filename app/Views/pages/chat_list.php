<?= $this->include('layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    .chat-container {
        max-width: 900px;
        margin: 30px auto;
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 80vh;
    }

    /* Header */
    .chat-header {
        padding: 15px 20px;
        background: #49e265;
        color: white;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 18px;
    }

    .chat-header img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        border: 2px solid #fff;
        object-fit: cover;
    }

    /* Messages Area */
    .chat-body {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #f3f4f6;
        display: flex;
        flex-direction: column; /* Penting untuk alignment bubble */
    }

    .msg {
        max-width: 70%;
        padding: 10px 15px;
        margin-bottom: 12px;
        border-radius: 12px;
        font-size: 15px;
        line-height: 1.4;
        position: relative;
    }

    /* Bubble dari Owner (Kiri) */
    .msg-owner {
        background: white;
        align-self: flex-start;
        border-bottom-left-radius: 2px;
    }

    /* Bubble dari User/Anda (Kanan) */
    .msg-user {
        background: #d1fae5;
        align-self: flex-end;
        border-bottom-right-radius: 2px;
    }

    .msg-time {
        display: block;
        font-size: 10px;
        color: #888;
        margin-top: 5px;
        text-align: right;
    }

    /* Form Input */
    .chat-footer {
        display: flex;
        padding: 12px;
        background: white;
        border-top: 1px solid #e5e7eb;
    }

    .chat-footer input {
        flex: 1;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid #ccc;
        font-size: 15px;
        outline: none;
    }

    .chat-footer button {
        margin-left: 10px;
        padding: 12px 25px;
        background: #059669;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }
</style>

<div class="chat-container">

    <div class="chat-header">
        <img src="/img/users/<?= $owner['profile_picture'] ?? 'default.png' ?>">
        <div>
            <div style="font-size: 16px;"><?= esc($owner['username']) ?></div>
            <div style="font-size: 11px; font-weight: normal; opacity: 0.9;">Pemilik Lapangan</div>
        </div>
    </div>

    <div class="chat-body" id="chatBox">

        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $m): ?>
                <div class="msg <?= $m['type'] == 'user' ? 'msg-user' : 'msg-owner' ?>">
                    <?= esc($m['message']) ?>
                    <span class="msg-time"><?= date('H:i', strtotime($m['created_at'])) ?></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align:center; margin-top:40px; color:#777;">
                <p>Belum ada percakapan.</p>
                <small>Mulai kirim pesan untuk bertanya pada pemilik lapangan.</small>
            </div>
        <?php endif; ?>

    </div>

    <form class="chat-footer" method="POST" action="/chat/send">
        <input type="hidden" name="room_id" value="<?= $owner['id']; ?>">
        <input type="hidden" name="owner_id" value="<?= $owner['user_id']; ?>">

        <input type="text" name="message" placeholder="Ketik pesan..." required autocomplete="off">
        <button type="submit">Kirim</button>
    </form>

</div>

<script>
    // Auto scroll ke pesan paling bawah saat halaman dimuat
    let chatBox = document.getElementById("chatBox");
    chatBox.scrollTop = chatBox.scrollHeight;
</script>

<?= $this->renderSection('content'); ?>
<?= $this->include('layout/footer') ?>