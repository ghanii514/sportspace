<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    /* --- CSS UTAMA --- */
    :root {
        --primary-color: #10b981;
        --sidebar-width: 280px;
    }

    body { font-family: 'Poppins', sans-serif; margin:0; background: #f3f4f6; }

    .owner-container {
        display: flex;
        height: 100vh; /* Full Height */
        overflow: hidden; /* Prevent body scroll */
    }

    /* SIDEBAR (Konsisten) */
    .sidebar {
        width: var(--sidebar-width);
        background: #cbffd3ff;
        padding: 30px 20px;
        border-right: 1px solid #49ff64ff;
        display: flex; flex-direction: column;
        flex-shrink: 0;
        z-index: 10;
    }
    .owner-profile { text-align: center; padding-bottom: 25px; border-bottom: 1px solid #f3f4f6; margin-bottom: 25px; }
    .owner-img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 3px solid var(--primary-color); padding: 2px; }
    .menu-link { display: block; padding: 14px 18px; margin-bottom: 8px; color: #6b7280; text-decoration: none; border-radius: 12px; font-weight: 500; transition: 0.3s; }
    .menu-link:hover, .menu-link.active { background-color: #ecfdf5; color: #059669; font-weight: 600; }

    /* --- LAYOUT CHAT --- */
    .chat-main-wrapper {
        display: flex;
        flex: 1;
        background: #fff;
        height: 100%;
    }

    /* Kiri: Daftar Chat */
    .chat-list-container {
        width: 380px;
        background: #fff;
        border-right: 1px solid #e5e7eb;
        display: flex;
        flex-direction: column;
    }

    .chat-list-header {
        padding: 20px 25px;
        border-bottom: 1px solid #f0f0f0;
        background: #fff;
    }

    .chat-items {
        overflow-y: auto;
        flex: 1;
    }

    .chat-item {
        display: flex;
        padding: 15px 25px;
        border-bottom: 1px solid #1dea76ff;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        transition: 0.2s;
        align-items: center;
    }

    .chat-item:hover { background-color: #f9fafb; }

    .chat-item.active {
        background: #ecfdf5;
        border-right: 3px solid var(--primary-color);
    }

    .chat-item-avatar {
        width: 50px; height: 50px;
        border-radius: 50%; margin-right: 15px;
        object-fit: cover;
        border: 1px solid #eee;
    }

    .chat-name { font-weight: 600; font-size: 0.95em; color: #333; }

    .venue-tag {
        font-size: 0.7em; background: #f3f4f6;
        padding: 3px 8px; border-radius: 10px;
        color: #666; font-weight: 500;
        margin-top: 5px; display: inline-block;
    }

    /* Kanan: Jendela Chat */
    .chat-window-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        background-color: #f0f2f5; /* Warna background WA Web modern */
        position: relative;
    }

    /* Header Chat Aktif */
    .chat-active-header {
        padding: 15px 25px;
        background: #87ffa3ff;
        border-bottom: 1px solid #e5e7eb;
        display: flex; align-items: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        z-index: 5;
    }

    /* Area Pesan */
    .messages-area {
        flex: 1;
        padding: 25px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        background-image: radial-gradient(#cbd5e1 1px, transparent 1px); /* Pola titik halus */
        background-size: 20px 20px;
    }

    /* Bubble Chat */
    .message-bubble {
        max-width: 65%;
        padding: 12px 18px;
        border-radius: 18px; /* Lebih bulat */
        margin-bottom: 8px;
        font-size: 0.95em;
        line-height: 1.5;
        position: relative;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .message-bubble.user {
        align-self: flex-start;
        background: #fff;
        border-bottom-left-radius: 4px; /* Aksen sudut */
        color: #1f2937;
    }

    .message-bubble.owner {
        align-self: flex-end;
        background: #10b981; /* Primary Color */
        color: #fff;
        border-bottom-right-radius: 4px; /* Aksen sudut */
    }

    .msg-time {
        font-size: 0.65em; margin-top: 4px; text-align: right; opacity: 0.7;
    }
    .message-bubble.user .msg-time { color: #9ca3af; }
    .message-bubble.owner .msg-time { color: #ecfdf5; }

    /* Footer Input */
    .chat-footer {
        padding: 20px;
        background: #fff;
        display: flex; align-items: center;
        border-top: 1px solid #e5e7eb;
    }

    .message-input {
        flex: 1;
        padding: 15px 20px;
        border-radius: 30px;
        border: 1px solid #e5e7eb;
        outline: none;
        margin-right: 15px;
        background: #f9fafb;
        font-family: 'Poppins', sans-serif;
        transition: 0.2s;
    }

    .message-input:focus {
        background: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .send-btn {
        background: var(--primary-color);
        color: white; border: none;
        width: 50px; height: 50px;
        border-radius: 50%;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: 0.2s;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
    }

    .send-btn:hover { background: #059669; transform: scale(1.05); }

</style>

<div class="owner-container">

    <aside class="sidebar">
        <div class="owner-profile">
            <img src="/img/users/<?= user()->profile_picture ?? 'default.png' ?>" class="owner-img">
            <h4 style="margin:0;"><?= esc(user()->username) ?></h4>
            <span style="font-size:0.85em; color:#888;">Pemilik Lapangan</span>
        </div>

        <nav>
            <a href="/owner" class="menu-link">📊 Dashboard Utama</a>
            <a href="/owner/bookings" class="menu-link">📅 Daftar Booking</a>
            <a href="/owner/chat" class="menu-link active">💬 Chat User</a>

            <hr style="border:0; border-top:1px solid #eee; margin: 20px 0;">
            <a href="/logout" class="menu-link" style="color:#ef4444;">🚪 Keluar</a>
        </nav>
    </aside>

    <div class="chat-main-wrapper">
        <section class="chat-list-container">
            <div class="chat-list-header">
                <h3 style="margin:0; font-weight:700;">Pesan Masuk</h3>
            </div>
            <div class="chat-items">
                <?php
                $activeRoomId = null; 
                if (!empty($chatList)):
                    foreach ($chatList as $chat):
                        if ($chat['active']) {
                            $activeRoomId = $chat['id'];
                        }
                        ?>
                        <a href="<?= base_url('owner/chat/' . $chat['id']) ?>"
                            class="chat-item <?= $chat['active'] ? 'active' : ''; ?>">
                            <img src="<?= $chat['avatar']; ?>" class="chat-item-avatar">
                            <div style="flex:1; overflow:hidden;">
                                <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                                    <span class="chat-name"><?= esc($chat['name']); ?></span>
                                    <span style="font-size:0.7em; color:#999;"><?= $chat['time']; ?></span>
                                </div>
                                <p style="font-size:0.85em; color:#6b7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin:0;">
                                    <?= esc($chat['last_message']); ?>
                                </p>
                                <div class="venue-tag"><?= $chat['venue']; ?></div>
                            </div>
                        </a>
                    <?php endforeach;
                else: ?>
                    <div style="text-align:center; padding:40px; color:#999;">
                        Belum ada percakapan.
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <main class="chat-window-container">
            <?php if (isset($activeChat['user']['name'])): ?>
                
                <div class="chat-active-header">
                    <img src="<?= $activeChat['user']['avatar']; ?>" style="width:45px; height:45px; border-radius:50%; margin-right:15px; object-fit:cover; border:1px solid #eee;">
                    <div>
                        <h4 style="margin:0; font-size:1.1em; font-weight:600;"><?= esc($activeChat['user']['name']); ?></h4>
                        <span style="font-size:0.8em; color:#666;"><?= $activeChat['user']['venue']; ?></span>
                    </div>
                </div>

                <div class="messages-area" id="msgArea">
                    <?php if (!empty($activeChat['messages'])): ?>
                        <?php foreach ($activeChat['messages'] as $msg): ?>
                            <?php if (isset($msg['type']) && $msg['type'] === 'separator'): ?>
                                <div style="text-align:center; margin:15px 0;">
                                    <span style="background:#e2e8f0; color:#64748b; padding:4px 12px; border-radius:12px; font-size:0.75em; font-weight:500;"><?= $msg['text']; ?></span>
                                </div>
                            <?php else: ?>
                                <div class="message-bubble <?= $msg['type']; ?>">
                                    <?= esc($msg['text']); ?>
                                    <div class="msg-time">
                                        <?= $msg['time']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="text-align:center; margin-top:50px; color:#94a3b8;">
                            <p>Mulai percakapan dengan pelanggan.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <form class="chat-footer" id="chatForm"> 
                    <input type="hidden" name="room_id" id="roomIdInput" value="<?= $activeRoomId ?>">
                    
                    <input type="text" name="message" id="messageInput" placeholder="Ketik pesan balasan..." class="message-input" required autocomplete="off">
                    
                    <button type="submit" class="send-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </form>

            <?php else: ?>
                <div style="flex:1; display:flex; align-items:center; justify-content:center; flex-direction:column; color:#9ca3af;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.3; margin-bottom:20px;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    <h3 style="margin-bottom:5px; font-weight:600;">Selamat Datang</h3>
                    <p>Pilih chat di sebelah kiri untuk melihat pesan.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script>
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const roomIdInput = document.getElementById('roomIdInput'); 
    const msgArea = document.getElementById("msgArea");

    let currentRoomId = roomIdInput ? roomIdInput.value : null;

    function scrollToBottom() {
        if (msgArea) {
            msgArea.scrollTop = msgArea.scrollHeight;
        }
    }

    scrollToBottom();

    if (chatForm) {
        chatForm.addEventListener('submit', function (e) {
            e.preventDefault(); 

            const message = messageInput.value;
            const roomId = roomIdInput.value;

            if (!message.trim()) return;

            let formData = new FormData();
            formData.append('message', message);
            formData.append('room_id', roomId);

            fetch('<?= base_url('owner/chat/send') ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    messageInput.value = ''; 
                    loadMessages(); 
                    setTimeout(scrollToBottom, 300);
                } else {
                    console.error("Gagal mengirim:", data);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    function loadMessages() {
        if (!currentRoomId) return; 

        fetch('<?= base_url('owner/api/messages/') ?>' + currentRoomId)
            .then(response => response.json())
            .then(data => {
                let html = '';

                if (data.length === 0) {
                    html = '<div style="text-align:center; margin-top:50px; color:#888;"><p>Belum ada riwayat pesan.</p></div>';
                } else {
                    data.forEach(msg => {
                        let bubbleClass = (msg.type === 'owner') ? 'owner' : 'user';

                        html += `
                            <div class="message-bubble ${bubbleClass}">
                                ${escapeHtml(msg.text)}
                                <div class="msg-time">
                                    ${msg.time}
                                </div>
                            </div>
                        `;
                    });
                }

                if (msgArea) {
                    const isScrolledToBottom = msgArea.scrollHeight - msgArea.scrollTop <= msgArea.clientHeight + 150;
                    msgArea.innerHTML = html; 
                    if (isScrolledToBottom) {
                        scrollToBottom();
                    }
                }
            })
            .catch(error => console.error('Error fetching messages:', error));
    }

    function escapeHtml(text) {
        if (!text) return "";
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    if (currentRoomId) {
        setInterval(loadMessages, 2000); 
    }
</script>