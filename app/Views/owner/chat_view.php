<?= $this->include('layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    /* CSS DIAMBIL DARI bookings.php */
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
    .menu-link:hover, .menu-link.active {
        background-color: #ecfdf5;
        color: #059669;
    }

    /* PENYESUAIAN AREA CHAT AGAR FLEXIBLE */
    .chat-main-wrapper {
        display: flex;
        flex: 1;
        height: 100vh;
        overflow: hidden;
    }

    /* CSS KHUSUS CHAT (Tetap dipertahankan dari chat_view asli) */
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
    .chat-list-header { padding: 20px; border-bottom: 1px solid #e5e7eb; }
    .search-bar { padding: 15px; }
    .search-input { width: 100%; padding: 10px 15px; border-radius: 20px; border: 1px solid #ddd; background: #f9f9f9; outline: none; }
    .chat-items { overflow-y: auto; flex: 1; }
    .chat-item { display: flex; padding: 15px; border-bottom: 1px solid #f3f4f6; cursor: pointer; }
    .chat-item.active { background: #eef2f5; border-left: 4px solid #059669; }
    .chat-item-avatar { width: 45px; height: 45px; border-radius: 50%; margin-right: 12px; }
    .chat-name { font-weight: 600; font-size: 0.95em; }
    .venue-tag { font-size: 0.75em; background: #eee; padding: 2px 6px; border-radius: 4px; color: #666; }
    .messages-area { flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; }
    .message-bubble { max-width: 70%; padding: 10px 15px; border-radius: 8px; margin-bottom: 10px; font-size: 0.9em; box-shadow: 0 1px 1px rgba(0,0,0,0.1); }
    .message-bubble.user { align-self: flex-start; background: #fff; }
    .message-bubble.admin { align-self: flex-end; background: #dcf8c6; }
    .chat-footer { padding: 15px; background: #f0f0f0; display: flex; align-items: center; }
    .message-input { flex: 1; padding: 12px; border-radius: 25px; border: none; outline: none; margin-right: 10px; }
    .send-btn { background: #059669; color: white; border: none; width: 45px; height: 45px; border-radius: 50%; cursor: pointer; }
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
                <h3 style="margin:0;">Chat</h3>
            </div>
            <div class="chat-items">
                <?php foreach($chatList as $chat): ?>
                <div class="chat-item <?= $chat['active'] ? 'active' : ''; ?>">
                    <img src="<?= $chat['avatar']; ?>" class="chat-item-avatar">
                    <div style="flex:1; overflow:hidden;">
                        <div style="display:flex; justify-content:space-between;">
                            <span class="chat-name"><?= $chat['name']; ?></span>
                            <span style="font-size:0.7em; color:#999;"><?= $chat['time']; ?></span>
                        </div>
                        <div class="venue-tag"><?= $chat['venue']; ?></div>
                        <p style="font-size:0.8em; color:#777; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:3px;">
                            <?= $chat['last_message']; ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <main class="chat-window-container">
            <div style="padding:15px 20px; background:#fff; border-bottom:1px solid #ddd; display:flex; align-items:center;">
                <img src="<?= $activeChat['user']['avatar']; ?>" style="width:40px; height:40px; border-radius:50%; margin-right:12px;">
                <div>
                    <h4 style="margin:0; font-size:1em;"><?= $activeChat['user']['name']; ?></h4>
                    <span class="venue-tag"><?= $activeChat['user']['venue']; ?></span>
                </div>
            </div>

            <div class="messages-area">
                <?php foreach($activeChat['messages'] as $msg): ?>
                    <?php if($msg['type'] === 'separator'): ?>
                        <div style="text-align:center; margin:15px 0;"><span style="background:#dce3e6; padding:4px 12px; border-radius:10px; font-size:0.75em;"><?= $msg['text']; ?></span></div>
                    <?php else: ?>
                        <div class="message-bubble <?= $msg['type']; ?>">
                            <?= $msg['text']; ?>
                            <div style="text-align:right; font-size:0.7em; color:#999; margin-top:4px;"><?= $msg['time']; ?></div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <footer class="chat-footer">
                <input type="text" placeholder="Ketik pesan..." class="message-input">
                <button class="send-btn"><i class="fas fa-paper-plane"></i></button>
            </footer>
        </main>
    </div>
</div>

<?= $this->renderSection('content'); ?>
<?= $this->include('layout/footer'); ?>