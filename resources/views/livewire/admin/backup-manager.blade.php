<div x-data="{ addModalOpen: false }" class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div>
            <h1 class="lg:text-2xl text-xl font-semibold text-gray-900 dark:text-white">Database Backups</h1>
            <p class="mt-1 lg:text-sm text-xs text-gray-600 dark:text-gray-400">
                Manage your database backups. You can create new backups, download them, or restore from a previous backup.
            </p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
            @if(auth()->user()->hasPermissionTo('create-backup'))
                <button @click="addModalOpen = true" class="lg:px-4 px-3 lg:py-2 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors lg:text-base text-sm inline-flex items-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm">
                    <svg class="lg:w-5 w-4 lg:h-5 h-4 inline lg:mr-2 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Backup
                </button>
            @endif
        </div>
    </div>

    <!-- Error messages -->
    @error('uploadFile') 
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 px-4 py-3 rounded-xl text-sm shadow-sm">
            {{ $message }}
        </div> 
    @enderror

    <!-- Backup List -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100/50 dark:border-gray-800 overflow-hidden">
        <ul role="list" class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse($backups as $backup)
                <li class="px-6 py-5 flex items-center justify-between hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $backup['name'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Size: {{ $backup['size'] }} &bull; Created: {{ $backup['date'] }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <button wire:click="downloadBackup('{{ $backup['name'] }}')" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">Download</button>
                        
                        @if(auth()->user()->hasPermissionTo('restore-backup'))
                            <button 
                                x-on:click="confirmRestoreBackup('{{ $backup['name'] }}', $wire)"
                                class="text-sm font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300 transition-colors">
                                Restore
                            </button>
                        @endif

                        @if(auth()->user()->hasPermissionTo('delete-backup'))
                            <button 
                                x-on:click="confirmDeleteBackup('{{ $backup['name'] }}', $wire)"
                                class="text-sm font-medium text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                Delete
                            </button>
                        @endif
                    </div>
                </li>
            @empty
                <li class="px-6 py-16 text-center">
                    <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"></path>
                        </svg>
                    </div>
                    <p class="text-base font-medium text-gray-900 dark:text-white">No backups found</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Get started by creating a new backup to secure your data.</p>
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Modal for New Backup -->
    <div x-show="addModalOpen" x-cloak class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="addModalOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 transition-opacity"></div>
             
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="addModalOpen" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     @click.away="addModalOpen = false"
                     class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100 dark:border-gray-800">
                    <div class="px-6 py-6 border-b border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modal-title">New Backup</h3>
                            <button @click="addModalOpen = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>
                    <div class="px-6 py-6 space-y-8">
                        <!-- Option 1: Create New -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Option 1: Generate Fresh Backup</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Instantly take a full snapshot of your current database.</p>
                            <button wire:click="createBackup" @click="addModalOpen = false" wire:loading.attr="disabled" class="w-full justify-center px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors text-sm font-medium inline-flex items-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-[0_4px_14px_0_rgb(37,99,235,0.39)]">
                                <span wire:loading.remove wire:target="createBackup">Create Database Backup Now</span>
                                <span wire:loading wire:target="createBackup">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>

                        <div class="relative">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-gray-100 dark:border-gray-800"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-white dark:bg-gray-900 px-3 text-xs font-semibold text-gray-400 tracking-wider">OR</span>
                            </div>
                        </div>

                        <!-- Option 2: Upload -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Option 2: Upload Existing Backup</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Upload a .sql file from your local machine to restore later.</p>
                            <form wire:submit="uploadBackupFile" class="flex flex-col gap-3">
                                <input type="file" wire:model="uploadFile" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 dark:file:bg-gray-800 dark:file:text-gray-300 dark:hover:file:bg-gray-700 focus:outline-none" accept=".sql" required>
                                <button type="submit" @click="addModalOpen = false" class="w-full justify-center px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="uploadFile">Upload File</span>
                                    <span wire:loading wire:target="uploadFile">Uploading...</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmRestoreBackup(filename, wire) {
        const isDarkMode = document.documentElement.classList.contains('dark');
        Swal.fire({
            title: '<div class="mx-auto w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mb-3"><svg class="w-5 h-5 text-amber-600 dark:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg></div>Restore Backup?',
            html: 'Are you sure you want to RESTORE this backup? This will OVERWRITE your current database and cannot be undone!',
            showCancelButton: true,
            confirmButtonText: 'Yes, Restore it!',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            background: isDarkMode ? '#18181b' : '#ffffff',
            customClass: {
                popup: 'border border-zinc-200 dark:border-zinc-800 rounded-3xl shadow-xl',
                title: '!text-lg font-bold tracking-tight mt-2 !text-zinc-900 dark:!text-white',
                htmlContainer: 'text-sm !text-zinc-500 dark:!text-zinc-400 mt-2 mb-6',
                actions: 'flex gap-4 w-full justify-center mt-6',
                confirmButton: 'btn px-6 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors',
                cancelButton: 'btn px-6 py-2 bg-zinc-200 dark:bg-zinc-800 hover:bg-zinc-300 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white font-medium rounded-lg transition-colors'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                wire.restoreBackup(filename);
            }
        });
    }

    function confirmDeleteBackup(filename, wire) {
        const isDarkMode = document.documentElement.classList.contains('dark');
        Swal.fire({
            title: '<div class="mx-auto w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-3"><svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></div>Delete Backup?',
            html: 'Are you sure you want to delete this backup file?',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            background: isDarkMode ? '#18181b' : '#ffffff',
            customClass: {
                popup: 'border border-zinc-200 dark:border-zinc-800 rounded-3xl shadow-xl',
                title: '!text-lg font-bold tracking-tight mt-2 !text-zinc-900 dark:!text-white',
                htmlContainer: 'text-sm !text-zinc-500 dark:!text-zinc-400 mt-2 mb-6',
                actions: 'flex gap-4 w-full justify-center mt-6',
                confirmButton: 'btn px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors',
                cancelButton: 'btn px-6 py-2 bg-zinc-200 dark:bg-zinc-800 hover:bg-zinc-300 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white font-medium rounded-lg transition-colors'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                wire.deleteBackup(filename);
            }
        });
    }
</script>
@endpush
