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

    <x-card class="w-full max-w-[420px] px-10 py-12 text-center">
        <div class="brand flex items-center justify-center gap-2 mb-8">
            <i class="ph-fill ph-car-profile text-3xl text-accent"></i>
            <h2 class="m-0 text-2xl">ParkGrid</h2>
        </div>
        <h2 class="mb-2">Chào mừng trở lại</h2>
        <p class="text-muted mb-8 text-sm">Đăng nhập để truy cập hệ thống của bạn.</p>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-500 text-sm">
                <ul class="list-none p-0 m-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <div class="text-left mb-6 relative">
                <label class="block mb-2 text-sm font-medium text-muted">Địa chỉ Email</label>
                <i class="ph ph-envelope-simple absolute bottom-[0.9rem] left-4 text-muted text-xl"></i>
                <input type="email" name="email" class="pl-10 w-full" placeholder="Nhập email của bạn" required autofocus>
            </div>

            <div class="text-left mb-6 relative">
                <label class="block mb-2 text-sm font-medium text-muted">Mật khẩu</label>
                <i class="ph ph-lock-key absolute bottom-[0.9rem] left-4 text-muted text-xl"></i>
                <input type="password" name="password" class="pl-10 w-full" placeholder="Nhập mật khẩu" required>
            </div>

            <button type="submit" class="btn-gradient w-full justify-center mt-4">
                Đăng nhập <i class="ph ph-arrow-right"></i>
            </button>
        </form>
    </x-card>

    <script src="{{ asset('assets/js/theme-switcher.js') }}"></script>

    @include('components.toast')
</body>
</html>
