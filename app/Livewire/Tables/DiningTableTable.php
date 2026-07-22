<?php

namespace App\Livewire\Tables;

use App\Models\DiningTable;
use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use Illuminate\Support\Facades\Schema;

class DiningTableTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'diningTable-table';
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    public function setUp(): array
    {
        if (auth()->user() && auth()->user()->hasPermission('delete-dining_table')) {
            $this->showCheckBox();
        }

        return [
            PowerGrid::exportable('export_dining_tables_' . now()->format('Ymd_His'))
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns()
                ->includeViewOnTop('components.admin.bulk-action-button'),
            PowerGrid::footer()
                ->showPerPage(10, [10, 25, 50, 100])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        $query = DiningTable::query();

        if (Schema::hasColumn('dining_tables', 'sort')) {
            $query->orderBy('sort', 'asc');
        }

        return $query;
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('number')
            ->add('capacity')
            ->add('status', function (DiningTable $row) {
                switch ($row->status) {
                    case 'available':
                        return '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Available</span>';
                    case 'occupied':
                        return '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Occupied</span>';
                    case 'maintenance':
                        return '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">Maintenance</span>';
                    default:
                        return '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">Unknown</span>';
                }
            })
            ->add('qr_code', function (DiningTable $row) {
                if ($row->qr_code) {
                    return '<div class="flex items-center justify-center bg-white p-1 rounded-md border border-gray-200 w-12 h-12">' . \SimpleSoftwareIO\QrCode\Facades\QrCode::size(40)->generate($row->qr_code) . '</div>';
                }
                return '<span class="text-gray-400 italic">No QR</span>';
            })
            ->add('show_display', function (DiningTable $row) {
                if ($row->show) {
                    return '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Active</span>';
                }
                return '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Inactive</span>';
            })
            ->add('action', function (DiningTable $row) {
                $actions = '<div class="flex items-center justify-center gap-1">';

                if (auth()->user() && auth()->user()->hasPermission('view-dining_tables')) {
                    $viewUrl = route('admin.dining_tables.show', $row->id);
                    $actions .= '<a href="' . $viewUrl . '" class="p-1.5 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors" title="View"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>';
                }

                if (auth()->user() && auth()->user()->hasPermission('edit-dining_table')) {
                    $editUrl = route('admin.dining_tables.edit', $row->id);
                    $actions .= '<a href="' . $editUrl . '" class="p-1.5 text-blue-600 hover
                    :text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>';
                }

                if (auth()->user() && auth()->user()->hasPermission('delete-dining_table')) {
                    $deleteUrl = route('admin.dining_tables.destroy', $row->id);
                    $actions .= '<button onclick="window.dispatchEvent(new CustomEvent(\'open-delete-modal\', { detail: { action: \'' . $deleteUrl . '\' } }))" class="p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>';
                }

                $actions .= '</div>';
                return $actions;
            });
    }

    public function columns(): array
    {
        return [
            Column::add()->title('No')->index()
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center'),

            Column::make('Number', 'number')->sortable()->searchable(),
            Column::make('Capacity', 'capacity')->sortable()->searchable(),
            Column::make('Status', 'status')->sortable()->searchable(),
            // Column::make('Qr Code', 'qr_code')->sortable()->searchable(),
            Column::make('Show', 'show_display'),
            Column::make('Actions', 'action')
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center')
                ->visibleInExport(false),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::boolean('show_display', 'show'),
            Filter::select('status', 'status')
                ->dataSource([
                    ['id' => 'available', 'label' => 'Available'],
                    ['id' => 'occupied', 'label' => 'Occupied'],
                    ['id' => 'maintenance', 'label' => 'Maintenance'],
                ])
                ->optionValue('id')
                ->optionLabel('label'),
            // TODO: Add Soft Deletes filter if needed
            // Filter::boolean('deleted_at')->label('Trashed', 'Active'),
        ];
    }

    #[\Livewire\Attributes\On('triggerBulkDelete')]
    public function triggerBulkDelete(?array $ids = null): void
    {
        if (!$ids) {
            $ids = $this->checkboxValues;
        }

        if (empty($ids)) return;

        // Ensure user has permission
        // if (!auth()->user()->hasPermission('delete-{{modelName}}')) {
        //     $this->dispatch('notify', type: 'error', message: 'You do not have permission to delete {{modelName}}s.');
        //     return;
        // }

        $this->dispatch('confirm-bulk-delete', [
            'ids' => $ids,
            'model' => 'App\\\\Models\\\\DiningTable',
            'refreshRoute' => 'refreshDatatable'
        ]);
    }

    #[\Livewire\Attributes\On('bulkDeleteConfirmed')]
    public function bulkDeleteConfirmed($ids, $model): void
    {
        // TODO: Ensure user has permission
        // if (!auth()->user()->hasPermission('delete-{{modelName}}')) return;

        try {
            DiningTable::whereIn('id', $ids)->delete();
            ActivityLogService::logBulkDelete(DiningTable::class, count($ids), $ids);

            $this->js('window.pgBulkActions.clearAll()');
            $this->dispatch('notify', type: 'success', message: count($ids) . ' {{modelName}}s have been deleted.');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Failed to delete {{modelName}}s.');
        }
    }
}
