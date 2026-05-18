<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Hub – HuruLearn</title>
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
            --amber-light: #fcd34d;
            --teal: #14b8a6;
            --white: #ffffff;
            --gray-100: #f3f4f6;
            --gray-400: #9ca3af;
            --gray-800: #1f2937;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--indigo-dark); color: var(--white); min-height: 100vh; overflow-x: hidden; }
        
        .bg-glow {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 10% 20%, rgba(59,130,246,0.1) 0%, transparent 40%),
                        radial-gradient(circle at 90% 80%, rgba(245,158,11,0.08) 0%, transparent 40%);
            z-index: -1;
        }

        /* Nav */
        nav {
            padding: 1.5rem 2rem; display: flex; align-items: center; justify-content: space-between;
            background: rgba(15,13,46,0.8); backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.08); position: sticky; top: 0; z-index: 100;
        }
        .logo { display: flex; align-items: center; gap: .8rem; text-decoration: none; color: #fff; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.2rem; }
        .logo img { width: 32px; height: 32px; }

        .container { max-width: 1200px; margin: 0 auto; padding: 3rem 2rem; }
        
        header { margin-bottom: 3rem; }
        header h1 { font-family: 'Space Grotesk', sans-serif; font-size: 2.5rem; margin-bottom: .5rem; background: linear-gradient(135deg, #fff, var(--gray-400)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        header p { color: var(--gray-400); font-size: 1.1rem; }

        .section-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: .8rem; }
        .section-title span { width: 32px; height: 4px; background: var(--amber); border-radius: 2px; }

        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 2rem; margin-bottom: 4rem; }
        
        .card {
            background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px; padding: 2rem; transition: all .3s;
            display: flex; flex-direction: column; justify-content: space-between;
            position: relative; overflow: hidden;
        }
        .card:hover { transform: translateY(-5px); border-color: rgba(59,130,246,0.3); background: rgba(255,255,255,0.05); box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
        .card::before { content: ''; position: absolute; top: 0; right: 0; width: 80px; height: 80px; background: linear-gradient(135deg, transparent, rgba(255,255,255,0.03)); border-radius: 0 0 0 80px; }
        
        .tag { display: inline-block; padding: .3rem .8rem; border-radius: 6px; font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 1rem; width: fit-content; }
        .tag-system { background: rgba(20,184,166,0.15); color: #2dd4bf; border: 1px solid rgba(20,184,166,0.3); }
        .tag-public { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.3); }
        
        .card h3 { font-family: 'Space Grotesk', sans-serif; font-size: 1.3rem; margin-bottom: .8rem; }
        .card p { font-size: .9rem; color: var(--gray-400); line-height: 1.6; margin-bottom: 1.5rem; flex-grow: 1; }
        
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: .8rem 1.5rem; border-radius: 12px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all .2s; border: none; font-size: .9rem; }
        .btn-primary { background: linear-gradient(135deg, var(--blue), #1d4ed8); color: #fff; box-shadow: 0 4px 15px rgba(59,130,246,0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(59,130,246,0.45); }
        .btn-outline { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: #fff; }
        .btn-outline:hover { background: rgba(255,255,255,0.1); border-color: var(--blue-light); }

        .fab { position: fixed; bottom: 2rem; right: 2rem; width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, var(--amber), #e67e22); color: #fff; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 30px rgba(245,158,11,0.5); cursor: pointer; transition: all .3s; z-index: 100; border: none; }
        .fab:hover { transform: scale(1.1) rotate(90deg); box-shadow: 0 12px 40px rgba(245,158,11,0.65); }
        .fab svg { width: 32px; height: 32px; }

        /* Modal */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); display: none; align-items: center; justify-content: center; z-index: 1000; padding: 2rem; }
        .modal { background: #1a1640; border: 1px solid rgba(255,255,255,0.1); border-radius: 24px; width: 100%; max-width: 500px; padding: 2.5rem; position: relative; }
        .modal h2 { font-family: 'Space Grotesk', sans-serif; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-size: .85rem; color: var(--gray-400); margin-bottom: .5rem; font-weight: 500; }
        .form-group input, .form-group textarea { width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: .8rem 1rem; color: #fff; font-size: .95rem; outline: none; }
        .form-group input:focus, .form-group textarea:focus { border-color: var(--blue); }
        
        @media (max-width: 768px) {
            .grid { grid-template-columns: 1fr; }
            header h1 { font-size: 2rem; }
        }
    </style>
</head>
<body>
    <div class="bg-glow"></div>
    
    <nav>
        <a href="/" class="logo">
            <img src="/logo.svg" alt="HuruLearn">
            <span>HuruLearn Community</span>
        </a>
        <a href="{{ route('chat.index') }}" class="btn btn-outline" style="padding: .5rem 1rem; font-size: .8rem;">Back to Chat</a>
    </nav>

    <div class="container">
        <header>
            <h1>Welcome to the Community</h1>
            <p>Connect with students, teachers, and stakeholders. Discuss anything!</p>
        </header>

        @if(session('error'))
            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); padding: 1rem; border-radius: 12px; color: #fca5a5; margin-bottom: 2rem; font-size: .9rem;">
                {{ session('error') }}
            </div>
        @endif

        @if($joinedThreads->count() > 0)
            <div class="section-title"><span></span> Your Threads</div>
            <div class="grid">
                @foreach($joinedThreads as $thread)
                <div class="card">
                    <div>
                        <span class="tag {{ $thread->is_system ? 'tag-system' : 'tag-public' }}">
                            {{ $thread->is_system ? 'Official' : 'Community' }}
                        </span>
                        <h3>{{ $thread->title }}</h3>
                        <p>{{ Str::limit($thread->description, 100) }}</p>
                    </div>
                    <a href="{{ route('community.show', $thread->slug) }}" class="btn btn-primary">Open Thread</a>
                </div>
                @endforeach
            </div>
        @endif

        <div class="section-title"><span></span> Explore Public Discussions</div>
        <div class="grid">
            @foreach($publicThreads as $thread)
                @if(!$joinedThreads->contains($thread))
                <div class="card">
                    <div>
                        <span class="tag {{ $thread->is_system ? 'tag-system' : 'tag-public' }}">
                            {{ $thread->is_system ? 'Official' : 'Public' }}
                        </span>
                        <h3>{{ $thread->title }}</h3>
                        <p>{{ Str::limit($thread->description, 100) }}</p>
                    </div>
                    <form action="{{ route('community.join', $thread->slug) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline" style="width: 100%;">Join Discussion</button>
                    </form>
                </div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Create Thread FAB -->
    <button class="fab" onclick="document.getElementById('createModal').style.display='flex'">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    </button>

    <!-- Create Thread Modal -->
    <div class="modal-overlay" id="createModal" onclick="if(event.target == this) this.style.display='none'">
        <div class="modal">
            <h2>Start a New Thread</h2>
            <form action="{{ route('community.threads.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" placeholder="e.g. Physics Study Group" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" placeholder="What is this thread about?"></textarea>
                </div>
                <div class="form-group" style="display: flex; align-items: center; gap: .8rem;">
                    <input type="checkbox" name="is_private" id="is_private" style="width: auto;">
                    <label for="is_private" style="margin-bottom: 0;">Make this a private thread</label>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="button" class="btn btn-outline" style="flex: 1;" onclick="document.getElementById('createModal').style.display='none'">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="flex: 1;">Create Thread</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
