@extends('admin.layouts.app')

@section('title', 'Dining Table Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header & Breadcrumbs -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dining_tables.index') }}" class="p-2 bg-white dark:bg-gray-800 rounded-full shadow-sm border border-gray-100 dark:border-gray-700 text-gray-500 hover:text-indigo-600 hover:bg-gray-50 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <nav class="flex text-sm text-gray-500 font-medium mb-1" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.dining_tables.index') }}" class="hover:text-indigo-600 transition-colors">Dining Tables</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                <span class="text-gray-400">Details</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="lg:text-2xl text-xl font-semibold text-gray-900 dark:text-white">Dining Table Details</h1>
            </div>
        </div>
        
        <a href="{{ route('admin.dining_tables.edit', $diningTable) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors text-sm font-semibold shadow-sm hover:shadow-md">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit Dining Table
        </a>
    </div>

    <!-- Content Card -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100/50 dark:border-gray-800 overflow-hidden">
        <div class="lg:p-8 px-4 py-4">
            <h3 class="lg:text-lg text-base font-semibold text-gray-900 dark:text-white mb-6">Information</h3>
            
            <div class="border border-gray-100 dark:border-gray-800 rounded-xl overflow-hidden divide-y divide-gray-100 dark:divide-gray-800">
                <dl class="mb-0">
                    <div class="lg:px-6 px-4 lg:py-4 py-3 flex flex-col lg:flex-row lg:items-center hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <dt class="lg:w-1/3 text-sm font-medium text-gray-500 dark:text-gray-400">
                            Number
                        </dt>
                        <dd class="mt-1 lg:mt-0 lg:w-2/3 text-sm text-gray-900 dark:text-white font-medium">
                        {{ $diningTable->number ?? 'N/A' }}
                        </dd>
                    </div>
                    <div class="lg:px-6 px-4 lg:py-4 py-3 flex flex-col lg:flex-row lg:items-center hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <dt class="lg:w-1/3 text-sm font-medium text-gray-500 dark:text-gray-400">
                            Capacity
                        </dt>
                        <dd class="mt-1 lg:mt-0 lg:w-2/3 text-sm text-gray-900 dark:text-white font-medium">
                        {{ $diningTable->capacity ?? 'N/A' }}
                        </dd>
                    </div>
                    <div class="lg:px-6 px-4 lg:py-4 py-3 flex flex-col lg:flex-row lg:items-center hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <dt class="lg:w-1/3 text-sm font-medium text-gray-500 dark:text-gray-400">
                            Status
                        </dt>
                        <dd class="mt-1 lg:mt-0 lg:w-2/3 text-sm text-gray-900 dark:text-white font-medium">
                        @if(strtolower($diningTable->status) === 'available')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">{{ ucfirst($diningTable->status) }}</span>
                        @elseif(strtolower($diningTable->status) === 'occupied')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">{{ ucfirst($diningTable->status) }}</span>
                        @elseif(strtolower($diningTable->status) === 'reserved')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">{{ ucfirst($diningTable->status) }}</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200">{{ ucfirst($diningTable->status ?? 'N/A') }}</span>
                        @endif
                        </dd>
                    </div>
                    <div class="lg:px-6 px-4 lg:py-4 py-3 flex flex-col lg:flex-row lg:items-center hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <dt class="lg:w-1/3 text-sm font-medium text-gray-500 dark:text-gray-400">
                            Qr code
                        </dt>
                        <dd class="mt-1 lg:mt-0 lg:w-2/3 text-sm text-gray-900 dark:text-white font-medium">
                        @if($diningTable->qr_code)
                            <div class="bg-white p-2 rounded-lg border border-gray-200 inline-block">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate($diningTable->qr_code) !!}
                            </div>
                        @else
                            <span class="text-gray-400 italic">N/A</span>
                        @endif
                        </dd>
                    </div>
                    <div class="lg:px-6 px-4 lg:py-4 py-3 flex flex-col lg:flex-row lg:items-center hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <dt class="lg:w-1/3 text-sm font-medium text-gray-500 dark:text-gray-400">
                            Show
                        </dt>
                        <dd class="mt-1 lg:mt-0 lg:w-2/3 text-sm text-gray-900 dark:text-white font-medium">
                        @if($diningTable->show)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">True</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">False</span>
                        @endif
                        </dd>
                    </div>
                    <div class="lg:px-6 px-4 lg:py-4 py-3 flex flex-col lg:flex-row lg:items-center hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <dt class="lg:w-1/3 text-sm font-medium text-gray-500 dark:text-gray-400">Created At</dt>
                        <dd class="mt-1 lg:mt-0 lg:w-2/3 text-sm text-gray-900 dark:text-white font-medium">
                            {{ $diningTable->created_at->format('M d, Y H:i') }}
                        </dd>
                    </div>

                    <div class="lg:px-6 px-4 lg:py-4 py-3 flex flex-col lg:flex-row lg:items-center hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <dt class="lg:w-1/3 text-sm font-medium text-gray-500 dark:text-gray-400">Updated At</dt>
                        <dd class="mt-1 lg:mt-0 lg:w-2/3 text-sm text-gray-900 dark:text-white font-medium">
                            {{ $diningTable->updated_at->format('M d, Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
