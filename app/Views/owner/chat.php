<?= $this->include('layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    /* Container chat */
    .chat-wrapper {
        display: flex;
        height: 100vh;
        background: #f3f4f6;
        width: 100%;
    }

    /* LIST USER CHAT */
    .chat-list {
        width: 320px;
        background: white;
        border-right: 1px solid #e5e7eb;
        overflow-y: auto;
        padding: 20px;
    }

    .user-card {
        display: flex;
        align-items: center;
        padding: 12px;
        background: #f9fafb;
        border-radius: 10px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: 0.2s;
    }

    .user-card:hover {
        background: #ecfdf5;
    }

    .user-card img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 12px;
    }

    /* CHAT ROOM */
    .chat-room {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .chat-room-header {
        padding: 15px;
        background: white;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }

    .messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #e5e7eb;
    }

    .msg {
        padding: 10px 15px;
        background: white;
        border-radius: 12px;
        margin-bottom: 10px;
        max-width: 70%;
        font-size: 15px;
        line-height: 1.4em;
    }

    .me {
        background: #d1fae5;
        margin-left: auto;
    }

    .chat-input {
        display: flex;
        border-top: 1px solid #ddd;
        padding: 12px;
        background: white;
    }

    .chat-input input {
        flex: 1;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
    }

    .chat-input button {
        margin-left: 10px;
        padding: 10px 18px;
        background: #059669;
        border: none;
        border-radius: 8px;
        color: white;
        cursor: pointer;
    }

    .owner-container {
        display: flex;
        min-height: 100vh;
        background-color: #f3f4f6;
    }

    /* === SIDEBAR === */
    .sidebar {
        width: 260px;
        background: #fff;
        padding: 20px;
        border-right: 1px solid #e5e7eb;
        position: sticky;
        top: 0;
        height: 100vh;
    }

    .owner-profile {
        text-align: center;
        padding-bottom: 20px;
        border-bottom: 1px solid #f3f4f6;
        margin-bottom: 20px;
    }

    .owner-img {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
        border: 2px solid #39E07A;
    }

    .menu-link {
        display: block;
        padding: 12px 15px;
        margin-bottom: 8px;
        color: #4b5563;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.2s;
    }

    .menu-link:hover, .menu-link.active {
        background-color: #ecfdf5;
        color: #059669; /* Hijau agak gelap */
    }

    /* === CONTENT === */
    .main-content {
        flex: 1;
        padding: 30px;
    }

    /* Kartu Ringkasan */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .card h3 { margin: 0; font-size: 2em; color: #1f2937; }
    .card p { margin: 0; color: #6b7280; font-size: 0.9em; }

    /* Tabel */
    .table-container {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th { text-align: left; padding: 12px; background: #f9fafb; color: #6b7280; font-size: 0.85em; }
    td { padding: 12px; border-bottom: 1px solid #f3f4f6; color: #374151; font-size: 0.95em; }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75em;
        font-weight: bold;
    }
    .bg-pending { background: #fef3c7; color: #92400e; }
    .bg-success { background: #d1fae5; color: #065f46; }
    .bg-cancel { background: #fee2e2; color: #991b1b; }
</style>

<div class="owner-container">

    <!-- SIDEBAR TETAP -->
    <?= $this->include('owner/sidebar/sidebar'); ?>

    <!-- MAIN CHAT AREA -->
    <div class="chat-wrapper">

        <!-- ====================== LIST USER CHAT ======================== -->
        <aside class="chat-list">
            <h3 style="margin-bottom:15px;">Chat User</h3>

            <?php if (!empty($chat_users)): ?>
                <?php foreach ($chat_users as $u): ?>
                    <a href="/owner/chat/<?= $u['user_id']; ?>" style="text-decoration:none; color:inherit;">
                        <div class="user-card">
                            <img src="/img/users/<?= $u['profile_picture'] ?? 'default.png'; ?>">
                            <div>
                                <p style="margin:0; font-weight:600;"><?= esc($u['username']); ?></p>
                                <small style="color:#6b7280;">
                                    <?= $u['last_message_time'] ? date('d M H:i', strtotime($u['last_message_time'])) : 'Belum ada pesan'; ?>
                                </small>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color:#999;">Belum ada user yang chat.</p>
            <?php endif; ?>
        </aside>

        <!-- ===================== CHAT ROOM ===================== -->
        <div class="chat-room">

            <?php if (!isset($active_user)): ?>

                <div class="messages" style="display:flex; justify-content:center; align-items:center;">
                    <p style="color:#6b7280;">Pilih user untuk mulai chat</p>
                </div>

            <?php else: ?>

                <!-- HEADER -->
                <div class="chat-room-header">
                    Chat dengan: <?= esc($active_user['username']); ?>
                </div>

                <!-- PESAN -->
                <div class="messages">
                    <?php foreach ($messages as $m): ?>
                        <div class="msg <?= $m['sender'] === 'owner' ? 'me' : '' ?>">
                            <?= esc($m['message']); ?>
                            <div style="font-size:11px; color:#555; margin-top:5px;">
                                <?= date('H:i', strtotime($m['created_at'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- INPUT KIRIM PESAN -->
                <form class="chat-input" method="POST" action="/owner/chat/send">
                    <input type="hidden" name="room_id" value="<?= $room_id; ?>">
                    <input type="text" name="message" placeholder="Ketik pesan..." required>
                    <button type="submit">Kirim</button>
                </form>

            <?php endif; ?>

        </div>

    </div>
</div>

<?= $this->renderSection('content'); ?>
