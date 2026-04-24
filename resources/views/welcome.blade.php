<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkGrid - Modern Parking Management</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col justify-center items-center text-center min-h-screen">
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
    <div class="ambient-glow"></div>

    <div class="hero max-w-[800px] p-8 z-10">
        <i class="ph-fill ph-car-profile text-6xl text-accent mb-4"></i>
        <h1 class="text-6xl mb-6 bg-gradient-to-br from-accent to-accent-secondary bg-clip-text text-transparent">ParkGrid</h1>
        <p class="text-xl text-muted mb-12 leading-[1.6]">Hệ thống quản lý bãi xe hiện đại và tối ưu nhất. Đăng ký phương tiện, xử lý giao dịch và quản lý cơ sở hạ tầng theo thời gian thực một cách liền mạch.</p>
        
        <div class="flex gap-6 justify-center">
            <a href="{{ route('login') }}" class="btn-gradient">
                <i class="ph ph-sign-in"></i> Bắt đầu ngay
            </a>
            <a href="#features" class="px-6 py-[0.8rem] rounded-xl border-2 border-accent bg-transparent text-main font-heading font-semibold text-base cursor-pointer transition-all duration-300 inline-flex items-center gap-2 no-underline hover:bg-[#6366f1]/10 hover:shadow-[0_10px_20px_-5px_rgba(99,102,241,0.4)] hover:scale-[1.02]">
                <i class="ph ph-info"></i> Tìm hiểu thêm
            </a>
        </div>
    </div>
</body>
</html>
