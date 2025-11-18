<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Mechanic Africa</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #F3F4F6; }
        
        .admin-container { display: flex; min-height: 100vh; }
        
        /* Sidebar */
        .sidebar { width: 260px; background: #1F2937; color: white; position: fixed; height: 100vh; overflow-y: auto; }
        .sidebar-header { padding: 24px; border-bottom: 1px solid #374151; }
        .sidebar-title { font-size: 20px; font-weight: 700; }
        .sidebar-nav { padding: 16px 0; }
        .nav-item { padding: 12px 24px; display: block; color: #D1D5DB; text-decoration: none; transition: all 0.2s; border-left: 3px solid transparent; }
        .nav-item:hover, .nav-item.active { background: #374151; color: white; border-left-color: #EF4444; }
        
        /* Main Content */
        .main-content { margin-left: 260px; flex: 1; padding: 24px; }
        .top-bar { background: white; padding: 16px 24px; border-radius: 8px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .user-info { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: #EF4444; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; }
        .logout-btn { background: #EF4444; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; }
        .logout-btn:hover { background: #DC2626; }
        
        .page-header { margin-bottom: 24px; }
        .page-title { font-size: 28px; font-weight: 800; color: #111827; margin-bottom: 8px; }
        .page-subtitle { color: #6B7280; font-size: 14px; }
        
        .content-card { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        
        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .stat-label { color: #6B7280; font-size: 14px; margin-bottom: 8px; }
        .stat-value { font-size: 32px; font-weight: 800; color: #111827; }
        .stat-change { font-size: 12px; color: #10B981; margin-top: 4px; }
        
        /* Table Styles */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #F9FAFB; padding: 12px; text-align: left; font-weight: 600; font-size: 12px; color: #6B7280; text-transform: uppercase; }
        td { padding: 16px 12px; border-bottom: 1px solid #E5E7EB; }
        tr:hover { background: #F9FAFB; }
        
        .badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .badge-unread { background: #FEE2E2; color: #DC2626; }
        .badge-read { background: #D1FAE5; color: #047857; }
        .badge-pending { background: #FEF3C7; color: #D97706; }
        .badge-approved { background: #D1FAE5; color: #047857; }
        .badge-rejected { background: #FEE2E2; color: #DC2626; }
        
        .btn { padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; border: none; text-decoration: none; display: inline-block; }
        .btn-primary { background: #EF4444; color: white; }
        .btn-primary:hover { background: #DC2626; }
        .btn-secondary { background: #6B7280; color: white; }
        .btn-secondary:hover { background: #4B5563; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; }
        .alert-success { background: #D1FAE5; color: #047857; border: 1px solid #6EE7B7; }
        .alert-error { background: #FEE2E2; color: #DC2626; border: 1px solid #FECACA; }
        
        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .sidebar { width: 200px; }
            .main-content { margin-left: 200px; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title">MECHANIC AFRICA</div>
                <div style="font-size: 12px; color: #9CA3AF;">Admin Panel</div>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 Dashboard</a>
                <a href="{{ route('admin.contacts.index') }}" class="nav-item {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">✉️ Contacts</a>
                <a href="{{ route('admin.partners.index') }}" class="nav-item {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">🤝 Partners</a>
                <a href="{{ route('admin.technicians.index') }}" class="nav-item {{ request()->routeIs('admin.technicians.*') ? 'active' : '' }}">🔧 Technicians</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="top-bar">
                <div>
                    <strong>{{ session('admin_username') }}</strong>
                    <span style="color: #6B7280; font-size: 14px;">({{ ucfirst(session('admin_role')) }})</span>
                </div>
                <div class="user-info">
                    <div class="user-avatar">{{ strtoupper(substr(session('admin_username'), 0, 1)) }}</div>
                    <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>
    @stack('scripts')
</body>
</html>
