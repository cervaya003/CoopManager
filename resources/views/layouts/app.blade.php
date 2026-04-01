<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>@yield('title', 'CoopManager') · Sistema de Cooperaciones</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /*  Variables  */
        :root {
            --ink:      #0f172a;
            --ink-2:    #334155;
            --ink-3:    #64748b;
            --ink-4:    #94a3b8;
            --surface:  #f8fafc;
            --card:     #ffffff;
            --border:   #e2e8f0;
            --accent:   #059669;
            --accent-2: #d1fae5;
            --danger:   #dc2626;
            --warning:  #d97706;
            --info:     #2563eb;
            --radius:   12px;
            --shadow:   0 1px 3px rgba(0,0,0,.06), 0 4px 12px rgba(0,0,0,.06);
            --shadow-md:0 4px 24px rgba(0,0,0,.10);
        }

        /*  Reset  */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 16px; -webkit-font-smoothing: antialiased; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--surface);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
        }

        /*  Sidebar  */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: var(--ink);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .brand-name {
            font-family: 'DM Serif Display', serif;
            font-size: 1.4rem;
            color: #fff;
            letter-spacing: -.5px;
        }
        .brand-sub {
            font-size: .72rem;
            color: var(--ink-4);
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
        }
        .nav-label {
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--ink-4);
            padding: 0 12px;
            margin: 16px 0 6px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: #94a3b8;
            text-decoration: none;
            font-size: .88rem;
            font-weight: 500;
            transition: all .15s;
            margin-bottom: 2px;
        }
        .nav-item:hover { background: rgba(255,255,255,.07); color: #fff; }
        .nav-item.active { background: var(--accent); color: #fff; }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,.08);
        }
        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
        }
        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: .8rem;
            flex-shrink: 0;
        }
        .user-info { flex: 1; min-width: 0; }
        .user-name { font-size: .82rem; color: #fff; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: .7rem; color: var(--ink-4); }
        .logout-btn {
            width: 100%;
            margin-top: 8px;
            padding: 8px 12px;
            background: rgba(255,255,255,.06);
            border: none;
            border-radius: 8px;
            color: #94a3b8;
            font-size: .82rem;
            cursor: pointer;
            text-align: left;
            font-family: inherit;
            transition: all .15s;
        }
        .logout-btn:hover { background: rgba(220,38,38,.2); color: #fca5a5; }

        /*  Main ─ */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .topbar {
            height: 60px;
            background: var(--card);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .page-title {
            font-weight: 600;
            font-size: 1rem;
            flex: 1;
        }
        .content {
            padding: 28px;
            flex: 1;
        }

        /*  Cards  */
        .card {
            background: var(--card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }
        .card-header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .card-title {
            font-weight: 600;
            font-size: .95rem;
        }
        .card-body { padding: 20px; }

        /*  Stat cards  */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
        }
        .stat-label { font-size: .78rem; color: var(--ink-3); font-weight: 500; text-transform: uppercase; letter-spacing: .05em; }
        .stat-value { font-size: 1.8rem; font-weight: 700; color: var(--ink); margin-top: 6px; font-family: 'DM Serif Display', serif; }
        .stat-sub   { font-size: .78rem; color: var(--ink-3); margin-top: 4px; }
        .stat-card.accent { border-color: var(--accent); }
        .stat-card.accent .stat-value { color: var(--accent); }

        /*  Buttons  */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: .85rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            font-family: inherit;
            transition: all .15s;
            white-space: nowrap;
        }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: #047857; }
        .btn-outline { background: transparent; color: var(--ink-2); border: 1px solid var(--border); }
        .btn-outline:hover { background: var(--surface); }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-sm { padding: 6px 12px; font-size: .8rem; }
        .btn svg { width: 16px; height: 16px; }

        /*  Badges ─ */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 600;
        }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-gray   { background: #f1f5f9; color: #475569; }

        /*  Progress ─ */
        .progress-bar {
            height: 8px;
            background: var(--border);
            border-radius: 99px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: var(--accent);
            border-radius: 99px;
            transition: width .3s;
        }
        .progress-fill.warning { background: var(--warning); }
        .progress-fill.danger  { background: var(--danger); }

        /*  Forms  */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block;
            font-size: .82rem;
            font-weight: 600;
            color: var(--ink-2);
            margin-bottom: 6px;
        }
        .form-control {
            width: 100%;
            padding: 9px 13px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: .88rem;
            font-family: inherit;
            color: var(--ink);
            background: var(--card);
            transition: border-color .15s;
            outline: none;
        }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(5,150,105,.1); }
        textarea.form-control { resize: vertical; min-height: 90px; }
        .form-hint { font-size: .75rem; color: var(--ink-3); margin-top: 4px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /*  Table  */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: .86rem; }
        thead th {
            text-align: left;
            padding: 10px 16px;
            font-size: .72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--ink-3);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        tbody td { padding: 12px 16px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: var(--surface); }

        /*  Alerts / Flash  */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: .86rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /*  Utilities  */
        .flex { display: flex; }
        .flex-1 { flex: 1; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .mt-1 { margin-top: 4px; }
        .mt-4 { margin-top: 16px; }
        .mb-4 { margin-bottom: 16px; }
        .mb-6 { margin-bottom: 24px; }
        .text-sm { font-size: .82rem; }
        .text-xs { font-size: .75rem; }
        .text-muted { color: var(--ink-3); }
        .text-right { text-align: right; }
        .font-bold { font-weight: 700; }
        .truncate { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /*  Responsive  */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .form-row { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .content { padding: 16px; }
        }
    </style>
</head>
<body>

{{--  Sidebar ─ --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-name">CoopManager</div>
        <div class="brand-sub">Cooperaciones</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">General</div>

        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('cooperaciones.index') }}"
           class="nav-item {{ request()->routeIs('cooperaciones.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Cooperaciones
        </a>

        @if(Auth::user()->esAdmin())
        <div class="nav-label">Administración</div>

        <a href="{{ route('pagos.index') }}"
           class="nav-item {{ request()->routeIs('pagos.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Todos los Pagos
        </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="user-chip">
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ ucfirst(Auth::user()->rol) }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Cerrar sesión</button>
        </form>
    </div>
</aside>

{{--  Main ─ --}}
<div class="main">
    <div class="topbar">
        <span class="page-title">@yield('page-title', 'Panel')</span>
        @yield('topbar-actions')
    </div>

    <div class="content">
        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</div>

</body>
</html>
