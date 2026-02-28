<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $thread->title }} – HuruLearn Community</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --indigo: #1e1b4b;
            --indigo-dark: #0f0d2e;
            --blue: #3b82f6;
            --blue-light: #60a5fa;
            --amber: #f59e0b;
            --white: #ffffff;
            --gray-100: #f3f4f6;
            --gray-400: #9ca3af;
            --gray-800: #1f2937;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--indigo-dark); color: var(--white); height: 100vh; display: flex; flex-direction: column; overflow: hidden; }
        
        /* Nav */
        nav {
            padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between;
            background: rgba(15,13,46,0.9); backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.08); z-index: 100;
        }
        .nav-left { display: flex; align-items: center; gap: 1rem; }
        .back-btn { color: var(--gray-400); text-decoration: none; display: flex; align-items: center; gap: .5rem; font-size: .9rem; transition: color .2s; }
        .back-btn:hover { color: #fff; }
        .thread-info h1 { font-family: 'Space Grotesk', sans-serif; font-size: 1.1rem; font-weight: 700; }
        .thread-info p { font-size: .75rem; color: var(--gray-400); }

        .chat-container { flex: 1; display: flex; overflow: hidden; position: relative; }
        
        /* Sidebar */
        .sidebar {
            width: 280px; background: rgba(15,13,46,0.5); border-right: 1px solid rgba(255,255,255,0.08);
            padding: 1.5rem; display: flex; flex-direction: column; gap: 2rem;
        }
        .sidebar-section h4 { font-size: .75rem; text-transform: uppercase; letter-spacing: .1em; color: var(--gray-400); margin-bottom: 1rem; }
        .member-list { display: flex; flex-direction: column; gap: .8rem; }
        .member-item { display: flex; align-items: center; gap: .8rem; font-size: .9rem; }
        .avatar { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--blue), var(--teal)); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .8rem; }
        
        /* Messages Area */
        .main-chat { flex: 1; display: flex; flex-direction: column; background: rgba(255,255,255,0.01); }
        .messages-list { flex: 1; overflow-y: auto; padding: 2rem; display: flex; flex-direction: column; gap: 1.5rem; scroll-behavior: smooth; }
        
        .message { display: flex; gap: 1rem; max-width: 85%; }
        .message.own { align-self: flex-end; flex-direction: row-reverse; }
        
        .msg-content { display: flex; flex-direction: column; gap: .3rem; }
        .msg-bubble { 
            padding: .8rem 1.2rem; border-radius: 18px; font-size: .95rem; line-height: 1.5;
            background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);
        }
        .message.own .msg-bubble { background: linear-gradient(135deg, var(--blue), #1d4ed8); border: none; }
        
        .msg-meta { font-size: .7rem; color: var(--gray-400); display: flex; gap: .8rem; }
        .message.own .msg-meta { justify-content: flex-end; }
        
        /* Input Area */
        .input-area { padding: 1.5rem 2rem; background: rgba(15,13,46,0.8); border-top: 1px solid rgba(255,255,255,0.08); }
        .input-wrapper {
            max-width: 900px; margin: 0 auto; display: flex; gap: 1rem;
            background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
            padding: .6rem .8rem; border-radius: 16px; align-items: center;
            transition: border-color .3s;
        }
        .input-wrapper:focus-within { border-color: var(--blue); }
        .input-wrapper textarea {
            flex: 1; background: transparent; border: none; color: #fff; outline: none;
            padding: .5rem; font-size: .95rem; resize: none; max-height: 120px;
        }
        .send-btn {
            width: 42px; height: 42px; border-radius: 12px;
            background: var(--blue); color: #fff; border: none;
            display: flex; align-items: center; justify-content: center; cursor: pointer;
            transition: all .2s;
        }
        .send-btn:hover { background: var(--blue-light); transform: scale(1.05); }
        .send-btn:active { transform: scale(0.95); }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .message { max-width: 95%; }
            .messages-list { padding: 1rem; }
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-left">
            <a href="{{ route('community.index') }}" class="back-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div class="thread-info">
                <h1>{{ $thread->title }}</h1>
                <p>{{ $members->count() }} members · {{ $thread->is_private ? 'Private' : 'Public' }}</p>
            </div>
        </div>
        <div class="nav-right">
            <form action="{{ route('community.leave', $thread->slug) }}" method="POST">
                @csrf
                <button type="submit" style="background: none; border: none; color: #f87171; font-size: .8rem; font-weight: 600; cursor: pointer;">Leave Thread</button>
            </form>
        </div>
    </nav>

    <div class="chat-container">
        <aside class="sidebar">
            <div class="sidebar-section">
                <h4>Description</h4>
                <p style="font-size: .85rem; color: var(--gray-400); line-height: 1.5;">{{ $thread->description ?? 'No description provided.' }}</p>
            </div>
            <div class="sidebar-section">
                <h4>Members</h4>
                <div class="member-list">
                    @foreach($members as $member)
                    <div class="member-item">
                        <div class="avatar">{{ strtoupper(substr($member->name ?? $member->phone_number, 0, 1)) }}</div>
                        <span>{{ $member->name ?? $member->phone_number }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </aside>

        <main class="main-chat">
            <div class="messages-list" id="messagesList">
                @foreach($posts as $post)
                <div class="message {{ $post->user_id == $user->id ? 'own' : '' }}">
                    <div class="msg-content">
                        @if($post->user_id != $user->id)
                        <div class="msg-meta" style="margin-bottom: .2rem;">
                            <span style="font-weight: 600; color: var(--blue-light);">{{ $post->user->name ?? $post->user->phone_number }}</span>
                        </div>
                        @endif
                        <div class="msg-bubble">{{ $post->content }}</div>
                        <div class="msg-meta">{{ $post->created_at->format('H:i') }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="input-area">
                <form id="chatForm" class="input-wrapper">
                    @csrf
                    <textarea id="messageInput" placeholder="Type your message..." rows="1"></textarea>
                    <button type="submit" class="send-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                    </button>
                </form>
            </div>
        </main>
    </div>

    <script>
        const messagesList = document.getElementById('messagesList');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');

        // Scroll to bottom
        messagesList.scrollTop = messagesList.scrollHeight;

        // Auto-expand textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const content = messageInput.value.trim();
            if(!content) return;

            messageInput.value = '';
            messageInput.style.height = 'auto';

            // Optimistic UI update
            const tempId = Date.now();
            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });
            
            const msgHtml = `
                <div class="message own" id="temp-${tempId}">
                    <div class="msg-content">
                        <div class="msg-bubble">${content}</div>
                        <div class="msg-meta">${time} · Sending...</div>
                    </div>
                </div>
            `;
            messagesList.insertAdjacentHTML('beforeend', msgHtml);
            messagesList.scrollTop = messagesList.scrollHeight;

            try {
                const response = await fetch("{{ route('community.posts.store', $thread->slug) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ content })
                });

                if(response.ok) {
                    const data = await response.json();
                    const tempMsg = document.getElementById(`temp-${tempId}`);
                    tempMsg.querySelector('.msg-meta').innerText = time;
                } else {
                    alert('Failed to send message.');
                }
            } catch (error) {
                console.error(error);
                alert('An error occurred.');
            }
        });

        // Polling for new messages (simulated real-time)
        setInterval(async () => {
            // In a real app, we'd fetch messages newer than the last ID
            // For now, we'll just keep it simple
        }, 5000);
    </script>
</body>
</html>
