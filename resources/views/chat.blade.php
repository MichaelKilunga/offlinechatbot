<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- ═══════════════════════════════════════════════════════
         PRIMARY SEO META
    ═══════════════════════════════════════════════════════ --}}
    <title>Chat with HuruLearn AI – Instant Education for Africa</title>
    <meta name="description" content="HuruLearn delivers curriculum-aligned AI tutoring through basic SMS and Web. No internet? Use SMS. Have data? Use our Web Chat. Serving students across Africa.">
    <meta name="keywords" content="SMS education Africa, AI tutoring Tanzania, offline learning, curriculum-aligned SMS, HuruLearn, Huru Digital, educational technology Africa, Kiswahili education, no internet learning, SDG 4 education">
    <meta name="author" content="Huru Digital Co. Ltd.">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">

    {{-- Canonical URL --}}
    <link rel="canonical" href="https://hurulearn.hurudigital.co.tz/chat">

    {{-- Alternate languages --}}
    <link rel="alternate" hreflang="en" href="https://hurulearn.hurudigital.co.tz/chat">
    <link rel="alternate" hreflang="sw" href="https://hurulearn.hurudigital.co.tz/chat">
    <link rel="alternate" hreflang="x-default" href="https://hurulearn.hurudigital.co.tz/chat">

    {{-- ═══════════════════════════════════════════════════════
         OPEN GRAPH (Facebook, LinkedIn, WhatsApp …)
    ═══════════════════════════════════════════════════════ --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="HuruLearn">
    <meta property="og:title" content="Chat with HuruLearn AI – Instant Education">
    <meta property="og:description" content="Curriculum-aligned AI tutoring delivered via any device. No internet? Use SMS. Have data? Use our Web Chat.">
    <meta property="og:url" content="https://hurulearn.hurudigital.co.tz/chat">
    <meta property="og:image" content="https://hurulearn.hurudigital.co.tz/og-image.svg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="HuruLearn — AI SMS Education Platform for Africa">
    <meta property="og:locale" content="en_TZ">
    <meta property="og:locale:alternate" content="sw_TZ">

    {{-- ═══════════════════════════════════════════════════════
         TWITTER / X CARD
    ═══════════════════════════════════════════════════════ --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@HuruLearnTZ">
    <meta name="twitter:creator" content="@HuruDigitalCoLtd">
    <meta name="twitter:title" content="Chat with HuruLearn AI – Instant Education">
    <meta name="twitter:description" content="Curriculum-aligned AI tutoring via basic SMS and Web. No internet? Use SMS. Have data? Use Web Chat.">
    <meta name="twitter:image" content="https://hurulearn.hurudigital.co.tz/og-image.svg">
    <meta name="twitter:image:alt" content="HuruLearn SMS Education Platform">

    {{-- ═══════════════════════════════════════════════════════
         GEO / REGIONAL META
    ═══════════════════════════════════════════════════════ --}}
    <meta name="geo.region" content="TZ">
    <meta name="geo.placename" content="Tanzania">
    <meta name="geo.position" content="-6.369028;34.888822">
    <meta name="ICBM" content="-6.369028, 34.888822">

    {{-- ═══════════════════════════════════════════════════════
         PWA / MANIFEST
    ═══════════════════════════════════════════════════════ --}}
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" href="/logo.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/logo.svg">
    <meta name="theme-color" content="#1e1b4b">

    {{-- ═══════════════════════════════════════════════════════
         FONTS & PERFORMANCE
    ═══════════════════════════════════════════════════════ --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
        :root {
            --primary: #3b82f6;
            --secondary: #1e1b4b;
            --accent: #f59e0b;
            --bg: #0f0d2e;
            --card-bg: rgba(255, 255, 255, 0.05);
            --text: #ffffff;
            --text-muted: #9ca3af;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chat-container {
            width: 100%;
            height: 100%;
            max-width: 600px;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .screen {
            display: none;
            width: 100%;
            height: 100%;
            transition: opacity 0.3s ease;
        }

        .screen.active {
            display: flex;
            flex-direction: column;
        }

        /* Auth Screen */
        #auth-screen {
            justify-content: center;
            padding: 2rem;
        }

        .auth-card {
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 3rem 2rem;
            backdrop-filter: blur(20px);
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .auth-header h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2rem;
            margin: 1.5rem 0 0.5rem;
            background: linear-gradient(135deg, #fff, var(--text-muted));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .auth-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--accent), var(--primary));
            color: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1.4rem;
            margin: 0 auto;
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }

        .input-group {
            text-align: left;
            margin-bottom: 1.8rem;
        }

        .input-group label {
            display: block;
            font-size: 0.85rem;
            margin-bottom: 0.6rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .input-group input {
            width: 100%;
            padding: 1rem 1.2rem;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            color: white;
            font-size: 1rem;
            outline: none;
            transition: all 0.2s;
        }

        .input-group input:focus {
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .btn-primary {
            width: 100%;
            padding: 1.1rem;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, var(--accent), #e67e22);
            color: white;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(245, 158, 11, 0.4);
        }

        /* Chat Screen */
        #chat-screen {
            background: rgba(15,13,46,0.5);
        }

        .chat-header {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(15, 13, 46, 0.8);
            backdrop-filter: blur(16px);
            z-index: 10;
        }

        .header-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-info .logo {
            width: 40px;
            height: 40px;
            font-size: 0.9rem;
            border-radius: 10px;
        }

        .header-info h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.1rem;
            margin: 0;
            font-weight: 700;
        }

        .status {
            font-size: 0.75rem;
            color: #10b981;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .status::before {
            content: '';
            width: 6px;
            height: 6px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
        }

        .btn-logout {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--text-muted);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .chat-messages {
            flex: 1;
            padding: 2rem 1.5rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
            scroll-behavior: smooth;
        }

        /* Custom Scrollbar */
        .chat-messages::-webkit-scrollbar { width: 6px; }
        .chat-messages::-webkit-scrollbar-track { background: transparent; }
        .chat-messages::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

        .message {
            max-width: 85%;
            padding: 1rem 1.2rem;
            border-radius: 18px;
            font-size: 0.95rem;
            line-height: 1.5;
            position: relative;
            animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .message.inbound {
            background: rgba(255, 255, 255, 0.07);
            align-self: flex-start;
            border-bottom-left-radius: 4px;
            color: #e5e7eb;
        }

        .message.outbound {
            background: linear-gradient(135deg, var(--primary), #1d4ed8);
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
        }

        .chat-input-area {
            padding: 1.2rem 1.5rem;
            display: flex;
            gap: 1rem;
            background: rgba(15, 13, 46, 0.9);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
        }

        .chat-input-area input {
            flex: 1;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            padding: 0.9rem 1.5rem;
            color: white;
            font-size: 1rem;
            outline: none;
            transition: all 0.2s;
        }

        .chat-input-area input:focus {
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.08);
        }

        .btn-send {
            background: linear-gradient(135deg, var(--primary), #2563eb);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-send:hover {
            transform: scale(1.05) rotate(-10deg);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        .typing-indicator {
            padding: 0.5rem 1.5rem;
            display: flex;
            gap: 5px;
            align-self: flex-start;
        }

        .typing-indicator span {
            width: 7px;
            height: 7px;
            background: var(--primary);
            border-radius: 50%;
            opacity: 0.4;
            animation: typing 1.2s infinite ease-in-out;
        }

        .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

        .filter-bar {
        padding: 0.8rem 1.5rem;
        background: rgba(15, 13, 46, 0.4);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .filter-group {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .filter-group input {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 0.5rem 0.8rem;
        color: white;
        font-size: 0.85rem;
        outline: none;
    }

    #keyword-search { flex: 1; }
    #date-filter { width: 140px; color-scheme: dark; }

    .btn-filter {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: opacity 0.2s;
    }

    .btn-reset {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-muted);
    }

    .search-status {
        margin-top: 0.5rem;
        font-size: 0.75rem;
        color: var(--accent);
        font-weight: 500;
    }

    .hidden { display: none !important; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes typing {
            0%, 100% { transform: translateY(0); opacity: 0.4; }
            50% { transform: translateY(-5px); opacity: 1; }
        }

        /* Mobile Adjustments */
        @media (max-width: 600px) {
            .chat-container { max-width: 100%; border-radius: 0; }
            .auth-card { border-radius: 0; height: 100%; display: flex; flex-direction: column; justify-content: center; border: none; }
        }
</style>
</head>
<body>

<div class="chat-container">
    <div id="auth-screen" class="screen active">
        <div class="auth-card">
            <div class="auth-header">
                <div class="logo">HL</div>
                <h1>HuruLearn AI</h1>
                <p>Register with your phone number to start asking questions directly to our AI tutor.</p>
            </div>
            <form id="login-form">
                @csrf
                <div class="input-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" id="phone_number" name="phone_number" placeholder="+255 7XX XXX XXX" required>
                </div>
                    <button type="submit" id="login-btn" class="btn-primary">Start Chatting ✦</button>
            </form>
            <div style="margin-top: 1.5rem; pt-4; border-top: 1px solid rgba(255,255,255,0.05);">
                <a href="{{ route('welcome') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem; font-weight: 500; display: inline-flex; align-items: center; gap: 5px; transition: color 0.2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-muted)'">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Return to Homepage
                </a>
            </div>
        </div>
    </div>

    <div id="chat-screen" class="screen">
        <div class="chat-header">
            <div class="header-info">
                <div class="logo">HL</div>
                <div>
                    <h2>HuruLearn AI</h2>
                    <span class="status">Online & Ready</span>
                </div>
            </div>
            <button id="logout-btn" class="btn-logout">Logout</button>
        </div>
        <div class="filter-bar">
            <div class="filter-group">
                <input type="text" id="keyword-search" placeholder="Search keywords..." aria-label="Search keywords">
                <input type="date" id="date-filter" aria-label="Filter by date">
                <button id="apply-filters" class="btn-filter">Search</button>
                <button id="reset-filters" class="btn-filter btn-reset hidden">Reset</button>
            </div>
            <div id="search-status" class="search-status hidden">
                Showing search results...
            </div>
        </div>
        <div id="chat-messages" class="chat-messages">
            <!-- Messages go here -->
        </div>
        <div id="typing-indicator" class="typing-indicator hidden">
            <span></span><span></span><span></span>
        </div>
        <form id="chat-form" class="chat-input-area">
            @csrf
            <input type="text" id="message-input" placeholder="Ask a question..." autocomplete="off">
            <button type="submit" id="send-btn" class="btn-send">
                <svg viewBox="0 0 24 24" width="24" height="24" style="transform: rotate(45deg);"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" fill="currentColor"></path></svg>
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const authScreen = document.getElementById('auth-screen');
        const chatScreen = document.getElementById('chat-screen');
        const loginForm = document.getElementById('login-form');
        const chatForm = document.getElementById('chat-form');
        const chatMessages = document.getElementById('chat-messages');
        const messageInput = document.getElementById('message-input');
        const typingIndicator = document.getElementById('typing-indicator');
        const logoutBtn = document.getElementById('logout-btn');
        const keywordSearch = document.getElementById('keyword-search');
        const dateFilter = document.getElementById('date-filter');
        const applyFiltersBtn = document.getElementById('apply-filters');
        const resetFiltersBtn = document.getElementById('reset-filters');
        const searchStatus = document.getElementById('search-status');

        // Check for existing session
        async function loadMessages(filters = {}) {
            try {
                const params = new URLSearchParams(filters).toString();
                const res = await fetch(`/chat/messages?${params}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await res.json();
                if (data.status === 'success') {
                    showChat(data.messages, !!params);
                }
            } catch (err) { console.error('Failed to load messages', err); }
        }
        loadMessages();

        applyFiltersBtn.addEventListener('click', () => {
            const keyword = keywordSearch.value.trim();
            const date = dateFilter.value;
            if (keyword || date) {
                resetFiltersBtn.classList.remove('hidden');
                searchStatus.classList.remove('hidden');
                loadMessages({ keyword, date });
            }
        });

        resetFiltersBtn.addEventListener('click', () => {
            keywordSearch.value = '';
            dateFilter.value = '';
            resetFiltersBtn.classList.add('hidden');
            searchStatus.classList.add('hidden');
            loadMessages();
        });

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('login-btn');
            btn.innerHTML = 'Signing in...';
            btn.disabled = true;

            const formData = new FormData(loginForm);
            try {
                const res = await fetch('/chat/login', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                if (data.status === 'success') {
                    const msgRes = await fetch('/chat/messages', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const msgData = await msgRes.json();
                    showChat(msgData.messages);
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (err) {
                alert('Connection error. Please try again.');
            } finally {
                btn.innerHTML = 'Start Chatting ✦';
                btn.disabled = false;
            }
        });

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const msg = messageInput.value.trim();
            if (!msg) return;

            // Optimistic Update
            addMessageToUi({ content: msg, direction: 'inbound' });
            messageInput.value = '';

            typingIndicator.classList.remove('hidden');
            scrollToBottom();

            try {
                const res = await fetch('/chat/send', {
                    method: 'POST',
                    body: JSON.stringify({ message: msg }),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await res.json();
                typingIndicator.classList.add('hidden');

                if (data.status === 'success') {
                    addMessageToUi(data.ai_message);
                } else {
                    addMessageToUi({ content: '⚠️ ' + data.message, direction: 'outbound' });
                }
            } catch (err) {
                typingIndicator.classList.add('hidden');
                addMessageToUi({ content: '❌ Sorry, something went wrong on our end.', direction: 'outbound' });
            }
            scrollToBottom();
        });

        logoutBtn.addEventListener('click', async () => {
            if (!confirm('Are you sure you want to logout?')) return;
            await fetch('/chat/logout', { 
                method: 'POST', 
                headers: { 
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            location.reload();
        });

        function showChat(messages = [], isSearchResult = false) {
            authScreen.classList.remove('active');
            chatScreen.classList.add('active');
            chatMessages.innerHTML = '';
            
            if (messages.length === 0) {
                if (isSearchResult) {
                    const div = document.createElement('div');
                    div.style.textAlign = 'center';
                    div.style.padding = '2rem';
                    div.style.color = 'var(--text-muted)';
                    div.textContent = 'No past chats found matching your filters.';
                    chatMessages.appendChild(div);
                } else {
                    addMessageToUi({ content: 'Habari! I am your AI tutor. How can I help you learn today?', direction: 'outbound' });
                }
            } else {
                messages.forEach(addMessageToUi);
            }
            scrollToBottom();
        }

        function addMessageToUi(msg) {
            const div = document.createElement('div');
            div.className = `message ${msg.direction === 'inbound' ? 'inbound' : 'outbound'}`;
            // Simple newline to <br> conversion
            div.innerHTML = msg.content.replace(/\n/g, '<br>');
            chatMessages.appendChild(div);
        }

        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });
</script>
</body>
</html>
