<?= $this->section('content'); ?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
:root {
    --primary-color: #10b981;
    --sidebar-width: 280px;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: #f3f4f6;
}

.owner-container {
    display: flex;
    height: 100vh;
    overflow: hidden;
}

.sidebar {
    width: var(--sidebar-width);
    background: #cbffd3ff;
    padding: 30px 20px;
    border-right: 1px solid #49ff64ff;
    display: flex;
    flex-direction: column;
    flex-shrink: 0;
}

.owner-profile {
    text-align: center;
    padding-bottom: 25px;
    border-bottom: 1px solid #f3f4f6;
    margin-bottom: 25px;
}

.owner-img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 15px;
    border: 3px solid var(--primary-color);
    padding: 2px;
}

.menu-link {
    display: block;
    padding: 14px 18px;
    margin-bottom: 8px;
    color: #6b7280;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 500;
    transition: 0.3s;
}

.menu-link:hover,
.menu-link.active {
    background: #ecfdf5;
    color: #059669;
    font-weight: 600;
}

.chat-main-wrapper {
    display: flex;
    flex: 1;
    background: #fff;
}

.chat-list-container {
    width: 380px;
    border-right: 1px solid #e5e7eb;
    display: flex;
    flex-direction: column;
}

.chat-list-header {
    padding: 20px 25px;
    border-bottom: 1px solid #f0f0f0;
}

.chat-items {
    flex: 1;
    overflow-y: auto;
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

.chat-item:hover {
    background: #f9fafb;
}

.chat-item.active {
    background: #ecfdf5;
    border-right: 3px solid var(--primary-color);
}

.chat-item-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 15px;
    object-fit: cover;
    border: 1px solid #eee;
}

.chat-name {
    font-weight: 600;
    font-size: 0.95em;
}

.venue-tag {
    font-size: 0.7em;
    background: #f3f4f6;
    padding: 3px 8px;
    border-radius: 10px;
    color: #666;
    font-weight: 500;
    margin-top: 5px;
    display: inline-block;
}

.chat-window-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #f0f2f5;
}

.chat-active-header {
    padding: 15px 25px;
    background: #87ffa3ff;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 15px;
}

.chat-active-header img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
}

.chat-active-header h4 {
    font-size: 1em;
    font-weight: 600;
}

.chat-active-header p {
    font-size: 0.8em;
    color: #059669;
    font-weight: 500;
}

.messages-area {
    flex: 1;
    padding: 25px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
    background-size: 20px 20px;
}

.message-bubble {
    max-width: 65%;
    padding: 12px 18px;
    border-radius: 18px;
    margin-bottom: 8px;
    font-size: 0.95em;
    line-height: 1.5;
    word-wrap: break-word;
}

.message-bubble.user {
    align-self: flex-start;
    background: #fff;
    border-bottom-left-radius: 4px;
}

.message-bubble.owner {
    align-self: flex-end;
    background: var(--primary-color);
    color: #fff;
    border-bottom-right-radius: 4px;
}

.msg-time {
    font-size: 0.65em;
    margin-top: 4px;
    text-align: right;
    opacity: 0.7;
}

.chat-footer {
    padding: 20px;
    background: #fff;
    display: flex;
    align-items: center;
    border-top: 1px solid #e5e7eb;
    gap: 12px;
}

.message-input {
    flex: 1;
    padding: 15px 20px;
    border-radius: 30px;
    border: 1px solid #e5e7eb;
    background: #f9fafb;
    outline: none;
    font-family: 'Poppins', sans-serif;
    font-size: 0.95em;
}

.message-input:focus {
    background: #fff;
    border-color: var(--primary-color);
}

.send-btn {
    background: var(--primary-color);
    color: #fff;
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.2s;
    flex-shrink: 0;
}

.send-btn:hover {
    background: #059669;
    transform: scale(1.05);
}

.empty-state {
    text-align: center;
    margin-top: 60px;
    color: #9ca3af;
}

.empty-state p {
    font-size: 1.1em;
    margin-bottom: 5px;
}

.empty-state small {
    font-size: 0.85em;
}

.no-chat-selected {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #9ca3af;
    font-size: 1.1em;
}
</style>

