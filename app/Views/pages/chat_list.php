<?= $this->include('layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    /* Container Utama */
    .chat-container {
        max-width: 800px;
        margin: 30px auto;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 80vh; /* Tinggi fix agar bisa scroll */
        border: 1px solid #e5e7eb;
    }

    /* Header Chat */
    .chat-header {
        padding: 15px 20px;
        background: #059669; /* Warna Hijau Tema */
        color: white;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        z-index: 10;
    }

    .chat-header img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        border: 2px solid #fff;
        object-fit: cover;
    }

    /* Area Pesan (Scrollable) */
    .chat-body {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background-color: #e5ddd5; /* Background a la WhatsApp */
        background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* Bubble Chat Base */
    .msg {
        max-width: 75%;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 15px;
        line-height: 1.4;
        position: relative;
        word-wrap: break-word;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    /* Bubble Owner (Kiri - Putih) */
    .msg-owner {
        background: #ffffff;
        align-self: flex-start; /* Kiri */
        border-top-left-radius: 0;
        color: #1f2937;
    }

    /* Bubble User / Saya (Kanan - Hijau Muda) */
    .msg-user {
        background: #dcf8c6;
        align-self: flex-end; /* Kanan */
        border-top-right-radius: 0;
        color: #1f2937;
    }

    .msg-time {
        display: block;
        font-size: 10px;
        color: #6b7280;
        margin-top: 4px;
        text-align: right;
    }

    /* Form Footer */
    .chat-footer {
        display: flex;
        padding: 15px;
        background: white;
        border-top: 1px solid #e5e7eb;
        align-items: center;
        gap: 10px;
    }

    .chat-footer input[type="text"] {
        flex: 1;
        padding: 12px 15px;
        border-radius: 25px;
        border: 1px solid #d1d5db;
        font-size: 15px;
        outline: none;
        transition: border-color 0.2s;
    }

    .chat-footer input[type="text"]:focus {
        border-color: #059669;
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
        transition: background 0.2s;
    }
    
    .send-btn:hover {
        background: #047857;
    }
</style>

<div class="container" style="margin-top: 20px;">
    <div class="chat-container">

        <div class="chat-header">
            <img src="/img/users/<?= $owner['profile_picture'] ?? 'default.png' ?>" alt="Owner">
            <div>
                <div style="font-weight: 700; font-size: 16px;"><?= esc($owner['username']) ?></div>
                <div style="font-size: 12px; opacity: 0.9;">Pemilik Lapangan</div>
            </div>
        </div>

        <div class="chat-body" id="chatBox">
            <div style="text-align:center; margin-top:50px; color:#666;" id="loadingIndicator">
                <p>Memuat percakapan...</p>
            </div>
        </div>

        <form class="chat-footer" id="chatForm">
            
            <input type="hidden" name="room_id" id="roomIdInput" value="<?= $roomId ?? '' ?>">
            
            <input type="hidden" name="owner_id" value="<?= $owner['id'] ?>">

            <input type="text" name="message" id="messageInput" placeholder="Tulis pesan..." required autocomplete="off">
            
            <button type="submit" class="send-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
            </button>
        </form>

    </div>
</div>

<script>
    const chatBox = document.getElementById("chatBox");
    const chatForm = document.getElementById("chatForm");
    const messageInput = document.getElementById("messageInput");
    const roomIdInput = document.getElementById("roomIdInput");
    
    // Ambil value room_id dari input hidden
    const roomId = roomIdInput ? roomIdInput.value : '';

    // Cek di Console apakah roomId ada
    console.log("Room ID:", roomId); 

    // 1. Fungsi Scroll ke Bawah Otomatis
    function scrollToBottom() {
        if(chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    }

    // 2. Fungsi Fetch Pesan (Polling)
    function loadMessages() {
        if (!roomId) {
            console.error("Room ID kosong! Tidak bisa mengambil pesan.");
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
                        <div style="text-align:center; margin-top:40px; color:#888;">
                            <p>Belum ada pesan.</p>
                            <small>Sapa pemilik lapangan sekarang!</small>
                        </div>`;
                } else {
                    data.forEach(msg => {
                        let bubbleClass = (msg.type === 'user') ? 'msg-user' : 'msg-owner';
                        
                        html += `
                            <div class="msg ${bubbleClass}">
                                ${escapeHtml(msg.message)}
                                <span class="msg-time">${msg.time}</span>
                            </div>
                        `;
                    });
                }

                const isScrolledToBottom = chatBox.scrollHeight - chatBox.scrollTop <= chatBox.clientHeight + 150;
                
                chatBox.innerHTML = html;

                if (isScrolledToBottom || document.getElementById('loadingIndicator')) {
                    scrollToBottom();
                }
            })
            .catch(error => console.error('Gagal memuat pesan:', error));
    }

    // 3. Fungsi Kirim Pesan (AJAX POST)
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
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
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

    // Jalankan Load Pertama Kali
    loadMessages();

    // Jalankan Polling setiap 2 detik
    setInterval(loadMessages, 2000);

</script>

<?= $this->renderSection('content'); ?>