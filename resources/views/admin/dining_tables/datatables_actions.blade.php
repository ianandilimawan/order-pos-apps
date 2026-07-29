@if(auth()->user() && auth()->user()->hasPermission('view-dining_tables'))
<a href="{{ route('admin.dining_tables.show', $id) }}"
    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">View</a>
@endif
@if(auth()->user() && auth()->user()->hasPermission('edit-dining_tables'))
<a href="{{ route('admin.dining_tables.edit', $id) }}"
    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">Edit</a>
@endif
@if(auth()->user() && auth()->user()->hasPermission('delete-dining_tables'))
<button @click="$dispatch('open-delete-modal', { action: '{{ route('admin.dining_tables.destroy', $id) }}' })"
    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
@endif
