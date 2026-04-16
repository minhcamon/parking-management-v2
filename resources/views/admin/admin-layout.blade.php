<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Parking Manager')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <style>
        /* Mobile toggle layout helper */
        .header-content { display: flex; align-items: center; }
        
        /* Dropup for sidebar profile */
        .sidebar .dropdown-menu {
            top: auto;
            bottom: 110%;
            transform: translateY(10px);
        }
        .sidebar .dropdown-menu.active {
            transform: translateY(0);
        }
        .sidebar .user-profile-trigger {
            background: transparent;
            border-color: transparent;
            color: var(--text-muted);
            justify-content: space-between;
        }
        .sidebar .user-profile-trigger:hover {
            color: var(--text-main);
            background: var(--nav-hover-bg);
            border-color: transparent;
        }
    </style>
    @stack('styles')
</head>
<body>
    <script>
        // Init Theme
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>

    <div class="ambient-glow"></div>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="brand">
            <i class="ph-fill ph-car-profile"></i>
            <h2>ParkGrid</h2>
        </div>
        <ul class="nav-items">
            <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}"><i class="ph ph-squares-four"></i> Dashboard</a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.parking-site.*') ? 'active' : '' }}">
                <a href="{{ route('admin.parking-site.index') }}"><i class="ph ph-garage"></i> Parking Site</a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                <a href="{{ route('admin.staff.index') }}"><i class="ph ph-users"></i> Staff & Users</a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.monthly-passes.*') ? 'active' : '' }}">
                <a href="{{ route('admin.monthly-passes.index') }}"><i class="ph ph-identification-card"></i> Monthly Passes</a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.reports.transactions') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.transactions') }}"><i class="ph ph-list-dashes"></i> Transactions</a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.reports.revenue') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.revenue') }}"><i class="ph ph-chart-line-up"></i> Revenue Reports</a>
            </li>
        </ul>

        <div class="user-profile" id="userProfileBtn" style="margin-top: auto; width: 100%;">
            <div class="user-profile-trigger" style="border-radius: 12px; padding: 0.8rem 1rem;">
                <span style="display: flex; align-items:center; gap:10px;">
                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=6366f1&color=fff" alt="Admin" style="width:32px; height:32px; border-radius:50%;">
                    Admin
                </span>
                <i class="ph ph-caret-up"></i>
            </div>
            
            <div class="dropdown-menu" id="userProfileMenu" style="width: 100%;">
                <div class="dropdown-item">
                    <span style="display:flex; align-items:center; gap:8px;">
                        <i class="ph ph-moon"></i> Dark Mode
                    </span>
                    <label class="theme-switch">
                        <input type="checkbox" id="themeToggle">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <span style="display:flex; align-items:center; gap:8px;">
                        <i class="ph ph-gear"></i> Settings
                    </span>
                </a>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="dropdown-item" style="color: #ef4444; background: transparent; border: none; width: 100%; text-align: left;">
                        <span style="display:flex; align-items:center; gap:8px;">
                            <i class="ph ph-sign-out"></i> Logout
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-wrapper">
        <header class="header">
            <div class="header-content">
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="ph ph-list"></i>
                </button>
                <div class="header-title">@yield('page-title', 'Overview')</div>
            </div>
        </header>

        <section class="content">
            @yield('content')
        </section>
    </main>

    @stack('scripts')
    <script src="{{ asset('assets/js/theme-switcher.js') }}"></script>
    <script>
        // Mobile Toggle Logic
        document.getElementById('mobileToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('open');
        });
    </script>
</body>
</html>
