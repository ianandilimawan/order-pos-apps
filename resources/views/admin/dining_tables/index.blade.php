@extends('admin.layouts.app')

@section('title', 'Dining Tables')

@section('content')
    <div class="space-y-6">

<!-- Page Header -->
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div>
                <h2 class="lg:text-2xl text-xl font-bold text-gray-900 dark:text-white">Dining Tables</h2>
                <p class="mt-1 lg:text-sm text-xs text-gray-600 dark:text-gray-400">Manage your diningtables here</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @if(auth()->user() && auth()->user()->hasPermission('create-dining_table'))
                <a href="{{ route('admin.dining_tables.print_all_qr') }}" target="_blank"
                    class="lg:px-4 px-3 lg:py-2 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors lg:text-base text-sm">
                    <svg class="lg:w-5 w-4 lg:h-5 h-4 inline lg:mr-2 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print All QR
                </a>
                <a href="{{ route('admin.dining_tables.create') }}"
                    class="lg:px-4 px-3 lg:py-2 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors lg:text-base text-sm">
                    <svg class="lg:w-5 w-4 lg:h-5 h-4 inline lg:mr-2 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Dining Table
                </a>
                @endif
            </div>
        </div>

        <!-- DataTable -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100/50 dark:border-gray-800 overflow-hidden p-2">
            <livewire:tables.dining-table-table />
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-confirm-delete-modal title="Delete Dining Table"
        message="Are you sure you want to delete this diningtable? This action cannot be undone." />
@endsection
