<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1a1a1a">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Order POS">
    <title>Menu - {{ config('app.name', 'Cafe') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        [x-cloak] { display: none !important; }
        html, body, button, a, input, textarea, select {
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #fafaf7;
            color: #1a1a1a;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* ─── Header ─── */
        .menu-header {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }

        /* ─── Category pills ─── */
        .cat-scroll { display: flex; overflow-x: auto; gap: 8px; padding: 10px 16px; }
        .cat-pill {
            padding: 7px 16px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            border: 1.5px solid #e5e5e0;
            background: #fff;
            color: #888;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }
        .cat-pill.active {
            background: #1a1a1a;
            color: #fff;
            border-color: #1a1a1a;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        /* ─── Product card ─── */
        .product-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: box-shadow 0.2s;
        }
        .product-card .img-wrap {
            aspect-ratio: 1;
            background: #f0efe9;
            position: relative;
            overflow: hidden;
        }
        .product-card .img-wrap img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform 0.35s ease;
        }
        @media (hover: hover) {
            .product-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
            .product-card:hover .img-wrap img { transform: scale(1.05); }
        }
        .product-card .info {
            padding: 10px 12px 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* ─── Grid ─── */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            padding: 10px 14px;
        }
        @media (min-width: 640px) {
            .product-grid { grid-template-columns: repeat(3, 1fr); gap: 14px; padding: 14px 20px; }
        }
        @media (min-width: 1024px) {
            .product-grid { grid-template-columns: repeat(4, 1fr); gap: 16px; max-width: 960px; margin: 0 auto; padding: 20px 24px; }
            .cat-scroll { max-width: 960px; margin: 0 auto; }
            .menu-header > div { max-width: 960px; margin: 0 auto; }
            .cart-wrap { max-width: 480px; left: 50% !important; transform: translateX(-50%); }
        }
        @media (min-width: 1280px) {
            .product-grid { grid-template-columns: repeat(5, 1fr); max-width: 1120px; }
            .cat-scroll { max-width: 1120px; }
            .menu-header > div { max-width: 1120px; }
        }

        /* ─── Add button (circle +) ─── */
        .add-btn {
            width: 32px; height: 32px;
            min-width: 32px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: #1a1a1a; color: #fff;
            border: none; cursor: pointer;
            transition: transform 0.15s, background 0.15s;
        }
        .add-btn:active { transform: scale(0.85); background: #333; }

        /* ─── Qty stepper (compact pill) ─── */
        .qty-stepper {
            display: inline-flex; align-items: center;
            border-radius: 100px;
            background: #f5f5f0;
            border: 1.5px solid #e5e5e0;
            overflow: hidden;
            flex-shrink: 0;
        }
        .qty-stepper button {
            width: 26px; height: 26px;
            display: flex; align-items: center; justify-content: center;
            background: none; border: none; cursor: pointer;
            color: #1a1a1a; font-size: 16px;
            transition: background 0.15s;
        }
        .qty-stepper button:active { background: rgba(0,0,0,0.06); }
        .qty-stepper .qty-val {
            min-width: 20px; text-align: center;
            font-size: 12px; font-weight: 700;
        }

        /* ─── Floating cart bar ─── */
        .cart-btn {
            width: 100%;
            background: #1a1a1a;
            color: #fff;
            border-radius: 16px;
            padding: 14px 18px;
            border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: space-between;
            font-family: inherit;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            transition: transform 0.15s;
        }
        .cart-btn:active { transform: scale(0.98); }

        /* ─── Bottom sheet (drawer) ─── */
        .sheet-backdrop {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.45);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: 50;
        }
        .sheet {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: #fff;
            border-radius: 24px 24px 0 0;
            max-height: 92dvh;
            display: flex; flex-direction: column;
            z-index: 51;
            box-shadow: 0 -8px 40px rgba(0,0,0,0.12);
        }
        @media (min-width: 640px) {
            .sheet { max-width: 480px; left: 50%; transform: translateX(-50%); border-radius: 24px 24px 0 0; }
        }
        .sheet-handle {
            width: 40px; height: 4px;
            border-radius: 2px;
            background: #ddd;
            margin: 10px auto 0;
            flex-shrink: 0;
        }

        /* ─── Toggle (Dine in / Take Away) ─── */
        .type-toggle {
            display: flex; gap: 4px;
            background: #f3f3ee; border-radius: 14px; padding: 4px;
        }
        .type-toggle button {
            flex: 1; padding: 10px 12px;
            border-radius: 10px; font-size: 13px; font-weight: 600;
            border: none; cursor: pointer; background: transparent;
            color: #999; transition: all 0.25s; font-family: inherit;
        }
        .type-toggle button.active {
            background: #fff; color: #1a1a1a;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        /* ─── Form ─── */
        .field-input {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid #e5e5e0; border-radius: 12px;
            font-size: 14px; font-family: inherit;
            background: #fafaf8; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .field-input:focus {
            border-color: #1a1a1a;
            box-shadow: 0 0 0 3px rgba(26,26,26,0.06);
        }
        .field-input::placeholder { color: #bbb; }
        .field-label {
            font-size: 11px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1px; color: #aaa; margin-bottom: 6px; display: block;
        }

        /* ─── CTA ─── */
        .cta-btn {
            width: 100%; padding: 15px;
            border-radius: 16px; font-size: 15px; font-weight: 700;
            background: #1a1a1a; color: #fff;
            border: none; cursor: pointer; font-family: inherit;
            transition: opacity 0.15s, transform 0.15s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        }
        .cta-btn:active { opacity: 0.85; transform: scale(0.98); }

        /* ─── Best seller badge ─── */
        .best-badge {
            position: absolute; top: 8px; left: 8px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white; font-size: 9px; font-weight: 800;
            padding: 3px 7px; border-radius: 6px; z-index: 10;
            display: flex; align-items: center; gap: 3px;
            box-shadow: 0 2px 6px rgba(249,115,22,0.35);
            letter-spacing: 0.3px;
        }

        /* ─── Success animations ─── */
        @keyframes pop { 0%{transform:scale(0);opacity:0} 50%{transform:scale(1.12)} 100%{transform:scale(1);opacity:1} }
        @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
        .anim-pop { animation: pop 0.45s cubic-bezier(0.34,1.56,0.64,1) forwards; }
        .anim-up { animation: fadeUp 0.4s ease forwards; }
        .anim-d1 { animation-delay:.12s; opacity:0; }
        .anim-d2 { animation-delay:.22s; opacity:0; }
        .anim-d3 { animation-delay:.32s; opacity:0; }
        .anim-d4 { animation-delay:.42s; opacity:0; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body class="antialiased pb-24">
    {{ $slot }}
    @livewireScripts

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('notify', (event) => {
                let data = Array.isArray(event) ? event[0] : event;
                Swal.fire({ toast:true, position:'top-end', showConfirmButton:false, timer:3000, timerProgressBar:true, icon:data.type, title:data.message });
            });

            Livewire.on('promo-alert', (event) => {
                let data = Array.isArray(event) ? event[0] : event;
                Swal.fire({
                    icon: data.type,
                    title: data.type === 'success' ? 'Promo Diterapkan!' : 'Promo Gagal',
                    text: data.message,
                    confirmButtonText: 'OK',
                    confirmButtonColor: data.type === 'success' ? '#10b981' : '#ef4444',
                    showClass: { popup: 'animate__animated animate__fadeInUp animate__faster' },
                    hideClass: { popup: 'animate__animated animate__fadeOutDown animate__faster' }
                });
            });
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
</body>
</html>
