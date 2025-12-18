<?= $this->include('layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    /* --- CSS UTAMA --- */
    .owner-container {
        display: flex;
        min-height: 100vh;
        background-color: #f3f4f6;
    }

    .sidebar {
        width: 260px;
        background: #fff;
        padding: 20px;
        border-right: 1px solid #e5e7eb;
        position: sticky;
        top: 0;
        height: 100vh;
        flex-shrink: 0;
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

    .menu-link:hover,
    .menu-link.active {
        background-color: #ecfdf5;
        color: #059669;
    }

    /* --- LAYOUT CHAT --- */
    .chat-main-wrapper {
        display: flex;
        flex: 1;
        height: 100vh;
        overflow: hidden;
    }

    .chat-list-container {
        width: 350px;
        background: #fff;
        border-right: 1px solid #e5e7eb;
        display: flex;
        flex-direction: column;
    }

    .chat-window-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        background-color: #e5ddd5;
        background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
    }

    .chat-list-header {
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .chat-items {
        overflow-y: auto;
        flex: 1;
    }

    .chat-item {
        display: flex;
        padding: 15px;
        border-bottom: 1px solid #f3f4f6;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        transition: 0.2s;
    }

    .chat-item:hover {
        background-color: #f9fafb;
    }

    .chat-item.active {
        background: #eef2f5;
        border-left: 4px solid #059669;
    }

    .chat-item-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        margin-right: 12px;
        object-fit: cover;
    }

    .chat-name {
        font-weight: 600;
        font-size: 0.95em;
        color: #333;
    }

    .venue-tag {
        font-size: 0.75em;
        background: #eee;
        padding: 2px 6px;
        border-radius: 4px;
        color: #666;
    }

    .messages-area {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    .message-bubble {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        font-size: 0.9em;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    }

    .message-bubble.user {
        align-self: flex-start;
        background: #fff;
    }

    .message-bubble.owner {
        align-self: flex-end;
        background: #dcf8c6; /* Warna Hijau WhatsApp */
    }

    /* --- FORM INPUT --- */
    .chat-footer {
        padding: 15px;
        background: #f0f0f0;
        display: flex;
        align-items: center;
        border-top: 1px solid #ddd;
    }

    .message-input {
        flex: 1;
        padding: 12px;
        border-radius: 25px;
        border: none;
        outline: none;
        margin-right: 10px;
    }

    .send-btn {
        background: #059669;
        color: white;
        border: none;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .send-btn:hover {
        background: #047857;
    }
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

            <hr style="border:0; border-top:1px solid #eee; margin: 15px 0;">
            <a href="/logout" class="menu-link" style="color:#ef4444;">Keluar</a>
        </nav>
    </aside>

    <div class="chat-main-wrapper">
        <section class="chat-list-container">
            <div class="chat-list-header">
                <h3 style="margin:0;">Pesan Masuk</h3>
            </div>
            <div class="chat-items">
                <?php
                $activeRoomId = null; // Init variabel
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
                                <div style="display:flex; justify-content:space-between;">
                                    <span class="chat-name"><?= esc($chat['name']); ?></span>
                                    <span style="font-size:0.7em; color:#999;"><?= $chat['time']; ?></span>
                                </div>
                                <div class="venue-tag"><?= $chat['venue']; ?></div>
                                <p style="font-size:0.8em; color:#777; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:3px;">
                                    <?= esc($chat['last_message']); ?>
                                </p>
                            </div>
                        </a>
                    <?php endforeach;
                else: ?>
                    <div style="text-align:center; padding:20px; color:#999;">Belum ada percakapan.</div>
                <?php endif; ?>
            </div>
        </section>

        <main class="chat-window-container">
            <?php if (isset($activeChat['user']['name'])): ?>
                
                <div style="padding:15px 20px; background:#fff; border-bottom:1px solid #ddd; display:flex; align-items:center;">
                    <img src="<?= $activeChat['user']['avatar']; ?>" style="width:40px; height:40px; border-radius:50%; margin-right:12px; object-fit:cover;">
                    <div>
                        <h4 style="margin:0; font-size:1em;"><?= esc($activeChat['user']['name']); ?></h4>
                        <span class="venue-tag"><?= $activeChat['user']['venue']; ?></span>
                    </div>
                </div>

                <div class="messages-area" id="msgArea">
                    <?php if (!empty($activeChat['messages'])): ?>
                        <?php foreach ($activeChat['messages'] as $msg): ?>
                            <?php if (isset($msg['type']) && $msg['type'] === 'separator'): ?>
                                <div style="text-align:center; margin:15px 0;">
                                    <span style="background:#dce3e6; padding:4px 12px; border-radius:10px; font-size:0.75em;"><?= $msg['text']; ?></span>
                                </div>
                            <?php else: ?>
                                <div class="message-bubble <?= $msg['type']; ?>">
                                    <?= esc($msg['text']); ?>
                                    <div style="text-align:right; font-size:0.7em; color:#999; margin-top:4px; opacity:0.7;">
                                        <?= $msg['time']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="text-align:center; margin-top:50px; color:#888;">
                            <p>Belum ada riwayat pesan di room ini.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <form class="chat-footer" id="chatForm"> 
                    <input type="hidden" name="room_id" id="roomIdInput" value="<?= $activeRoomId ?>">

                    <input type="text" name="message" id="messageInput" placeholder="Ketik pesan balasan..." class="message-input" required autocomplete="off">

                    <button type="submit" class="send-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </form>

            <?php else: ?>
                <div style="flex:1; display:flex; align-items:center; justify-content:center; flex-direction:column; color:#888;">
                    <h3>👋 Selamat Datang</h3>
                    <p>Pilih chat di sebelah kiri untuk melihat pesan.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script>
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const roomIdInput = document.getElementById('roomIdInput'); // Menggunakan getElementById lebih pasti
    const msgArea = document.getElementById("msgArea");

    // Ambil ID Room, pastikan null safe jika input hidden tidak ada (saat belum pilih chat)
    let currentRoomId = roomIdInput ? roomIdInput.value : null;

    // 1. FUNGSI SCROLL KE BAWAH
    function scrollToBottom() {
        if (msgArea) {
            msgArea.scrollTop = msgArea.scrollHeight;
        }
    }

    // Scroll saat pertama kali load
    scrollToBottom();

    // 2. KIRIM PESAN (AJAX POST)
    if (chatForm) {
        chatForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Mencegah reload halaman

            const message = messageInput.value;
            const roomId = roomIdInput.value;

            if (!message.trim()) return;

            // Buat FormData untuk dikirim
            let formData = new FormData();
            formData.append('message', message);
            formData.append('room_id', roomId);

            // Kirim ke Server
            fetch('<?= base_url('owner/chat/send') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Menandakan ini AJAX CI4
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    messageInput.value = ''; // Kosongkan input
                    loadMessages(); // Refresh chat langsung
                    
                    // Kita akan scroll ke bawah setelah pesan dimuat
                    setTimeout(scrollToBottom, 300);
                } else {
                    console.error("Gagal mengirim:", data);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // 3. AMBIL PESAN (AJAX GET - POLLING)
    function loadMessages() {
        if (!currentRoomId) return; // Jangan jalankan jika tidak ada room aktif

        fetch('<?= base_url('owner/api/messages/') ?>' + currentRoomId)
            .then(response => response.json())
            .then(data => {
                let html = '';

                if (data.length === 0) {
                    html = '<div style="text-align:center; margin-top:50px; color:#888;"><p>Belum ada riwayat pesan.</p></div>';
                } else {
                    data.forEach(msg => {
                        // Admin = Owner (Hijau/Biru), User = Lawan Bicara (Putih)
                        let bubbleClass = (msg.type === 'owner') ? 'owner' : 'user';

                        html += `
                            <div class="message-bubble ${bubbleClass}">
                                ${escapeHtml(msg.text)}
                                <div style="text-align:right; font-size:0.7em; color:#999; margin-top:4px; opacity:0.7;">
                                    ${msg.time}
                                </div>
                            </div>
                        `;
                    });
                }

                // Cek apakah user sedang scroll ke atas (reading history)
                if (msgArea) {
                    const isScrolledToBottom = msgArea.scrollHeight - msgArea.scrollTop <= msgArea.clientHeight + 150;
                    
                    msgArea.innerHTML = html; // Update isi chat

                    // Hanya auto scroll jika user memang sedang di bawah
                    if (isScrolledToBottom) {
                        scrollToBottom();
                    }
                }
            })
            .catch(error => console.error('Error fetching messages:', error));
    }

    // Helper untuk keamanan (XSS Prevention)
    function escapeHtml(text) {
        if (!text) return "";
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // 4. JALANKAN INTERVAL (Setiap 2 Detik)
    if (currentRoomId) {
        setInterval(loadMessages, 2000); // 2000ms = 2 detik
    }

</script>

<?= $this->renderSection('content'); ?>
<?= $this->include('layout/footer'); ?>