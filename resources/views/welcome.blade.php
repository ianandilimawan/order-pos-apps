<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Cafe Order POS') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #1a1a1a;
            --secondary: #4a5568;
            --accent: #3b82f6;
            --accent-light: #60a5fa;
            --bg: #ffffff;
            --gradient-1: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Animated Background Gradient */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(59, 130, 246, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 70% 80%, rgba(139, 92, 246, 0.08) 0%, transparent 50%);
            animation: rotate 30s linear infinite;
            z-index: -1;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Header */
        .header {
            border-bottom: 1px solid rgba(229, 231, 235, 0.5);
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 800;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
        }

        .btn-login {
            padding: 10px 24px;
            background: white;
            color: var(--primary);
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background: #f9fafb;
            transform: translateY(-1px);
        }

        /* Hero Section */
        .hero {
            max-width: 1200px;
            margin: 0 auto;
            padding: 100px 24px;
            text-align: center;
            position: relative;
        }

        .hero h1 {
            font-size: 64px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 24px;
            color: var(--primary);
            letter-spacing: -2px;
            animation: fadeInUp 0.6s ease-out;
        }

        .hero h1 .gradient-text {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 20px;
            color: var(--secondary);
            max-width: 600px;
            margin: 0 auto 40px;
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }

        .cta-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            animation: fadeInUp 0.6s ease-out 0.3s both;
        }

        .btn-primary {
            padding: 16px 32px;
            background: var(--primary);
            color: white;
            border-radius: 12px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .btn-secondary {
            padding: 16px 32px;
            background: white;
            color: var(--primary);
            border-radius: 12px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            border: 1px solid #e5e7eb;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary:hover {
            background: #f9fafb;
            transform: translateY(-2px);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .mockup-container {
            margin-top: 80px;
            animation: fadeInUp 0.8s ease-out 0.5s both;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <a href="/" class="logo">{{ config('app.name', 'Cafe Order') }}</a>
            <div class="nav-links">
                @if (Route::has('admin.login'))
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="btn-login">Go to Dashboard</a>
                    @else
                        <a href="{{ route('admin.login') }}" class="btn-login">Staff Login</a>
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1>
            Order Your Coffee<br>
            <span class="gradient-text">Without the Wait.</span>
        </h1>
        <p>Experience our self-service QR menu. Order directly from your table, customize your drinks, and get notified when it's ready.</p>
        
        <div class="cta-buttons">
            <a href="{{ route('customer.menu') }}" class="btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Order Now (QR Menu)
            </a>
            <a href="{{ route('admin.login') }}" class="btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                POS Kasir
            </a>
        </div>
    </section>

</body>
</html>
