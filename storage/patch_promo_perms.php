<?php
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

$permissions = [
    'view-promos' => 'promos',
    'create-promo' => 'promos',
    'edit-promo' => 'promos',
    'delete-promo' => 'promos',
];

foreach ($permissions as $name => $module) {
    if (!Permission::where('name', $name)->exists()) {
        Permission::create(['name' => $name, 'module' => $module, 'guard_name' => 'web']);
        echo "Created permission: $name\n";
    }
}

// Assign to Super Admin
$role = Role::where('name', 'Super Admin')->first();
if ($role) {
    foreach (array_keys($permissions) as $name) {
        if (!$role->hasPermissionTo($name)) {
            $role->givePermissionTo($name);
            echo "Assigned $name to Super Admin\n";
        }
    }
}
