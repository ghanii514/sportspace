<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
:root {
    --primary-color: #10b981;
    --sidebar-width: 280px;
}

body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
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
}

.message-input {
    flex: 1;
    padding: 15px 20px;
    border-radius: 30px;
    border: 1px solid #e5e7eb;
    margin-right: 15px;
    background: #f9fafb;
    outline: none;
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
}
</style>

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

            data.forEach(msg => {
                const type = msg.type === 'owner' ? 'owner' : 'user';
                html += `
                    <div class="message-bubble ${type}">
                        ${escapeHtml(msg.text)}
                        <div class="msg-time">${msg.time}</div>
                    </div>
                `;
            });

            msgArea.innerHTML = html;
            scrollToBottom();
        });
}

function escapeHtml(text) {
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
