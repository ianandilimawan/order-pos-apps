@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex justify-between items-end animate-fade-in-up">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white tracking-tight">Kasir Dashboard</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Welcome, {{ auth()->user()->name }}! Have a great shift.</p>
            </div>
            <div class="hidden sm:flex space-x-2">
                <a href="{{ route('admin.pos') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm shadow-blue-500/30 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Open POS
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fade-in-up delay-100">
            <!-- Stat Card 1 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 p-6 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-md group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">My Orders Today</p>
                        <p class="mt-2 text-xl font-bold tracking-tight tracking-tight text-zinc-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ number_format($myOrdersToday) }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/40 rounded-xl group-hover:bg-blue-100 dark:group-hover:bg-blue-900/60 transition-colors">
                        <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-100 dark:border-zinc-700 p-6 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-md group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">My Revenue Today</p>
                        <p class="mt-2 text-xl font-bold tracking-tight tracking-tight text-zinc-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">Rp {{ number_format($myRevenueToday, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-green-50 dark:bg-green-900/40 rounded-xl group-hover:bg-green-100 dark:group-hover:bg-green-900/60 transition-colors">
                        <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
