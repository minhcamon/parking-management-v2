<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ParkGrid</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex justify-center items-center min-h-screen">
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
    <div class="ambient-glow"></div>

    <!-- Theme Toggle Fixed at Top Right -->
    <div class="absolute top-8 right-8">
        <label class="theme-switch">
            <input type="checkbox" id="themeToggle">
            <span class="slider"></span>
        </label>
    </div>

    <div class="premium-card w-full max-w-[420px] px-10 py-12 text-center">
        <div class="brand flex items-center justify-center gap-2 mb-8">
            <i class="ph-fill ph-car-profile text-[1.8rem] text-[var(--accent-primary)]"></i>
            <h2 class="m-0 text-[1.4rem]">ParkGrid</h2>
        </div>
        <h2 class="mb-2">Welcome Back</h2>
        <p class="text-[var(--text-muted)] mb-8 text-[0.9rem]">Sign in to access your workspace.</p>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <div class="text-left mb-6 relative">
                <label class="block mb-2 text-[0.85rem] font-medium text-[var(--text-muted)]">Email Address</label>
                <i class="ph ph-envelope-simple absolute bottom-[0.9rem] left-4 text-[var(--text-muted)] text-[1.2rem]"></i>
                <input type="email" name="email" class="form-control pl-10 w-full" placeholder="Enter your email" required autofocus>
            </div>

            <div class="text-left mb-6 relative">
                <label class="block mb-2 text-[0.85rem] font-medium text-[var(--text-muted)]">Password</label>
                <i class="ph ph-lock-key absolute bottom-[0.9rem] left-4 text-[var(--text-muted)] text-[1.2rem]"></i>
                <input type="password" name="password" class="form-control pl-10 w-full" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn-gradient w-full justify-center mt-4">
                Sign In <i class="ph ph-arrow-right"></i>
            </button>
        </form>
    </div>

    <script src="{{ asset('assets/js/theme-switcher.js') }}"></script>

    @include('components.toast')
</body>
</html>
