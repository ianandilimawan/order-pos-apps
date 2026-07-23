<?php
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

Route::get('/patch-perms', function () {
    $permissions = [
        'view-promos' => 'promos',
        'create-promo' => 'promos',
        'edit-promo' => 'promos',
        'delete-promo' => 'promos',
    ];

    foreach ($permissions as $name => $module) {
        if (!Permission::where('name', $name)->exists()) {
            Permission::create(['name' => $name, 'module' => $module, 'guard_name' => 'web']);
        }
    }

    $role = Role::where('name', 'Super Admin')->first();
    if ($role) {
        foreach (array_keys($permissions) as $name) {
            if (!$role->hasPermissionTo($name)) {
                $role->givePermissionTo($name);
            }
        }
    }
    return "Done";
});
