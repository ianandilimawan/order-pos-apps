@if(auth()->user() && auth()->user()->hasPermission('view-orders'))
<a href="{{ route('admin.orders.show', $id) }}"
    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">View</a>
@endif
@if(auth()->user() && auth()->user()->hasPermission('edit-orders'))
<a href="{{ route('admin.orders.edit', $id) }}"
    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">Edit</a>
@endif
@if(auth()->user() && auth()->user()->hasPermission('delete-orders'))
<button @click="$dispatch('open-delete-modal', { action: '{{ route('admin.orders.destroy', $id) }}' })"
    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
@endif
