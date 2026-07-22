@if(auth()->user() && auth()->user()->hasPermission('view-cash_opnames'))
<a href="{{ route('admin.cash_opnames.show', $id) }}"
    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">View</a>
@endif
@if(auth()->user() && auth()->user()->hasPermission('edit-cash_opname'))
<a href="{{ route('admin.cash_opnames.edit', $id) }}"
    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">Edit</a>
@endif
@if(auth()->user() && auth()->user()->hasPermission('delete-cash_opname'))
<button @click="$dispatch('open-delete-modal', { action: '{{ route('admin.cash_opnames.destroy', $id) }}' })"
    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
@endif
