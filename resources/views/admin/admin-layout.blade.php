<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Parking Manager')</title>
    
    <!-- Google Fonts: Be Vietnam Pro (Excellent Vietnamese Support) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                <a href="{{ route('admin.dashboard') }}"><i class="ph ph-squares-four"></i> Tổng quan</a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.parking-site.*') ? 'active' : '' }}">
                <a href="{{ route('admin.parking-site.index') }}"><i class="ph ph-garage"></i> Quản lý bãi đỗ</a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                <a href="{{ route('admin.staff.index') }}"><i class="ph ph-users"></i> Nhân viên & Người dùng</a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.monthly-passes.*') ? 'active' : '' }}">
                <a href="{{ route('admin.monthly-passes.index') }}"><i class="ph ph-identification-card"></i> Vé tháng (Passes)</a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.reports.transactions') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.transactions') }}"><i class="ph ph-list-dashes"></i> Lịch sử giao dịch</a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.reports.revenue') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.revenue') }}"><i class="ph ph-chart-line-up"></i> Báo cáo doanh thu</a>
            </li>
            <div class="dropdown-divider my-4 opacity-50"></div>
            <li class="nav-item">
                <a href="{{ route('staff.dashboard') }}" target="_blank" class="text-accent-secondary">
                    <i class="ph ph-desktop"></i> Giao diện POS (Staff)
                </a>
            </li>
        </ul>

        <div class="user-profile mt-auto w-full" id="userProfileBtn">
            <div class="user-profile-trigger rounded-xl px-4 py-[0.8rem]">
                <span class="flex items-center gap-2.5">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff" alt="Admin" class="w-8 h-8 rounded-full">
                    {{ Auth::user()->name }}
                </span>
                <i class="ph ph-caret-up"></i>
            </div>
            
            <div class="dropdown-menu w-full" id="userProfileMenu">
                <div class="dropdown-item">
                    <span class="flex items-center gap-2">
                        <i class="ph ph-moon"></i> Dark Mode
                    </span>
                    <label class="theme-switch">
                        <input type="checkbox" id="themeToggle">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <span class="flex items-center gap-2">
                        <i class="ph ph-gear"></i> Settings
                    </span>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item text-red-500 bg-transparent border-none w-full text-left">
                        <span class="flex items-center gap-2">
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

    @include('components.toast')
</body>
</html>
