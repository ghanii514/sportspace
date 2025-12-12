<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportSpace Owner Admin Chat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #28a745;
            --light-gray-bg: #f4f7f6;
            --border-color: #e6e6e6;
            --sidebar-width: 260px;
            --chat-list-width: 350px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            height: 100vh;
            background-color: var(--light-gray-bg);
        }

        .main-container {
            display: flex;
            height: 100vh;
            max-width: 1440px;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }

        /* --- KOLOM 1: SIDEBAR KIRI --- */
        .sidebar {
            width: var(--sidebar-width);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .profile-section {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
        }

        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .profile-info h2 {
            font-size: 16px;
            font-weight: 600;
        }

        .profile-info p {
            font-size: 13px;
            color: #888;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            text-decoration: none;
            color: #555;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .menu-item i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }

        .menu-item:hover {
            background-color: #f0f0f0;
        }

        .menu-item.active {
            background-color: var(--primary-green);
            color: white;
        }

        .menu-status { margin-top: 20px; font-size: 14px; color: #888; padding-left: 15px; }

        /* --- KOLOM 2: DAFTAR CHAT (TENGAH) --- */
        .chat-list-container {
            width: var(--chat-list-width);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
        }

        .chat-list-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-list-header h3 { font-size: 20px; }

        .search-bar {
            padding: 0 20px 20px 20px;
        }

        .search-input-wrapper {
            position: relative;
        }
        
        .search-input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        .search-input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            background-color: #f9f9f9;
            outline: none;
        }

        .chat-items {
            overflow-y: auto;
            flex-grow: 1;
        }

        .chat-item {
            display: flex;
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .chat-item:hover { background-color: #f9f9f9; }
        .chat-item.active { background-color: #eef2f5; border-left: 4px solid var(--primary-green); }

        .chat-item-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .chat-item-info { flex-grow: 1; overflow: hidden; }

        .chat-item-top {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .chat-name { font-weight: 600; font-size: 15px; }
        .chat-time { font-size: 12px; color: #999; }
        .venue-tag { font-size: 12px; color: #666; background: #e0e0e0; padding: 2px 6px; border-radius: 4px;}

        .chat-preview {
            font-size: 13px;
            color: #777;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* --- KOLOM 3: JENDELA CHAT (KANAN) --- */
        .chat-window-container {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            background-color: #e5ddd5; /* Warna background ala WhatsApp */
            /* Pattern background (opsional) */
            background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
            background-repeat: repeat;
        }

        .chat-window-header {
            padding: 15px 20px;
            background-color: #fff;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .active-user-info { display: flex; align-items: center; }
        .active-user-avatar { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
        .active-user-name h4 { font-size: 16px; margin-bottom: 2px; }

        .messages-area {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .date-separator {
            text-align: center;
            margin: 10px 0;
        }
        .date-separator span {
            background-color: #dce3e6;
            padding: 5px 15px;
            border-radius: 10px;
            font-size: 12px;
            color: #555;
        }

        .message-bubble {
            max-width: 65%;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            position: relative;
            font-size: 14px;
            line-height: 1.4;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }

        .message-bubble.user {
            align-self: flex-start;
            background-color: #fff;
            border-top-left-radius: 0;
        }

        .message-bubble.admin {
            align-self: flex-end;
            background-color: #dcf8c6; /* Warna hijau muda ala pesan terkirim WA */
            border-top-right-radius: 0;
        }

        .message-time {
            display: block;
            text-align: right;
            font-size: 10px;
            color: #999;
            margin-top: 5px;
        }

        .chat-footer {
            padding: 15px;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
        }

        .message-input {
            flex-grow: 1;
            padding: 12px 15px;
            border: 1px solid #fff;
            border-radius: 25px;
            outline: none;
            margin-right: 15px;
            font-size: 14px;
        }

        .send-btn {
            background-color: var(--primary-green);
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
        }
        .send-btn:hover { background-color: #218838; }

    </style>
</head>
<body>

<div class="main-container">
    <aside class="sidebar">
        <div class="profile-section">
            <img src="<?= $profile['avatar']; ?>" alt="Profile" class="profile-img">
            <div class="profile-info">
                <h2><?= $profile['name']; ?></h2>
                <p><?= $profile['role']; ?></p>
            </div>
        </div>

        <nav class="menu">
            <a href="#" class="menu-item"><i class="fas fa-home"></i> Dashboard</a>
            <a href="#" class="menu-item"><i class="fas fa-user"></i> User</a>
            <a href="#" class="menu-item active"><i class="far fa-comment-dots"></i> Chat User</a>
            
            <div class="menu-status">Status</div>
            <a href="#" class="menu-item"><i class="fas fa-cog"></i> Settings</a>
            <a href="#" class="menu-item"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </nav>
    </aside>

    <section class="chat-list-container">
        <div class="chat-list-header">
            <h3>Chat</h3>
            <i class="far fa-edit" style="color: #888; cursor: pointer;"></i>
        </div>
        <div class="search-bar">
            <div class="search-input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search" class="search-input">
            </div>
        </div>

        <div class="chat-items">
            <?php foreach($chatList as $chat): ?>
            <div class="chat-item <?= $chat['active'] ? 'active' : ''; ?>">
                <img src="<?= $chat['avatar']; ?>" alt="<?= $chat['name']; ?>" class="chat-item-avatar">
                <div class="chat-item-info">
                    <div class="chat-item-top">
                        <span class="chat-name"><?= $chat['name']; ?></span>
                        <span class="chat-time"><?= $chat['time']; ?></span>
                    </div>
                    <div class="venue-tag"><?= $chat['venue']; ?></div>
                    <p class="chat-preview"><?= $chat['last_message']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <main class="chat-window-container">
        <div class="chat-window-header">
            <div class="active-user-info">
                <img src="<?= $activeChat['user']['avatar']; ?>" alt="" class="active-user-avatar">
                <div class="active-user-name">
                    <h4><?= $activeChat['user']['name']; ?></h4>
                    <span class="venue-tag"><?= $activeChat['user']['venue']; ?></span>
                </div>
            </div>
            <i class="fas fa-ellipsis-h" style="color: #888; cursor: pointer;"></i>
        </div>

        <div class="messages-area">
            <?php foreach($activeChat['messages'] as $msg): ?>
                <?php if($msg['type'] === 'separator'): ?>
                    <div class="date-separator">
                        <span><?= $msg['text']; ?></span>
                    </div>
                <?php else: ?>
                    <div class="message-bubble <?= $msg['type']; ?>">
                        <?= $msg['text']; ?>
                        <span class="message-time"><?= $msg['time']; ?></span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <footer class="chat-footer">
            <input type="text" placeholder="Type a message" class="message-input">
            <button class="send-btn">
                <i class="fas fa-paper-plane"></i>
            </button>
        </footer>
    </main>
</div>

</body>
</html>