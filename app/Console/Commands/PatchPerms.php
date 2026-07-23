<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PatchPerms extends Command
{
    protected $signature = 'patch:perms';

    public function handle()
    {
        $permissions = [
            'view-promos' => 'promos',
            'create-promo' => 'promos',
            'edit-promo' => 'promos',
            'delete-promo' => 'promos',
            'view-reports' => 'reports',
        ];

        foreach ($permissions as $name => $module) {
            if (!Permission::where('name', $name)->exists()) {
                Permission::create(['name' => $name, 'module' => $module, 'guard_name' => 'web']);
                $this->info("Created permission: $name");
            }
        }

        $role = Role::where('name', 'Super Admin')->first();
        if ($role) {
            foreach (array_keys($permissions) as $name) {
                if (!$role->hasPermissionTo($name)) {
                    $role->givePermissionTo($name);
                    $this->info("Assigned $name to Super Admin");
                }
            }
        }
    }
}
