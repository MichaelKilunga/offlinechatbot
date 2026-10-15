<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} &mdash; HuruLearn Admin</title>
    {{-- Prevent admin panel from being indexed by search engines --}}
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" href="/logo.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/logo.svg">
    <meta name="theme-color" content="#1e1b4b">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --indigo: #1e1b4b; --indigo-dark: #0f0d2e; --indigo-mid: #16143a;
            --blue: #3b82f6; --blue-light: #60a5fa;
            --amber: #f59e0b; --amber-light: #fcd34d;
            --teal: #14b8a6; --teal-light: #5eead4;
            --white: #ffffff;
            --gray-50: #f8fafc; --gray-100: #f1f5f9; --gray-200: #e2e8f0;
            --gray-300: #cbd5e1; --gray-400: #94a3b8; --gray-500: #64748b;
            --gray-600: #475569; --gray-700: #334155; --gray-800: #1e293b; --gray-900: #0f172a;
            --sidebar-w: 240px;
            --success: #10b981; --danger: #ef4444; --warning: #f59e0b; --red: #dc2626;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background: #0b0a1e; color: var(--gray-100); min-height: 100vh; }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-w); background: var(--indigo-dark);
            border-right: 1px solid rgba(255,255,255,0.07);
            position: fixed; top: 0; left: 0; height: 100vh;
            display: flex; flex-direction: column; z-index: 100;
            transition: transform .3s;
        }
        .sidebar-logo {
            padding: 1.5rem 1.2rem; border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex; align-items: center; gap: .7rem; text-decoration: none;
        }
        .sidebar-logo-text { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.1rem; color: #fff; line-height: 1.1; }
        .sidebar-logo-sub { font-size: .65rem; color: var(--gray-500); font-weight: 500; letter-spacing: .05em; text-transform: uppercase; }
        .sidebar-nav { flex: 1; padding: 1.2rem 0; overflow-y: auto; }
        .sidebar-section { padding: .4rem 1.2rem .2rem; font-size: .65rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--gray-600); margin-top: .5rem; }
        .sidebar-link {
            display: flex; align-items: center; gap: .75rem;
            padding: .6rem 1.2rem; margin: .1rem .6rem; border-radius: 10px;
            text-decoration: none; color: var(--gray-400); font-size: .85rem; font-weight: 500;
            transition: all .2s; position: relative;
        }
        .sidebar-link:hover { background: rgba(255,255,255,0.06); color: var(--white); }
        .sidebar-link.active { background: rgba(245,158,11,0.15); color: var(--amber-light); }
        .sidebar-link.active::before { content: ''; position: absolute; left: 0; top: 25%; bottom: 25%; width: 3px; background: var(--amber); border-radius: 0 3px 3px 0; }
        .sidebar-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }
        .sidebar-count { margin-left: auto; font-size: .68rem; font-weight: 700; padding: .1rem .45rem; border-radius: 50px; background: rgba(239,68,68,0.2); color: #fca5a5; }
        .sidebar-footer { padding: 1rem 1.2rem; border-top: 1px solid rgba(255,255,255,0.07); }
        .sidebar-footer a { display: flex; align-items: center; gap: .6rem; text-decoration: none; color: var(--gray-500); font-size: .8rem; transition: color .2s; }
        .sidebar-footer a:hover { color: var(--gray-300); }

        /* TOPBAR */
        .topbar {
            position: fixed; top: 0; left: var(--sidebar-w); right: 0; height: 60px;
            background: rgba(11,10,30,0.9); backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2rem; z-index: 90;
        }
        .topbar-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.05rem; font-weight: 600; color: var(--gray-100); }
        .topbar-right { display: flex; align-items: center; gap: .6rem; flex-wrap: wrap; }
        .topbar-badge {
            padding: .3rem .8rem; border-radius: 50px; font-size: .72rem; font-weight: 600;
            background: rgba(20,184,166,0.15); border: 1px solid rgba(20,184,166,0.3); color: var(--teal-light);
            display: flex; align-items: center; gap: .4rem; text-decoration: none;
        }
        .topbar-badge.live::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--teal); display: inline-block; animation: pulse 2s infinite; }
        .topbar-badge.off { background: rgba(239,68,68,0.15); border-color: rgba(239,68,68,0.3); color: #fca5a5; }
        .topbar-badge.warn { background: rgba(245,158,11,0.15); border-color: rgba(245,158,11,0.3); color: var(--amber-light); }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

        /* MAIN */
        .main-wrapper { margin-left: var(--sidebar-w); min-height: 100vh; width: calc(100% - var(--sidebar-w)); }
        .main-content { margin-top: 60px; padding: 2rem; }

        /* CARDS */
        .card {
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px; overflow: hidden;
        }
        .card-header {
            padding: 1.2rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;
        }
        .card-title { font-family: 'Space Grotesk', sans-serif; font-size: 1rem; font-weight: 600; color: var(--gray-100); }
        .card-subtitle { font-size: .78rem; color: var(--gray-500); }
        .card-body { padding: 1.5rem; }
        .card-footer { padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.07); }

        /* STAT CARDS */
        .stat-card {
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px; padding: 1.3rem 1.5rem; position: relative; overflow: hidden;
            transition: transform .2s, border-color .2s; text-decoration: none; display: block; color: inherit;
        }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-label { font-size: .72rem; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--gray-500); margin-bottom: .45rem; }
        .stat-value { font-family: 'Space Grotesk', sans-serif; font-size: 2rem; font-weight: 700; color: var(--gray-100); line-height: 1; }
        .stat-sub { font-size: .74rem; color: var(--gray-500); margin-top: .5rem; }
        .stat-icon { position: absolute; top: 1.1rem; right: 1.2rem; font-size: 1.4rem; opacity: .6; }

        /* TABLES */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { padding: .75rem 1.2rem; text-align: left; font-size: .7rem; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: var(--gray-500); border-bottom: 1px solid rgba(255,255,255,0.07); white-space: nowrap; }
        .data-table td { padding: .85rem 1.2rem; font-size: .85rem; color: var(--gray-300); border-bottom: 1px solid rgba(255,255,255,0.04); vertical-align: top; }
        .data-table tbody tr { transition: background .15s; }
        .data-table tbody tr:hover { background: rgba(255,255,255,0.03); }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table a { color: var(--blue-light); text-decoration: none; }
        .data-table a:hover { text-decoration: underline; }
        .empty-state { text-align: center; padding: 3rem; color: var(--gray-600); }
        .empty-state .big { font-size: 2rem; margin-bottom: .5rem; }

        /* BADGES */
        .badge { display: inline-flex; align-items: center; gap: .3rem; padding: .25rem .65rem; border-radius: 50px; font-size: .72rem; font-weight: 600; white-space: nowrap; }
        .badge-green { background: rgba(16,185,129,0.15); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.25); }
        .badge-blue { background: rgba(59,130,246,0.15); color: #93c5fd; border: 1px solid rgba(59,130,246,0.25); }
        .badge-amber { background: rgba(245,158,11,0.15); color: var(--amber-light); border: 1px solid rgba(245,158,11,0.25); }
        .badge-red { background: rgba(239,68,68,0.15); color: #fca5a5; border: 1px solid rgba(239,68,68,0.25); }
        .badge-gray { background: rgba(255,255,255,0.07); color: var(--gray-400); border: 1px solid rgba(255,255,255,0.1); }
        .badge-teal { background: rgba(20,184,166,0.15); color: var(--teal-light); border: 1px solid rgba(20,184,166,0.25); }

        /* FORMS */
        .form-label { display: block; font-size: .82rem; font-weight: 500; color: var(--gray-400); margin-bottom: .4rem; }
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: .65rem 1rem; border-radius: 10px;
            background: rgba(255,255,255,0.06); border: 1.5px solid rgba(255,255,255,0.1);
            color: var(--gray-100); font-size: .875rem; font-family: inherit; outline: none;
            transition: border-color .2s, background .2s;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--amber); background: rgba(255,255,255,0.08); }
        .form-input::placeholder, .form-textarea::placeholder { color: var(--gray-600); }
        .form-select option { background: #1e1b4b; color: var(--gray-100); }
        .form-hint { font-size: .75rem; color: var(--gray-600); margin-top: .35rem; }
        .form-group { margin-bottom: 1.2rem; }
        .form-error { font-size: .75rem; color: #fca5a5; margin-top: .35rem; }
        .form-inline { display: flex; gap: .6rem; flex-wrap: wrap; align-items: flex-end; }
        .form-inline .form-group { margin-bottom: 0; }
        .form-inline .form-input, .form-inline .form-select { padding: .5rem .8rem; font-size: .82rem; }
        .form-input.sm, .form-select.sm { padding: .5rem .8rem; font-size: .82rem; }

        /* BUTTONS */
        .btn { display: inline-flex; align-items: center; gap: .5rem; padding: .6rem 1.3rem; border-radius: 10px; font-size: .875rem; font-weight: 600; cursor: pointer; border: none; transition: all .2s; text-decoration: none; font-family: inherit; white-space: nowrap; }
        .btn-primary { background: linear-gradient(135deg, var(--amber), #e67e22); color: #fff; box-shadow: 0 4px 15px rgba(245,158,11,0.25); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245,158,11,0.35); }
        .btn-blue { background: linear-gradient(135deg, var(--blue), #1d4ed8); color: #fff; box-shadow: 0 4px 15px rgba(59,130,246,0.2); }
        .btn-blue:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(59,130,246,0.3); }
        .btn-teal { background: rgba(20,184,166,0.15); color: var(--teal-light); border: 1px solid rgba(20,184,166,0.3); }
        .btn-teal:hover { background: rgba(20,184,166,0.25); }
        .btn-danger { background: rgba(239,68,68,0.15); color: #fca5a5; border: 1px solid rgba(239,68,68,0.3); }
        .btn-danger:hover { background: rgba(239,68,68,0.25); }
        .btn-ghost { background: rgba(255,255,255,0.06); color: var(--gray-300); border: 1px solid rgba(255,255,255,0.1); }
        .btn-ghost:hover { background: rgba(255,255,255,0.1); }
        .btn-sm { padding: .35rem .8rem; font-size: .78rem; }
        .btn-xs { padding: .25rem .6rem; font-size: .72rem; border-radius: 8px; }
        .btn-full { width: 100%; justify-content: center; }
        .btn-row { display: flex; gap: .4rem; flex-wrap: wrap; }
        .btn-row form { display: inline; }

        /* ALERTS */
        .alert { padding: 1rem 1.2rem; border-radius: 12px; font-size: .875rem; margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: .75rem; }
        .alert-success { background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.25); color: #6ee7b7; }
        .alert-error { background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.25); color: #fca5a5; }
        .alert-info { background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.25); color: #93c5fd; }
        .alert-icon { font-size: 1rem; flex-shrink: 0; }

        /* GRID & LAYOUT HELPERS */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 1.1rem; margin-bottom: 1.6rem; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .grid-side { display: grid; grid-template-columns: 340px 1fr; gap: 1.5rem; align-items: start; }
        .stack { display: flex; flex-direction: column; gap: 1.5rem; }
        .divider { height: 1px; background: rgba(255,255,255,0.07); margin: 1.5rem 0; }
        .mono { font-family: 'Courier New', monospace; }
        .muted { color: var(--gray-500); }
        .small { font-size: .78rem; }
        .pre { background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.07); border-radius: 10px; padding: 1rem; font-size: .78rem; color: var(--gray-300); white-space: pre-wrap; word-break: break-word; font-family: 'Courier New', monospace; line-height: 1.6; overflow-x: auto; max-height: 480px; overflow-y: auto; }
        .tabs { display: flex; gap: .4rem; flex-wrap: wrap; }
        .tab { padding: .4rem .9rem; border-radius: 50px; font-size: .78rem; font-weight: 600; text-decoration: none; color: var(--gray-400); background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); }
        .tab.active { background: rgba(245,158,11,0.15); color: var(--amber-light); border-color: rgba(245,158,11,0.3); }
        .tab:hover { color: #fff; }
        .section-title { font-family: 'Space Grotesk', sans-serif; font-size: .95rem; font-weight: 600; color: var(--gray-200); margin: 0 0 .8rem; display: flex; align-items: center; gap: .5rem; }
        .kv { display: grid; grid-template-columns: 180px 1fr; gap: .4rem 1rem; font-size: .83rem; }
        .kv dt { color: var(--gray-500); }
        .kv dd { color: var(--gray-200); word-break: break-word; }

        /* BAR CHART (single series, CSS only) */
        .bars { display: flex; align-items: flex-end; gap: 4px; height: 120px; padding-top: .5rem; }
        .bar-col { flex: 1; display: flex; flex-direction: column; justify-content: flex-end; height: 100%; position: relative; min-width: 0; }
        .bar { width: 100%; border-radius: 4px 4px 0 0; min-height: 2px; background: var(--blue); opacity: .85; transition: opacity .15s; }
        .bar.teal { background: var(--teal); }
        .bar-col:hover .bar { opacity: 1; }
        .bar-col:hover .bar-tip { display: block; }
        .bar-tip { display: none; position: absolute; bottom: calc(100% + 4px); left: 50%; transform: translateX(-50%); background: #1e293b; border: 1px solid rgba(255,255,255,0.12); color: var(--gray-100); font-size: .7rem; padding: .3rem .5rem; border-radius: 6px; white-space: nowrap; z-index: 5; }
        .bar-labels { display: flex; gap: 4px; margin-top: .4rem; }
        .bar-labels span { flex: 1; text-align: center; font-size: .62rem; color: var(--gray-600); overflow: hidden; white-space: nowrap; }

        /* PROGRESS / HEALTH */
        .check { display: flex; align-items: center; gap: .75rem; padding: .6rem 0; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: .85rem; }
        .check:last-child { border-bottom: none; }
        .check-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .check-dot.ok { background: var(--success); }
        .check-dot.bad { background: var(--danger); }

        /* FOOTER */
        .admin-footer { padding: 1.2rem 2rem; border-top: 1px solid rgba(255,255,255,0.07); text-align: center; font-size: .78rem; color: var(--gray-600); }

        /* SCROLLBAR */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

        /* MOBILE */
        @media (max-width: 1024px) {
            .grid-3 { grid-template-columns: 1fr 1fr; }
            .grid-side { grid-template-columns: 1fr; }
            .grid-side > .card[style*="sticky"] { position: static !important; }
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; width: 100%; }
            .main-content { padding: 1rem; }
            .topbar { left: 0; padding: 0 1rem; }
            .mobile-menu-btn { display: flex !important; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            .kv { grid-template-columns: 1fr; }
            .topbar-right .topbar-badge span.lbl { display: none; }
        }
        .mobile-menu-btn { display: none; background: none; border: none; color: var(--gray-400); cursor: pointer; font-size: 1.3rem; }
    </style>
</head>
<body>

@php
    $aiEnabled = \App\Models\SystemSetting::get('ai_enabled', '1') == '1';
    $moderationOn = \App\Models\SystemSetting::get('moderation_enabled', '1') == '1';
    $pendingPosts = \App\Models\CommunityPost::where('is_approved', false)->count();
    $failedJobs = \Illuminate\Support\Facades\Schema::hasTable('failed_jobs') ? \Illuminate\Support\Facades\DB::table('failed_jobs')->count() : 0;
@endphp

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
        <img src="/logo.svg" alt="Logo" style="width: 36px; height: 36px;">
        <div>
            <div class="sidebar-logo-text">HuruLearn</div>
            <div class="sidebar-logo-sub">Admin Panel</div>
        </div>
    </a>
    <nav class="sidebar-nav">
        <div class="sidebar-section">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="sidebar-icon">📊</span> Dashboard
        </a>
        <a href="{{ route('admin.usage.index') }}" class="sidebar-link {{ request()->routeIs('admin.usage.*') ? 'active' : '' }}">
            <span class="sidebar-icon">📈</span> Usage &amp; Insights
        </a>

        <div class="sidebar-section">People &amp; Traffic</div>
        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span class="sidebar-icon">👤</span> Users
        </a>
        <a href="{{ route('admin.conversations.index') }}" class="sidebar-link {{ request()->routeIs('admin.conversations.*') ? 'active' : '' }}">
            <span class="sidebar-icon">💬</span> Conversations
        </a>
        <a href="{{ route('admin.community.index') }}" class="sidebar-link {{ request()->routeIs('admin.community.*') ? 'active' : '' }}">
            <span class="sidebar-icon">🗣️</span> Community
            @if($pendingPosts > 0)<span class="sidebar-count">{{ $pendingPosts }}</span>@endif
        </a>

        <div class="sidebar-section">Knowledge</div>
        <a href="{{ route('admin.curriculum.index') }}" class="sidebar-link {{ request()->routeIs('admin.curriculum.*') ? 'active' : '' }}">
            <span class="sidebar-icon">⚖️</span> Legal Database
        </a>
        <a href="{{ route('admin.providers.index') }}" class="sidebar-link {{ request()->routeIs('admin.providers.*') ? 'active' : '' }}">
            <span class="sidebar-icon">🏢</span> Legal Aid Providers
        </a>
        <a href="{{ route('admin.templates.index') }}" class="sidebar-link {{ request()->routeIs('admin.templates.index') || request()->routeIs('admin.templates.edit') ? 'active' : '' }}">
            <span class="sidebar-icon">🧩</span> Prompt Templates
        </a>
        <a href="{{ route('admin.templates.preview') }}" class="sidebar-link {{ request()->routeIs('admin.templates.preview*') ? 'active' : '' }}">
            <span class="sidebar-icon">🧪</span> Prompt Playground
        </a>

        <div class="sidebar-section">System</div>
        <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <span class="sidebar-icon">⚙️</span> Settings
        </a>
        <a href="{{ route('admin.system.index') }}" class="sidebar-link {{ request()->routeIs('admin.system.index') ? 'active' : '' }}">
            <span class="sidebar-icon">🩺</span> Health &amp; Tools
            @if($failedJobs > 0)<span class="sidebar-count">{{ $failedJobs }}</span>@endif
        </a>
        <a href="{{ route('admin.system.activity') }}" class="sidebar-link {{ request()->routeIs('admin.system.activity') ? 'active' : '' }}">
            <span class="sidebar-icon">📜</span> Activity Log
        </a>
        <a href="/" class="sidebar-link" target="_blank" rel="noopener">
            <span class="sidebar-icon">🌐</span> View Public Site
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="/">← Back to Public Site</a>
    </div>
</aside>

<!-- TOPBAR -->
<div class="topbar">
    <div style="display:flex; align-items:center; gap:1rem;">
        <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>
        <span class="topbar-title">{{ $title ?? 'Dashboard' }}</span>
    </div>
    <div class="topbar-right">
        @if($aiEnabled)
            <a href="{{ route('admin.settings.index') }}" class="topbar-badge live" title="Engine is answering messages"><span class="lbl">Engine Live</span></a>
        @else
            <a href="{{ route('admin.settings.index') }}" class="topbar-badge off" title="Engine paused: maintenance message is sent instead">⏸️ <span class="lbl">Engine Paused</span></a>
        @endif
        @unless($moderationOn)
            <a href="{{ route('admin.settings.index') }}" class="topbar-badge warn" title="Keyword moderation is switched off">🛡️ <span class="lbl">Moderation Off</span></a>
        @endunless
        @if($failedJobs > 0)
            <a href="{{ route('admin.system.index') }}" class="topbar-badge off" title="Failed queue jobs">⚠️ <span class="lbl">{{ $failedJobs }} failed job{{ $failedJobs === 1 ? '' : 's' }}</span></a>
        @endif
    </div>
</div>

<!-- MAIN -->
<div class="main-wrapper">
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success">
                <span class="alert-icon">✓</span> <div>{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <span class="alert-icon">✕</span> <div>{{ session('error') }}</div>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <span class="alert-icon">✕</span>
                <div>
                    <strong>Please fix the following:</strong>
                    <ul style="margin:.4rem 0 0 1rem;">
                        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                    </ul>
                </div>
            </div>
        @endif
        @if(session('tool_output'))
            <div class="card" style="margin-bottom:1.5rem;">
                <div class="card-header"><div class="card-title">🖥️ Command output</div></div>
                <div class="card-body"><pre class="pre" style="max-height:260px;">{{ session('tool_output') ?: '(no output)' }}</pre></div>
            </div>
        @endif
        @yield('content')
    </div><!-- /main-content -->

    <footer class="admin-footer">
        <div>&copy; {{ date('Y') }} HuruLearn by Huru Digital Co. Ltd. &mdash; Legal Guidance SMS Platform</div>
        <div style="margin-top: 0.5rem; display: flex; justify-content: center; gap: 1rem;">
            <a href="{{ route('legal.terms') }}" style="color: var(--gray-600); text-decoration: none;">Terms & Conditions</a>
            <span>&bull;</span>
            <a href="{{ route('legal.privacy') }}" style="color: var(--gray-600); text-decoration: none;">Privacy Policy</a>
        </div>
    </footer>

</div><!-- /main-wrapper -->

<script>
    const menuBtn = document.getElementById('mobileMenuBtn');
    const sidebar = document.getElementById('sidebar');
    if (menuBtn) menuBtn.addEventListener('click', () => sidebar.classList.toggle('open'));
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 768 && sidebar.classList.contains('open') && !sidebar.contains(e.target) && e.target !== menuBtn) {
            sidebar.classList.remove('open');
        }
    });
</script>
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js').then((reg) => {
                console.log('ServiceWorker registration successful');
            }).catch((err) => {
                console.log('ServiceWorker registration failed:', err);
            });
        });
    }
</script>
@stack('scripts')
</body>
</html>
