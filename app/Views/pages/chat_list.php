<?= $this->include('layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    /* === GLOBAL & VARIABLES === */
    :root {
        --primary-color: #10b981; /* Emerald Green */
        --primary-dark: #059669;
        --bg-color: #f3f4f6;
        --chat-bg: #f0f2f5;
        --text-dark: #1f2937;
        --white: #ffffff;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-color);
        margin: 0;
    }

    /* === CONTAINER UTAMA === */
    .chat-wrapper {
        display: flex;
        justify-content: center;
        padding-top: 30px;
        padding-bottom: 30px;
        height: 85vh; /* Tinggi Chat Window */
    }

    .chat-card {
        width: 100%;
        max-width: 600px; /* Lebar maksimal agar enak dilihat di PC */
        background: var(--white);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }

    /* === HEADER === */
    .chat-header {
        padding: 15px 25px;
        background: var(--white);
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 15px;
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .chat-header img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #eee;
    }

    .owner-info h4 {
        margin: 0;
        font-size: 1.1em;
        font-weight: 600;
        color: var(--text-dark);
    }

    .owner-info p {
        margin: 0;
        font-size: 0.8em;
        color: var(--primary-color);
        font-weight: 500;
    }

    /* === BODY CHAT === */
    .chat-body {
        flex: 1;
        padding: 25px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        background-color: var(--chat-bg);
        /* Pola titik halus */
        background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
        background-size: 20px 20px;
    }

    /* === BUBBLE CHAT === */
    .msg {
        max-width: 70%;
        padding: 12px 18px;
        border-radius: 18px;
        margin-bottom: 8px;
        font-size: 0.95em;
        line-height: 1.5;
        position: relative;
        word-wrap: break-word;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        /* Animasi dihapus agar instan */
    }

    /* Bubble Owner (Kiri - Putih) */
    .msg-owner {
        align-self: flex-start;
        background: var(--white);
        color: var(--text-dark);
        border-bottom-left-radius: 4px; /* Aksen sudut */
    }

    /* Bubble User / Saya (Kanan - Hijau) */
    .msg-user {
        align-self: flex-end;
        background: var(--primary-color);
        color: var(--white);
        border-bottom-right-radius: 4px; /* Aksen sudut */
    }

    .msg-time {
        display: block;
        font-size: 0.7em;
        margin-top: 4px;
        text-align: right;
        opacity: 0.8;
    }
    
    .msg-owner .msg-time { color: #9ca3af; }
    .msg-user .msg-time { color: #ecfdf5; }

    /* === FOOTER / FORM === */
    .chat-footer {
        padding: 20px;
        background: var(--white);
        border-top: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chat-footer input[type="text"] {
        flex: 1;
        padding: 14px 20px;
        border-radius: 30px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95em;
        outline: none;
        transition: 0.3s;
    }

    .chat-footer input[type="text"]:focus {
        background: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .send-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
    }
    
    .send-btn:hover {
        background: var(--primary-dark);
        transform: scale(1.05);
    }

</style>

<div class="chat-wrapper">
    <div class="chat-card">

        <div class="chat-header">
            <img src="/img/users/<?= $owner['profile_picture'] ?? 'default.png' ?>" alt="Owner">
            <div class="owner-info">
                <h4><?= esc($owner['username']) ?></h4>
                <p>Pemilik Lapangan</p>
            </div>
        </div>

        <div class="chat-body" id="chatBox">
            <div style="text-align:center; margin-top:50px; color:#9ca3af;" id="loadingIndicator">
                <p>Memuat percakapan...</p>
            </div>
        </div>

        <form class="chat-footer" id="chatForm">
            <input type="hidden" name="room_id" id="roomIdInput" value="<?= $roomId ?? '' ?>">
            <input type="hidden" name="owner_id" value="<?= $owner['id'] ?>">

            <input type="text" name="message" id="messageInput" placeholder="Tulis pesan..." required autocomplete="off">
            
            <button type="submit" class="send-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
            </button>
        </form>

    </div>
</div>

<script>
    const chatBox = document.getElementById("chatBox");
    const chatForm = document.getElementById("chatForm");
    const messageInput = document.getElementById("messageInput");
    const roomIdInput = document.getElementById("roomIdInput");
    
    const roomId = roomIdInput ? roomIdInput.value : '';

    function scrollToBottom() {
        if(chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    }

    function loadMessages() {
        if (!roomId) {
            document.getElementById('loadingIndicator').innerHTML = "<p style='color:red'>Error: Room ID tidak ditemukan.</p>";
            return;
        }

        fetch('<?= base_url('chat/api/messages/') ?>' + roomId)
            .then(response => {
                if (!response.ok) { throw new Error("HTTP error " + response.status); }
                return response.json();
            })
            .then(data => {
                let html = '';
                
                if (data.length === 0) {
                    html = `
                        <div style="text-align:center; margin-top:50px; color:#9ca3af;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.3; margin-bottom:10px;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                            <p style="margin:0;">Belum ada pesan.</p>
                            <small>Sapa pemilik lapangan sekarang!</small>
                        </div>`;
                } else {
                    data.forEach(msg => {
                        // msg-user = Saya (Kanan/Hijau), msg-owner = Dia (Kiri/Putih)
                        let bubbleClass = (msg.type === 'user') ? 'msg-user' : 'msg-owner';
                        
                        html += `
                            <div class="msg ${bubbleClass}">
                                ${escapeHtml(msg.message)}
                                <span class="msg-time">${msg.time}</span>
                            </div>
                        `;
                    });
                }

                // Cek scroll user
                const isScrolledToBottom = chatBox.scrollHeight - chatBox.scrollTop <= chatBox.clientHeight + 150;
                
                chatBox.innerHTML = html;

                // Auto scroll jika di bawah atau load pertama
                if (isScrolledToBottom || document.getElementById('loadingIndicator')) {
                    scrollToBottom();
                }
            })
            .catch(error => console.error('Gagal memuat pesan:', error));
    }

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const message = messageInput.value;
        if (!message.trim()) return;

        if (!roomId) {
            alert("Terjadi kesalahan: Room ID tidak ditemukan.");
            return;
        }

        let formData = new FormData(chatForm);

        fetch('<?= base_url('chat/send') ?>', {
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
                alert('Gagal mengirim pesan: ' + (data.message || 'Error'));
            }
        })
        .catch(error => console.error('Error:', error));
    });

    function escapeHtml(text) {
        if (!text) return "";
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Load awal & Polling
    loadMessages();
    setInterval(loadMessages, 2000);

</script>

<?= $this->renderSection('content'); ?>