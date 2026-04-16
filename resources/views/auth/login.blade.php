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
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 3rem 2.5rem;
            text-align: center;
        }
        .login-card .brand {
            justify-content: center;
            margin-bottom: 2rem;
        }
        .login-card h2 {
            margin-bottom: 0.5rem;
        }
        .login-card p {
            color: var(--text-muted);
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
        .form-group {
            text-align: left;
            margin-bottom: 1.5rem;
            position: relative;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-muted);
        }
        .form-control {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.5rem;
            background: rgba(0,0,0,0.02);
            border: 1px solid var(--header-border);
            border-radius: 12px;
            color: var(--text-main);
            outline: none;
            transition: border 0.3s, background 0.3s;
            font-family: inherit;
        }
        :root[data-theme="dark"] .form-control {
            background: rgba(0,0,0,0.2);
            border-color: rgba(255,255,255,0.1);
        }
        .form-control:focus {
            border-color: var(--accent-primary);
        }
        .form-icon {
            position: absolute;
            bottom: 0.9rem;
            left: 1rem;
            color: var(--text-muted);
            font-size: 1.2rem;
        }
        .btn-full {
            width: 100%;
            justify-content: center;
            margin-top: 1rem;
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

    <!-- Theme Toggle Fixed at Top Right -->
    <div style="position: absolute; top: 2rem; right: 2rem;">
        <label class="theme-switch">
            <input type="checkbox" id="themeToggle">
            <span class="slider"></span>
        </label>
    </div>

    <div class="premium-card login-card">
        <div class="brand">
            <i class="ph-fill ph-car-profile"></i>
            <h2>ParkGrid</h2>
        </div>
        <h2>Welcome Back</h2>
        <p>Sign in to access your workspace.</p>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Email Address</label>
                <i class="ph ph-envelope-simple form-icon"></i>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required autofocus>
            </div>

            <div class="form-group">
                <label>Password</label>
                <i class="ph ph-lock-key form-icon"></i>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn-gradient btn-full">
                Sign In <i class="ph ph-arrow-right"></i>
            </button>
        </form>
    </div>

    <script src="{{ asset('assets/js/theme-switcher.js') }}"></script>
</body>
</html>
