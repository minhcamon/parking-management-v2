<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff POS - ParkGrid')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


    <style>
        .staff-body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .staff-header {
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--glass-border);
            
            /* Đã bật lại position sticky và set z-index cao nhất */
            position: sticky; 
            top: 0;
            z-index: 50; 
        }

        .staff-nav {
            display: flex;
            gap: 1rem;
        }

        .staff-nav-item {
            padding: 0.5rem 1rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .staff-nav-item:hover {
            color: var(--text-main);
            background: var(--nav-hover-bg);
        }

        .staff-nav-item.active {
            color: var(--accent-primary);
            background: rgba(99, 102, 241, 0.1);
        }

        .staff-container {
            padding: 2rem;
            max-width: 1600px;
            margin: 0 auto;
            width: 100%;
            flex-grow: 1;
        }

        /* --- CSS MỚI CHO MOBILE MENU --- */
        .mobile-menu-btn {
            display: none; /* Ẩn ở desktop */
            background: none;
            border: none;
            color: var(--text-main);
            font-size: 1.8rem;
            cursor: pointer;
            padding: 0;
            transition: color 0.2s;
        }
        .mobile-menu-btn:hover { color: var(--accent-primary); }

        .mobile-menu-overlay {
            position: absolute;
            top: 70px; /* Nằm ngay dưới header */
            left: 0;
            width: 100%;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            padding: 1rem 2rem 2rem 2rem;
            gap: 0.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .mobile-nav-item {
            padding: 1rem;
            font-size: 1.1rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(0,0,0,0.02);
            border: 1px solid transparent;
        }

        :root[data-theme="dark"] .mobile-nav-item {
            background: rgba(0,0,0,0.2);
        }

        .mobile-nav-item.active {
            color: var(--accent-primary);
            background: rgba(99, 102, 241, 0.1);
            border-color: rgba(99, 102, 241, 0.2);
        }

        /* Media Query: Khi màn hình nhỏ hơn 768px */
        @media (max-width: 768px) {
            .staff-nav {
                display: none; /* Ẩn menu ngang */
            }
            .mobile-menu-btn {
                display: block; /* Hiện nút Hamburger */
            }
            .staff-container {
                padding: 1rem;
            }
            .staff-header {
                padding: 0 1rem;
            }
        }
    </style>
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="staff-body" x-data="{ isMobileMenuOpen: false }">
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
    <div class="ambient-glow"></div>

    <header class="staff-header">
        <div class="flex items-center gap-6">
            
            <button class="mobile-menu-btn" @click="isMobileMenuOpen = !isMobileMenuOpen">
                <i class="ph" :class="isMobileMenuOpen ? 'ph-x' : 'ph-list'"></i>
            </button>

            <div class="brand flex items-center gap-2 mb-0">
                <i class="ph-fill ph-car-profile text-[1.8rem] text-[var(--accent-primary)]"></i>
                <h2 class="m-0 text-[1.4rem]">ParkGrid</h2>
            </div>
            
            <nav class="staff-nav ml-6">
                <a href="{{ route('staff.dashboard') }}" class="staff-nav-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                    <i class="ph ph-qr-code"></i> Check In / Out
                </a>
                <a href="{{ route('staff.operations.search') }}" class="staff-nav-item {{ request()->routeIs('staff.operations.search*') ? 'active' : '' }}">
                    <i class="ph ph-magnifying-glass"></i> Search
                </a>
                <a href="{{ route('staff.operations.register-pass') }}" class="staff-nav-item {{ request()->routeIs('staff.operations.register-pass*') ? 'active' : '' }}">
                    <i class="ph ph-identification-card"></i> Register Pass
                </a>
                <a href="{{ route('staff.history.index') }}" class="staff-nav-item {{ request()->routeIs('staff.history.*') ? 'active' : '' }}">
                    <i class="ph ph-clock-counter-clockwise"></i> History
                </a>
            </nav>
        </div>

        <div class="flex items-center gap-6">
            <label class="theme-switch" title="Toggle Theme">
                <input type="checkbox" id="themeToggle">
                <span class="slider"></span>
            </label>
            
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="bg-transparent border-none text-[#ef4444] font-semibold cursor-pointer flex items-center gap-1.5">
                    <i class="ph ph-sign-out"></i> <span class="hide-mobile">End Shift</span>
                </button>
            </form>
        </div>

    </header>

    <div x-show="isMobileMenuOpen"
         x-transition.opacity.duration.300ms
         style="display: none;"
         class="fixed top-[70px] left-0 w-full h-[calc(100vh-70px)] bg-black/40 backdrop-blur-sm z-40"
         @click="isMobileMenuOpen = false">
    </div>

    <div class="mobile-menu-overlay"
         x-show="isMobileMenuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-5"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-5"
         style="display: none;"
         class="mobile-menu-overlay fixed top-[70px] left-0 w-full z-[41]">
        
        <a href="{{ route('staff.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
            <i class="ph-fill ph-qr-code"></i> Check In / Out
        </a>
        <a href="{{ route('staff.operations.search') }}" class="mobile-nav-item {{ request()->routeIs('staff.operations.search*') ? 'active' : '' }}">
            <i class="ph-fill ph-magnifying-glass"></i> Search
        </a>
        <a href="{{ route('staff.operations.register-pass') }}" class="mobile-nav-item {{ request()->routeIs('staff.operations.register-pass*') ? 'active' : '' }}">
            <i class="ph-fill ph-identification-card"></i> Register Pass
        </a>
        <a href="{{ route('staff.history.index') }}" class="mobile-nav-item {{ request()->routeIs('staff.history.*') ? 'active' : '' }}">
            <i class="ph-fill ph-clock-counter-clockwise"></i> History
        </a>
    </div>

    <main class="staff-container">
        @yield('content')
    </main>

    @stack('scripts')
    <script src="{{ asset('assets/js/theme-switcher.js') }}"></script>

    @include('components.toast')
</body>
</html>