<div class="owner-container">
    <aside class="sidebar">
        <div class="owner-profile">
            <img src="/img/fields/<?= $venue_image ?>" class="owner-img">
            <h4 style="margin:0;"><?= esc($venue_names) ?></h4>
            <span style="font-size:0.85em; color:#888;">Pemilik Lapangan</span>
        </div>

        <nav>
            <a href="/owner" class="menu-link">Dashboard Utama</a>
            <a href="/owner/bookings" class="menu-link">Daftar Booking</a>
            <a href="/owner/chat" class="menu-link active">Chat User</a>
            <hr style="border:0; border-top:1px solid #eee; margin: 20px 0;">
            <a href="/logout" class="menu-link" style="color:#ef4444;">Keluar</a>
        </nav>
    </aside>

    <div class="chat-main-wrapper">
        <div class="chat-list-container">
            <div class="chat-list-header">
                <h3 style="margin:0; font-weight:600;">Percakapan</h3>
            </div>
            <div class="chat-items">
                <?php if (!empty($chatList)): ?>
                    <?php foreach ($chatList as $chat): ?>
                        <a href="/owner/chat/<?= $chat['id'] ?>" class="chat-item <?= $chat['active'] ? 'active' : '' ?>">
                            <img src="<?= $chat['avatar'] ?>" class="chat-item-avatar" onerror="this.src='/img/users/default.png'">
                            <div style="flex:1; min-width:0;">
                                <div class="chat-name"><?= esc($chat['name']) ?></div>
                                <span class="venue-tag"><?= esc($chat['venue']) ?></span>
                                <div style="font-size:0.8em; color:#9ca3af; margin-top:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?= esc($chat['last_message']) ?></div>
                            </div>
                            <div style="font-size:0.7em; color:#9ca3af; white-space:nowrap; margin-left:8px;"><?= $chat['time'] ?></div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align:center; padding:40px 20px; color:#9ca3af;">
                        <p style="font-size:1em;">Belum ada percakapan</p>
                        <small>Percakapan akan muncul setelah user menghubungi Anda.</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="chat-window-container">
            <?php if (!empty($activeChat['messages']) || !empty($activeChat['user']['name']) && $activeChat['user']['name'] !== 'Pilih Chat'): ?>
                <div class="chat-active-header">
                    <img src="<?= $activeChat['user']['avatar'] ?>" onerror="this.src='/img/users/default.png'">
                    <div>
                        <h4><?= esc($activeChat['user']['name']) ?></h4>
                        <p><?= esc($activeChat['user']['venue']) ?></p>
                    </div>
                </div>

                <div class="messages-area" id="msgArea">
                    <?php if (!empty($activeChat['messages'])): ?>
                        <?php foreach ($activeChat['messages'] as $msg): ?>
                            <div class="message-bubble <?= $msg['type'] ?>">
                                <?= esc($msg['text']) ?>
                                <div class="msg-time"><?= $msg['time'] ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>Belum ada pesan</p>
                            <small>Mulai percakapan dengan menyapa user.</small>
                        </div>
                    <?php endif; ?>
                </div>

                <form class="chat-footer" id="chatForm">
                    <input type="hidden" name="room_id" id="roomIdInput" value="<?= $activeChat['messages'][0]['room_id'] ?? ($chatList[0]['id'] ?? '') ?>">
                    <input type="text" name="message" id="messageInput" class="message-input" placeholder="Tulis pesan..." autocomplete="off" required>
                    <button type="submit" class="send-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </form>
            <?php else: ?>
                <div class="no-chat-selected">
                    <div style="text-align:center;">
                        <div style="font-size:3em; margin-bottom:15px; opacity:0.3;">/</div>
                        <p>Pilih percakapan untuk mulai chatting</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
const chatForm = document.getElementById('chatForm');
const messageInput = document.getElementById('messageInput');
const roomIdInput = document.getElementById('roomIdInput');
const msgArea = document.getElementById('msgArea');

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

        const message = messageInput.value.trim();
        if (!message) return;

        const formData = new FormData();
        formData.append('room_id', currentRoomId);
        formData.append('message', message);

        fetch('<?= base_url('owner/chat/send') ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                messageInput.value = '';
                loadMessages();
                setTimeout(scrollToBottom, 200);
            }
        });
    });
}

function loadMessages() {
    if (!currentRoomId) return;

    fetch('<?= base_url('owner/api/messages/') ?>' + currentRoomId)
        .then(res => res.json())
        .then(data => {
            let html = '';

            if (data.length === 0) {
                html = '<div class="empty-state"><p>Belum ada pesan</p><small>Mulai percakapan dengan menyapa user.</small></div>';
            } else {
                data.forEach(msg => {
                    const type = msg.type === 'owner' ? 'owner' : 'user';
                    html += `
                        <div class="message-bubble ${type}">
                            ${escapeHtml(msg.text)}
                            <div class="msg-time">${msg.time}</div>
                        </div>
                    `;
                });
            }

            msgArea.innerHTML = html;
            scrollToBottom();
        });
}

function escapeHtml(text) {
    if (!text) return '';
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function loadChatList() {
    fetch('<?= base_url('owner/api/chat-list') ?>')
        .then(res => res.json())
        .then(data => {
            const chatItemsContainer = document.querySelector('.chat-items');
            if (!chatItemsContainer) return;

            if (data.length === 0) {
                chatItemsContainer.innerHTML = '<div style="text-align:center; padding:40px 20px; color:#9ca3af;"><p style="font-size:1em;">Belum ada percakapan</p><small>Percakapan akan muncul setelah user menghubungi Anda.</small></div>';
                return;
            }

            let html = '';
            data.forEach(chat => {
                const isActive = chat.room_id == currentRoomId;
                html += `
                    <a href="/owner/chat/${chat.room_id}" class="chat-item ${isActive ? 'active' : ''}">
                        <img src="${escapeHtml(chat.avatar)}" class="chat-item-avatar" onerror="this.src='/img/users/default.png'">
                        <div style="flex:1; min-width:0;">
                            <div class="chat-name">${escapeHtml(chat.name)}</div>
                            <span class="venue-tag">${escapeHtml(chat.venue)}</span>
                            <div style="font-size:0.8em; color:#9ca3af; margin-top:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${escapeHtml(chat.last_message)}</div>
                        </div>
                        <div style="font-size:0.7em; color:#9ca3af; white-space:nowrap; margin-left:8px;">${escapeHtml(chat.time)}</div>
                    </a>
                `;
            });
            chatItemsContainer.innerHTML = html;

            // Auto-select first room if no room is active
            if (!currentRoomId && data.length > 0) {
                currentRoomId = data[0].room_id;
                loadMessages();
                // Update URL without reload
                history.replaceState(null, '', '/owner/chat/' + currentRoomId);
                // Update header info
                loadRoomHeader(data[0]);
            }
        });
}

function loadRoomHeader(chat) {
    const headerContainer = document.querySelector('.chat-window-container');
    if (!headerContainer) return;

    // Only update if currently showing "no chat selected" state
    const noChat = headerContainer.querySelector('.no-chat-selected');
    if (noChat) {
        location.reload();
    }
}

// Polling: messages every 2s, chat list every 5s
setInterval(loadMessages, 2000);
setInterval(loadChatList, 5000);
</script>

<?= $this->renderSection('content'); ?>
