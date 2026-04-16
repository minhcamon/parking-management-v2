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
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .hero {
            max-width: 800px;
            padding: 2rem;
            z-index: 10;
        }
        .hero h1 {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero p {
            font-size: 1.25rem;
            color: var(--text-muted);
            margin-bottom: 3rem;
            line-height: 1.6;
        }
        .action-btns {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
        }
        .btn-outline {
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            border: 2px solid var(--accent-primary);
            background: transparent;
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all var(--transition-speed);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-outline:hover {
            background: rgba(99, 102, 241, 0.1);
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
    <div class="ambient-glow"></div>

    <div class="hero">
        <i class="ph-fill ph-car-profile" style="font-size: 4rem; color: var(--accent-primary); margin-bottom: 1rem;"></i>
        <h1>ParkGrid</h1>
        <p>The ultimate modern parking management system. Seamlessly register vehicles, process transactions, and manage your facilities in real time.</p>
        
        <div class="action-btns">
            <a href="{{ route('login') }}" class="btn-gradient">
                <i class="ph ph-sign-in"></i> Get Started
            </a>
            <a href="#features" class="btn-outline">
                <i class="ph ph-info"></i> Learn More
            </a>
        </div>
    </div>
</body>
</html>
