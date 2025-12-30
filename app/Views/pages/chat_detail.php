<?= $this->include('layout/template'); ?>

<style>
    .chat-wrapper {
        max-width: 600px;
        margin: 20px auto;
        background: #fff;
        height: 80vh; 
        display: flex;
        flex-direction: column;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        overflow: hidden; 
        border: 1px solid #e5e7eb;
    }

    .chat-header {
        background-color: #4ade80; 
        padding: 15px 20px;
        display: flex;
        align-items: center;
        color: #000;
        z-index: 10;
        flex-shrink: 0; 
    }

    .btn-back {
        text-decoration: none;
        color: #000;
        font-size: 24px;
        margin-right: 15px;
        font-weight: bold;
    }

    .venue-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        margin-right: 12px;
        background: #eee;
    }

    .header-info h2 { margin: 0; font-size: 1.1rem; font-weight: 700; }
    .header-info span { font-size: 0.8rem; opacity: 0.8; }

    .chat-body {
        flex: 1; 
        padding: 20px;
        overflow-y: auto; 
        background-color: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    
    .bubble-left {
        background-color: #fff;
        color: #1f2937;
        padding: 15px;
        border-radius: 0 15px 15px 15px;
        max-width: 85%;
        align-self: flex-start;
        border: 1px solid #e2e8f0;
        position: relative;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    
    .bubble-right {
        background-color: #dcfce7;
        color: #166534;
        padding: 12px 20px;
        border-radius: 15px 0 15px 15px;
        max-width: 70%;
        align-self: flex-end;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .bubble-time {
        display: block;
        font-size: 0.7rem;
        color: #94a3b8;
        text-align: right;
        margin-top: 5px;
    }

    .chat-footer {
        background: #fff;
        padding: 15px;
        border-top: 1px solid #eee;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .chat-input {
        flex: 1;
        padding: 12px 15px;
        border: 1px solid #cbd5e1;
        border-radius: 25px; 
        font-size: 0.95rem;
        outline: none;
        background: #f1f5f9;
    }
    .chat-input:focus { background: #fff; border-color: #4ade80; }

    .btn-send {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #4ade80;
    }
</style>

<div class="chat-wrapper">
    
    <div class="chat-header">
        <a href="/chat" class="btn-back">←</a>
        
        <img src="/img/fields/<?= esc($header_info['gambar_lapangan']) ?>" 
             class="venue-avatar" 
             onerror="this.src='https://via.placeholder.com/45'">
        
        <div class="header-info">
            <h2><?= esc($header_info['nama_lapangan']) ?></h2>
            <span>Admin Lapangan</span>
        </div>
    </div>

    <div class="chat-body" id="chatContainer">
        
        <?php foreach($chats as $msg): ?>
            
            <?php if($msg['sender'] == 'admin'): ?>
                <div class="bubble-left">
                    <?= nl2br(esc($msg['message'])) ?>
                    <span class="bubble-time"><?= date('H.i', strtotime($msg['created_at'])) ?></span>
                </div>
            <?php else: ?>
                <div class="bubble-right">
                    <?= nl2br(esc($msg['message'])) ?>
                    <span class="bubble-time" style="text-align:right; color:#14532d;">
                        <?= date('H.i', strtotime($msg['created_at'])) ?>
                    </span>
                </div>
            <?php endif; ?>

        <?php endforeach; ?>

    </div>

    <div class="chat-footer">
        <input type="hidden" id="venueId" value="<?= $venue_id ?>">
        <input type="text" id="msgInput" class="chat-input" placeholder="Ketik pesan..." autocomplete="off">
        <button class="btn-send" onclick="sendMessage()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
        </button>
    </div>

</div>

<script>
    
    document.addEventListener("DOMContentLoaded", function() {
        scrollToBottom();
    });

    function scrollToBottom() {
        var chatContainer = document.getElementById("chatContainer");
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    document.getElementById("msgInput").addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            sendMessage();
        }
    });

    function sendMessage() {
        var input = document.getElementById("msgInput");
        var message = input.value;
        var venueId = document.getElementById("venueId").value;
        var container = document.getElementById("chatContainer");

        if (message.trim() !== "") {
            
            
            var bubble = document.createElement("div");
            bubble.classList.add("bubble-right");
            var now = new Date();
            var time = now.getHours() + "." + String(now.getMinutes()).padStart(2, '0');
            
            
            bubble.innerHTML = message + '<span class="bubble-time" style="text-align:right; color:#14532d;">' + time + '</span>';
            
            container.appendChild(bubble);
            input.value = ""; 
            scrollToBottom();

            
            let formData = new FormData();
            formData.append('venue_id', venueId);
            formData.append('message', message);

            fetch('/chat/send', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.status !== 'success') {
                    console.error("Gagal simpan pesan");
                }
            });
        }
    }
</script>

<?= $this->include('layout/footer'); ?>