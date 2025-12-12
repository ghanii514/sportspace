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

    /* Messages */
    .chat-body {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #f3f4f6;
    }

    .msg {
        max-width: 70%;
        padding: 10px 15px;
        margin-bottom: 12px;
        border-radius: 12px;
        font-size: 15px;
        line-height: 1.4;
    }

    .msg-owner {
        background: white;
        align-self: flex-start;
    }

    .msg-user {
        background: #d1fae5;
        align-self: flex-end;
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

    <!-- HEADER CHAT -->
    <div class="chat-header">
        <img src="/img/users/default.png">
        Chat dengan Pemilik Lapangan
    </div>

    <!-- AREA PESAN -->
    <div class="chat-body" id="chatBox">

        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $m): ?>

                <div class="msg <?= $m['sender'] == 'user' ? 'msg-user' : 'msg-owner' ?>">
                    <?= esc($m['message']) ?>
                </div>

            <?php endforeach; ?>
        <?php else: ?>

            <p style="text-align:center; margin-top:40px; color:#777;">Belum ada pesan</p>

        <?php endif; ?>

    </div>

    <!-- FORM INPUT -->
    <form class="chat-footer" method="POST" action="/user/chat/send">
        <input type="hidden" name="room_id" value="<?= $room['id']; ?>">

        <input type="text" name="message" placeholder="Ketik pesan..." required>
        <button type="submit">Kirim</button>
    </form>

</div>

<!-- Auto scroll ke bawah -->
<script>
    let chatBox = document.getElementById("chatBox");
    chatBox.scrollTop = chatBox.scrollHeight;
</script>

<?= $this->renderSection('content'); ?>
