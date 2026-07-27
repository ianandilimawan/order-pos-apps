<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'InPOS') }} - Point of Sale & Self Ordering</title>
    <link rel="icon" href="{{ asset('images/favicon.ico') }}">
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
            

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #0f172a;
            --brand: #0ea5e9;
            /* Sky blue */
            --bg: #ffffff;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--primary);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        /* Glassmorphism Header */
        .glass-nav {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Subtle Grid Background for Hero */
        .grid-bg {
            background-size: 40px 40px;
            background-image:
                linear-gradient(to right, rgba(15, 23, 42, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(15, 23, 42, 0.03) 1px, transparent 1px);
            mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
            -webkit-mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
            position: absolute;
            inset: 0;
            z-index: -1;
        }

        .blob-shape {
            position: absolute;
            filter: blur(100px);
            z-index: -2;
            opacity: 0.15;
            border-radius: 50%;
        }

        /* Mockup Window */
        .mockup-wrapper {
            position: relative;
            border-radius: 12px;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.15), 0 0 0 1px rgba(15, 23, 42, 0.05);
            overflow: hidden;
            background: #fff;
        }

        .mockup-header {
            height: 32px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 12px;
            gap: 6px;
        }

        .mockup-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .feature-image-shadow {
            box-shadow: 0 30px 60px -12px rgba(15, 23, 42, 0.12), 0 18px 36px -18px rgba(15, 23, 42, 0.15);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(15, 23, 42, 0.05);
        }

        /* Hide elements before motion applies */
        .motion-hide {
            opacity: 0;
        }
    </style>
</head>

<body class="antialiased relative dark:bg-slate-950 dark:text-slate-200 transition-colors duration-300">

    <!-- Header -->
    <header
        class="fixed w-full top-0 z-50 glass-nav bg-white/85 border-b border-black/5 dark:bg-slate-900/85 dark:border-slate-800 transition-all duration-300 motion-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center min-h-[100px] py-2">
                <div class="flex-shrink-0 flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 md:w-[100px] md:h-[100px] object-contain object-left -ml-5">
                </div>

                <nav class="hidden md:flex gap-8">
                    <a href="#features"
                        class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">{{ __('Features') }}</a>
                    <a href="#how-it-works"
                        class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">{{ __('How it works') }}</a>
                    <a href="#customization"
                        class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">{{ __('Customization') }}</a>
                    <a href="#contact"
                        class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">{{ __('Contact') }}</a>
                </nav>

                <div class="flex items-center gap-2">
                    <!-- Language Switcher -->
                    <div class="flex bg-slate-100 dark:bg-slate-800 rounded-lg p-1">
                        <a href="{{ route('lang.switch', 'id') }}"
                            class="px-2 py-1 text-xs font-bold rounded-md transition-colors {{ app()->getLocale() == 'id' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300' }}">
                            ID
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}"
                            class="px-2 py-1 text-xs font-bold rounded-md transition-colors {{ app()->getLocale() == 'en' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300' }}">
                            EN
                        </a>
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button id="theme-toggle" type="button"
                        class="theme-toggle text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200 dark:focus:ring-slate-700 rounded-lg text-sm p-2.5">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden dark:bg-slate-950">
        <div class="grid-bg dark:opacity-20"></div>
        <!-- Very subtle ambient glows (Teal and Sky Blue instead of Purple) -->
        <div class="blob-shape bg-sky-400 w-96 h-96 top-0 left-10"></div>
        <div class="blob-shape bg-teal-400 w-[30rem] h-[30rem] top-20 right-0"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium mb-6 motion-title motion-hide">
                    <span class="flex h-2 w-2 relative">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    {{ __('Now live and ready to use') }}
                </div>

                <h1
                    class="font-heading text-5xl md:text-7xl font-bold tracking-tight text-slate-900 dark:text-white mb-6 leading-tight motion-title motion-hide">
                    {!! __('Manage your business') !!} <br>
                    <span class="text-sky-600 dark:text-sky-400">{{ __('without the chaos.') }}</span>
                </h1>

                <p class="mt-4 text-lg md:text-xl text-slate-600 dark:text-slate-400 mb-10 motion-subtitle motion-hide">
                    {{ __('A sleek system combining a powerful POS dashboard for your staff and a frictionless QR Self-Ordering menu for your customers.') }}
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center motion-buttons motion-hide">
                    <a href="{{ route('admin.login') }}"
                        class="w-full sm:w-auto font-medium text-base px-8 py-4 bg-slate-900 dark:bg-sky-500 text-white rounded-lg hover:bg-slate-800 dark:hover:bg-sky-600 transition-all shadow-lg flex items-center justify-center gap-2 hover:-translate-y-0.5">
                        {{ __('Try Admin POS') }}
                    </a>
                    <a href="{{ route('customer.menu') }}"
                        class="w-full sm:w-auto font-medium text-base px-8 py-4 bg-white dark:bg-slate-800 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm flex items-center justify-center gap-2 hover:-translate-y-0.5">
                        {{ __('Try Customer Menu') }}
                    </a>
                </div>
                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400 motion-buttons motion-hide">
                    {{ __('No credit card required. Explore the live demo immediately.') }}</p>
            </div>
        </div>

        <!-- Hero Images -->
        <div class="mt-16 md:mt-24 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative motion-images motion-hide">
            <div class="relative w-full max-w-5xl mx-auto">
                <!-- Desktop POS Screenshot -->
                <div class="mockup-wrapper group">
                    <div class="mockup-header">
                        <div class="mockup-dot bg-rose-400"></div>
                        <div class="mockup-dot bg-amber-400"></div>
                        <div class="mockup-dot bg-emerald-400"></div>
                        <div class="ml-4 text-[10px] text-slate-400 font-medium">admin.yourcafe.com</div>
                    </div>
                    <!-- Transition effect to smoothly scale up on hover -->
                    <div class="overflow-hidden">
                        <img src="{{ asset('images/mockup-pos.png') }}" alt="POS Dashboard Light"
                            class="w-full h-auto object-cover object-top border-b border-slate-200 transition-transform duration-700 ease-out group-hover:scale-[1.02] dark:hidden"
                            loading="lazy">
                        <img src="{{ asset('images/mockup-pos-dark.png') }}" alt="POS Dashboard Dark"
                            class="hidden w-full h-auto object-cover object-top border-b border-slate-800 transition-transform duration-700 ease-out group-hover:scale-[1.02] dark:block"
                            loading="lazy">
                    </div>
                </div>

                <!-- Mobile QR Menu Screenshot (Overlapping) -->
                <div class="absolute -right-4 md:-right-8 -bottom-10 md:-bottom-16 w-32 md:w-56 shadow-2xl z-20 group">
                    <div
                        class="overflow-hidden rounded-[2rem] border-[6px] border-slate-900 bg-slate-900 transition-transform duration-700 ease-out group-hover:-translate-y-2 group-hover:rotate-2">
                        <img src="{{ asset('images/mockup-menu.png') }}" alt="Customer QR Menu" class="w-full h-auto"
                            loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Logo Cloud -->
    <div class="py-12 border-y border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p
                class="text-center text-sm font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-8">
                {{ __('Empowering independent F&B businesses, restaurants, and retail') }}</p>
            <div
                class="flex justify-center gap-10 md:gap-20 opacity-30 dark:opacity-40 grayscale flex-wrap items-center">
                <span class="font-heading text-xl font-bold tracking-tight text-slate-900 dark:text-white">Kopi
                    Kenangan</span>
                <span class="font-heading text-xl font-bold tracking-tight text-slate-900 dark:text-white">Janji
                    Jiwa</span>
                <span class="font-heading text-xl font-bold tracking-tight text-slate-900 dark:text-white">Fore
                    Coffee</span>
                <span
                    class="font-heading text-xl font-bold tracking-tight text-slate-900 dark:text-white">Excelso</span>
            </div>
        </div>
    </div>

    <!-- Deep Dive Features (Alternating Layout) -->
    <section id="features" class="py-24 bg-white dark:bg-slate-900 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-20 motion-section motion-hide">
                <h2 class="font-heading text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                    {{ __('Enterprise Features, Accessible Prices.') }}</h2>
                <p class="text-slate-500 dark:text-slate-400 text-lg max-w-2xl mx-auto">
                    {{ __('Everything you need to run your daily operations smoothly, neatly packed into one elegant interface.') }}
                </p>
            </div>

            <!-- Feature 1: POS -->
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20 mb-32 motion-section motion-hide">
                <div class="lg:w-1/2">
                    <div
                        class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h2 class="font-heading text-3xl font-bold text-slate-900 dark:text-white mb-4">
                        {{ __('Streamlined Point of Sale') }}</h2>
                    <p class="text-lg text-slate-600 dark:text-slate-400 mb-6">
                        {{ __('Your cashiers need speed. We designed the POS interface to minimize clicks. Add items, apply promo codes, and process payments in seconds.') }}
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300 font-medium">
                            <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('Real-time cart calculation') }}
                        </li>
                        <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300 font-medium">
                            <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('Support for Dine-in and Takeaway') }}
                        </li>
                        <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300 font-medium">
                            <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('Instant receipt generation') }}
                        </li>
                    </ul>
                </div>
                <div class="lg:w-1/2 relative w-full">
                    <div
                        class="absolute inset-0 bg-slate-100 dark:bg-slate-800 rounded-2xl transform translate-x-4 translate-y-4">
                    </div>
                    <img src="{{ asset('images/mockup-pos-list.png') }}" alt="POS Interface Light"
                        class="relative z-10 w-full h-auto feature-image-shadow transition-transform duration-500 hover:-translate-y-1 rounded-lg dark:hidden">
                    <img src="{{ asset('images/mockup-pos-list-dark.png') }}" alt="POS Interface Dark"
                        class="hidden relative z-10 w-full h-auto feature-image-shadow transition-transform duration-500 hover:-translate-y-1 rounded-lg dark:block">
                </div>
            </div>

            <!-- Feature 2: Order Management -->
            <div
                class="flex flex-col lg:flex-row-reverse items-center gap-12 lg:gap-20 mb-32 motion-section motion-hide">
                <div class="lg:w-1/2">
                    <div
                        class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:teal-400 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="font-heading text-3xl font-bold text-slate-900 dark:text-white mb-4">
                        {{ __('Never lose track of an order') }}</h2>
                    <p class="text-lg text-slate-600 dark:text-slate-400 mb-6">
                        {{ __('Whether the order comes from the cashier or directly from a customer\'s phone via QR, it all syncs perfectly into one organized queue.') }}
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300 font-medium">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('Clear order statuses (Pending, Processing, Completed)') }}
                        </li>
                        <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300 font-medium">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('Payment tracking (Cash, QRIS, Transfer)') }}
                        </li>
                        <li class="flex items-center gap-3 text-slate-700 dark:text-slate-300 font-medium">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('Print kitchen tickets instantly') }}
                        </li>
                    </ul>
                </div>
                <div class="lg:w-1/2 relative w-full">
                    <div
                        class="absolute inset-0 bg-slate-100 dark:bg-slate-800 rounded-2xl transform -translate-x-4 translate-y-4">
                    </div>
                    <img src="{{ asset('images/mockup-orders.png') }}" alt="Order Management Light"
                        class="relative z-10 w-full h-auto feature-image-shadow transition-transform duration-500 hover:-translate-y-1 rounded-lg dark:hidden">
                    <img src="{{ asset('images/mockup-orders-dark.png') }}" alt="Order Management Dark"
                        class="hidden relative z-10 w-full h-auto feature-image-shadow transition-transform duration-500 hover:-translate-y-1 rounded-lg dark:block">
                </div>
            </div>

            <!-- Feature 3: Analytics -->
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20 motion-section motion-hide">
                <div class="lg:w-1/2">
                    <div
                        class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="font-heading text-3xl font-bold text-slate-900 dark:text-white mb-4">
                        {{ __('Understand your business') }}</h2>
                    <p class="text-lg text-slate-600 dark:text-slate-400 mb-6">
                        {{ __('Stop guessing. Get clear, actionable data on what\'s selling, when your busy hours are, and how much revenue you\'re generating directly from the dashboard.') }}
                    </p>
                    <a href="{{ route('admin.login') }}"
                        class="inline-flex font-semibold text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 transition-colors items-center gap-1">
                        {{ __('View Demo Analytics') }} <svg class="w-4 h-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>
                <div class="lg:w-1/2 relative w-full">
                    <div
                        class="absolute inset-0 bg-slate-100 dark:bg-slate-800 rounded-2xl transform translate-x-4 translate-y-4">
                    </div>
                    <img src="{{ asset('images/mockup-reports.png') }}" alt="Sales Analytics Light"
                        class="relative z-10 w-full h-auto feature-image-shadow transition-transform duration-500 hover:-translate-y-1 rounded-lg dark:hidden">
                    <img src="{{ asset('images/mockup-reports-dark.png') }}" alt="Sales Analytics Dark"
                        class="hidden relative z-10 w-full h-auto feature-image-shadow transition-transform duration-500 hover:-translate-y-1 rounded-lg dark:block">
                </div>
            </div>

        </div>
    </section>

    <!-- How It Works (Simple 3 steps) -->
    <section id="how-it-works"
        class="py-24 bg-slate-50 dark:bg-slate-950 border-y border-slate-200 dark:border-slate-800 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16 motion-section motion-hide">
                <h2 class="font-heading text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                    {{ __('How self-ordering works') }}
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-lg max-w-2xl mx-auto">
                    {{ __('Reduce wait times and let customers order at their own pace.') }}</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 relative text-center">
                <!-- Connecting Line -->
                <div
                    class="hidden md:block absolute top-12 left-1/6 right-1/6 h-px bg-slate-300 dark:bg-slate-700 z-0">
                </div>

                <div class="relative z-10 motion-step motion-hide">
                    <div
                        class="w-24 h-24 mx-auto bg-white dark:bg-slate-900 rounded-2xl flex items-center justify-center text-sky-500 dark:text-sky-400 mb-6 shadow-sm border border-slate-200 dark:border-slate-800">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <h3 class="font-heading text-xl font-bold text-slate-900 dark:text-white mb-3">
                        {{ __('1. Scan QR Code') }}</h3>
                    <p class="text-slate-600 dark:text-slate-400">
                        {{ __('Customer sits at the table, scans the code, and instantly sees your digital menu.') }}
                    </p>
                </div>
                <div class="relative z-10 motion-step motion-hide" style="transition-delay: 150ms">
                    <div
                        class="w-24 h-24 mx-auto bg-white dark:bg-slate-900 rounded-2xl flex items-center justify-center text-sky-500 dark:text-sky-400 mb-6 shadow-sm border border-slate-200 dark:border-slate-800">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-heading text-xl font-bold text-slate-900 dark:text-white mb-3">
                        {{ __('2. Order & Pay') }}</h3>
                    <p class="text-slate-600 dark:text-slate-400">
                        {{ __('They add items to the cart and proceed to checkout, generating an invoice number.') }}
                    </p>
                </div>
                <div class="relative z-10 motion-step motion-hide" style="transition-delay: 300ms">
                    <div
                        class="w-24 h-24 mx-auto bg-white dark:bg-slate-900 rounded-2xl flex items-center justify-center text-sky-500 dark:text-sky-400 mb-6 shadow-sm border border-slate-200 dark:border-slate-800">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="font-heading text-xl font-bold text-slate-900 dark:text-white mb-3">
                        {{ __('3. Serve') }}</h3>
                    <p class="text-slate-600 dark:text-slate-400">
                        {{ __('The order pops up on your Admin POS. Prepare the food and serve it to the table.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Multi-Device / Anywhere Access Section -->
    <section class="py-24 bg-white dark:bg-slate-900 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="bg-slate-50 dark:bg-slate-950 rounded-[2.5rem] p-10 md:p-16 border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden motion-section motion-hide">
                <!-- Decorative background elements -->
                <div
                    class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-emerald-500/10 dark:bg-emerald-500/5 blur-3xl">
                </div>
                <div
                    class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 rounded-full bg-sky-500/10 dark:bg-sky-500/5 blur-3xl">
                </div>

                <div class="relative z-10 text-center max-w-3xl mx-auto">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 text-sky-500 dark:text-sky-400 mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="font-heading text-3xl md:text-5xl font-bold text-slate-900 dark:text-white mb-6">
                        {{ __('Run your business from anywhere') }}</h2>
                    <p class="text-slate-600 dark:text-slate-400 text-lg mb-8 leading-relaxed">
                        {{ __('You don\'t need expensive specialized hardware. Our system is completely cloud-based and responsive. Use it on an iPad, a standard PC, or even just your smartphone. As an owner, you can monitor live sales and active orders in real-time from anywhere in the world.') }}
                    </p>

                    <div class="flex flex-wrap justify-center gap-8 mt-10">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-slate-400 dark:text-slate-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span
                                class="font-medium text-slate-700 dark:text-slate-300">{{ __('Desktop / PC') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-slate-400 dark:text-slate-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span
                                class="font-medium text-slate-700 dark:text-slate-300">{{ __('Tablets / iPads') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-slate-400 dark:text-slate-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span
                                class="font-medium text-slate-700 dark:text-slate-300">{{ __('Smartphones') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Customization Module -->
    <section id="customization" class="py-24 bg-slate-900 dark:bg-slate-950 text-white relative overflow-hidden">
        <div class="absolute inset-0 grid-bg opacity-10"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 motion-section motion-hide">
            <div
                class="lg:flex items-center gap-16 bg-slate-800 dark:bg-slate-900 rounded-3xl p-10 md:p-16 border border-slate-700 dark:border-slate-800 shadow-2xl">
                <div class="lg:w-2/3 mb-10 lg:mb-0">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-700 border border-slate-600 text-slate-300 text-sm font-medium mb-6">
                        {{ __('Custom Development') }}
                    </div>
                    <h2 class="font-heading text-3xl md:text-5xl font-bold text-white mb-6">
                        {{ __('Need something specific for your business?') }}</h2>
                    <p class="text-slate-400 text-lg leading-relaxed mb-8">
                        {{ __('Every F&B business operates differently. Whether you need deep integration with your existing accounting software, specific multi-outlet inventory management, or unique loyalty programs, our engineering team can build it.') }}
                        <br><br>
                        {{ __('This isn\'t a rigid SaaS box. We customize the architecture to fit your exact operational needs perfectly.') }}
                    </p>
                    <a href="#contact"
                        class="inline-flex items-center gap-2 font-semibold text-slate-900 bg-white px-6 py-3 rounded-lg hover:bg-slate-100 transition-colors">
                        {{ __('Talk to our engineers') }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
                <div class="lg:w-1/3">
                    <div class="grid grid-cols-2 gap-4">
                        <div
                            class="bg-slate-900 dark:bg-slate-950 p-6 rounded-2xl border border-slate-700 dark:border-slate-800 flex flex-col items-center justify-center text-center">
                            <svg class="w-8 h-8 text-sky-400 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                            <span class="font-medium text-slate-300">{{ __('Custom API Integration') }}</span>
                        </div>
                        <div
                            class="bg-slate-900 dark:bg-slate-950 p-6 rounded-2xl border border-slate-700 dark:border-slate-800 flex flex-col items-center justify-center text-center">
                            <svg class="w-8 h-8 text-emerald-400 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span class="font-medium text-slate-300">{{ __('Custom Reporting') }}</span>
                        </div>
                        <div
                            class="bg-slate-900 dark:bg-slate-950 p-6 rounded-2xl border border-slate-700 dark:border-slate-800 flex flex-col items-center justify-center text-center">
                            <svg class="w-8 h-8 text-amber-400 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            <span class="font-medium text-slate-300">{{ __('Multi-Outlet Sync') }}</span>
                        </div>
                        <div
                            class="bg-slate-900 dark:bg-slate-950 p-6 rounded-2xl border border-slate-700 dark:border-slate-800 flex flex-col items-center justify-center text-center">
                            <svg class="w-8 h-8 text-purple-400 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="font-medium text-slate-300">{{ __('Add New Features') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial -->
    <section class="py-24 bg-white dark:bg-slate-950">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center motion-section motion-hide">
            <svg class="w-10 h-10 mx-auto text-slate-200 dark:text-slate-800 mb-8" fill="currentColor"
                viewBox="0 0 24 24">
                <path
                    d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
            </svg>
            <p
                class="text-2xl md:text-4xl font-heading font-medium text-slate-900 dark:text-white leading-tight mb-10">
                "{{ __('We were losing orders during peak hours because the line was too long. This QR system solved our bottleneck on day one.') }}"
            </p>
            <div class="flex items-center justify-center gap-4">
                <div
                    class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-bold text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                    AI</div>
                <div class="text-left">
                    <div class="font-bold text-slate-900 dark:text-white">Andy Ian</div>
                    <div class="text-slate-500 dark:text-slate-400 text-sm">{{ __('Owner, InPOS Early Adopter') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="contact"
        class="py-24 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div
                class="bg-sky-600 dark:bg-sky-800 rounded-[3rem] p-12 md:p-20 text-center text-white relative overflow-hidden shadow-2xl motion-section motion-hide">
                <!-- Abstract BG patterns -->
                <div class="absolute inset-0 opacity-10"
                    style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;">
                </div>
                <div class="absolute top-0 right-0 -mr-32 -mt-32 w-96 h-96 rounded-full bg-white opacity-10 blur-3xl">
                </div>
                <div
                    class="absolute bottom-0 left-0 -ml-32 -mb-32 w-96 h-96 rounded-full bg-emerald-400 opacity-20 blur-3xl">
                </div>

                <div class="relative z-10 max-w-3xl mx-auto">
                    <h2 class="font-heading text-4xl md:text-5xl font-bold mb-6">
                        {{ __('Stop juggling apps. Start streamlining.') }}</h2>
                    <p class="text-sky-100 text-lg md:text-xl mb-10 leading-relaxed">
                        {{ __('Join the modern standard for F&B operations. Try the demo now or contact us for a custom deployment tailored exactly to your workflow.') }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="{{ route('admin.login') }}"
                            class="w-full sm:w-auto font-medium text-base px-8 py-4 bg-white text-sky-900 rounded-lg hover:bg-slate-50 transition-all shadow-lg flex items-center justify-center gap-2 hover:-translate-y-0.5">
                            {{ __('Try Admin Dashboard') }}
                        </a>
                        <a href="{{ route('customer.menu') }}"
                            class="w-full sm:w-auto font-medium text-base px-8 py-4 bg-sky-700 dark:bg-sky-900 text-white border border-sky-500 dark:border-sky-700 rounded-lg hover:bg-sky-800 transition-all shadow-sm flex items-center justify-center gap-2 hover:-translate-y-0.5">
                            {{ __('Try QR Menu') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-50 dark:bg-slate-950 pt-20 pb-10 border-t border-slate-200 dark:border-slate-800 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 mb-16">
                <div class="col-span-2 lg:col-span-2">
                    <div class="flex items-center gap-2 mb-6">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 md:w-[100px] md:h-[100px] object-contain object-left -ml-5">
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 mb-6 max-w-sm">
                        {{ __('A modern, lightning-fast POS and Self-Ordering system designed for F&B businesses that refuse to compromise on design and speed.') }}
                    </p>
                </div>

                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-4">{{ __('Product') }}</h4>
                    <ul class="space-y-3">
                        <li><a href="#"
                                class="text-slate-500 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">{{ __('Admin POS') }}</a>
                        </li>
                        <li><a href="#"
                                class="text-slate-500 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">{{ __('Customer QR Menu') }}</a>
                        </li>
                        <li><a href="#"
                                class="text-slate-500 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">{{ __('Reporting & Analytics') }}</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-4">{{ __('Company') }}</h4>
                    <ul class="space-y-3">
                        <li><a href="#"
                                class="text-slate-500 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">{{ __('About Us') }}</a>
                        </li>
                        <li><a href="#"
                                class="text-slate-500 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">{{ __('Contact Engineering') }}</a>
                        </li>
                        <li><a href="#"
                                class="text-slate-500 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">{{ __('Careers') }}</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-4">{{ __('Legal') }}</h4>
                    <ul class="space-y-3">
                        <li><a href="#"
                                class="text-slate-500 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">{{ __('Privacy Policy') }}</a>
                        </li>
                        <li><a href="#"
                                class="text-slate-500 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">{{ __('Terms of Service') }}</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div
                class="border-t border-slate-200 dark:border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-slate-500 dark:text-slate-400">
                <p>&copy; {{ date('Y') }} InPOS. Powered by InTech Studio.</p>
            </div>
        </div>
    </footer>

    <!-- Framer Motion (Motion One) Script -->
    <script type="module">
        import {
            animate,
            stagger,
            inView
        } from "https://cdn.jsdelivr.net/npm/motion@11.11.13/+esm";

        // Initial Hero Animations
        animate(".motion-nav", {
            y: [-100, 0],
            opacity: [0, 1]
        }, {
            duration: 0.8,
            easing: "ease-out"
        });
        animate(".motion-title", {
            y: [30, 0],
            opacity: [0, 1]
        }, {
            duration: 0.8,
            delay: stagger(0.1),
            easing: "ease-out"
        });
        animate(".motion-subtitle", {
            y: [30, 0],
            opacity: [0, 1]
        }, {
            duration: 0.8,
            delay: 0.3,
            easing: "ease-out"
        });
        animate(".motion-buttons", {
            y: [30, 0],
            opacity: [0, 1]
        }, {
            duration: 0.8,
            delay: 0.5,
            easing: "ease-out"
        });
        animate(".motion-images", {
            y: [80, 0],
            opacity: [0, 1]
        }, {
            duration: 1,
            delay: 0.7,
            easing: "ease-out"
        });

        // Scroll Animations
        inView(".motion-section", (info) => {
            animate(info.target, {
                y: [50, 0],
                opacity: [0, 1]
            }, {
                duration: 0.8,
                easing: "ease-out"
            });
        });

        inView(".motion-step", (info) => {
            animate(info.target, {
                y: [30, 0],
                opacity: [0, 1]
            }, {
                duration: 0.6,
                easing: "ease-out"
            });
        });
    </script>
    <!-- Theme Toggle JS Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtns = document.querySelectorAll('.theme-toggle');
            const htmlClassList = document.documentElement.classList;
            const darkIcon = document.getElementById('theme-toggle-dark-icon');
            const lightIcon = document.getElementById('theme-toggle-light-icon');

            // Initial icon state
            if (htmlClassList.contains('dark')) {
                lightIcon?.classList.remove('hidden');
            } else {
                darkIcon?.classList.remove('hidden');
            }

            // Toggle logic
            toggleBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    darkIcon?.classList.toggle('hidden');
                    lightIcon?.classList.toggle('hidden');
                    
                    if (htmlClassList.contains('dark')) {
                        htmlClassList.remove('dark');
                        localStorage.theme = 'light';
                    } else {
                        htmlClassList.add('dark');
                        localStorage.theme = 'dark';
                    }
                });
            });
        });
    </script>
</body>

</html>
