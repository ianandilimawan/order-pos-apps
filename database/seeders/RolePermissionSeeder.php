<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@redtech.co.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('redtech.co.id'),
                'email_verified_at' => now(),
            ]
        );

        // Create Kasir User
        $kasir = User::firstOrCreate(
            ['email' => 'kasir@inpos.id'],
            [
                'name' => 'Kasir Demo',
                'password' => Hash::make('kasir123'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created: admin@retech.co.id / redtech.co.id');



        // Create Basic Permissions first
        $now = now();
        $permissions = [
            [
                'display_name' => 'View Users', // Custom display name
                'name' => 'view-users',         // Spatie name (slug)
                'description' => null,
                'module' => 'users',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create User',
                'name' => 'create-users',
                'description' => null,
                'module' => 'users',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit User',
                'name' => 'edit-users',
                'description' => null,
                'module' => 'users',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete User',
                'name' => 'delete-users',
                'description' => null,
                'module' => 'users',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Roles',
                'name' => 'view-roles',
                'description' => null,
                'module' => 'roles',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Role',
                'name' => 'create-roles',
                'description' => null,
                'module' => 'roles',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Role',
                'name' => 'edit-roles',
                'description' => null,
                'module' => 'roles',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Role',
                'name' => 'delete-roles',
                'description' => null,
                'module' => 'roles',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Permissions',
                'name' => 'view-permissions',
                'description' => null,
                'module' => 'permissions',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Permission',
                'name' => 'create-permissions',
                'description' => null,
                'module' => 'permissions',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Permission',
                'name' => 'edit-permissions',
                'description' => null,
                'module' => 'permissions',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Permission',
                'name' => 'delete-permissions',
                'description' => null,
                'module' => 'permissions',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Activity Logs',
                'name' => 'view-activity-logs',
                'description' => null,
                'module' => 'activity_logs',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'display_name' => 'View Laravel Logs',
                'name' => 'view-laravel-logs',
                'description' => 'Access to view Laravel application logs',
                'module' => 'logs',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Settings',
                'name' => 'view-settings',
                'description' => null,
                'module' => 'settings',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Setting',
                'name' => 'edit-settings',
                'description' => null,
                'module' => 'settings',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Promos',
                'name' => 'view-promos',
                'description' => null,
                'module' => 'promos',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Promo',
                'name' => 'create-promos',
                'description' => null,
                'module' => 'promos',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Promo',
                'name' => 'edit-promos',
                'description' => null,
                'module' => 'promos',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Promo',
                'name' => 'delete-promos',
                'description' => null,
                'module' => 'promos',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Categories',
                'name' => 'view-categories',
                'description' => 'Can view categories list',
                'module' => 'categories',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Categories',
                'name' => 'create-categories',
                'description' => 'Can create new category',
                'module' => 'categories',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Categories',
                'name' => 'edit-categories',
                'description' => 'Can edit category',
                'module' => 'categories',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Categories',
                'name' => 'delete-categories',
                'description' => 'Can delete category',
                'module' => 'categories',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Products',
                'name' => 'view-products',
                'description' => 'Can view products list',
                'module' => 'products',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Products',
                'name' => 'create-products',
                'description' => 'Can create new product',
                'module' => 'products',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Products',
                'name' => 'edit-products',
                'description' => 'Can edit product',
                'module' => 'products',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Products',
                'name' => 'delete-products',
                'description' => 'Can delete product',
                'module' => 'products',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Charge Settings',
                'name' => 'view-charge_settings',
                'description' => 'Can view chargesettings list',
                'module' => 'charge_settings',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Charge Settings',
                'name' => 'create-charge_settings',
                'description' => 'Can create new chargesetting',
                'module' => 'charge_settings',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Charge Settings',
                'name' => 'edit-charge_settings',
                'description' => 'Can edit chargesetting',
                'module' => 'charge_settings',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Charge Settings',
                'name' => 'delete-charge_settings',
                'description' => 'Can delete chargesetting',
                'module' => 'charge_settings',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Orders',
                'name' => 'view-orders',
                'description' => 'Can view orders list',
                'module' => 'orders',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Orders',
                'name' => 'create-orders',
                'description' => 'Can create new order',
                'module' => 'orders',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Orders',
                'name' => 'edit-orders',
                'description' => 'Can edit order',
                'module' => 'orders',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Orders',
                'name' => 'delete-orders',
                'description' => 'Can delete order',
                'module' => 'orders',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Order Items',
                'name' => 'view-order_items',
                'description' => 'Can view orderitems list',
                'module' => 'order_items',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Order Items',
                'name' => 'create-order_items',
                'description' => 'Can create new orderitem',
                'module' => 'order_items',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Order Items',
                'name' => 'edit-order_items',
                'description' => 'Can edit orderitem',
                'module' => 'order_items',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Order Items',
                'name' => 'delete-order_items',
                'description' => 'Can delete orderitem',
                'module' => 'order_items',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Order Charges',
                'name' => 'view-order_charges',
                'description' => 'Can view ordercharges list',
                'module' => 'order_charges',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Order Charges',
                'name' => 'create-order_charges',
                'description' => 'Can create new ordercharge',
                'module' => 'order_charges',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Order Charges',
                'name' => 'edit-order_charges',
                'description' => 'Can edit ordercharge',
                'module' => 'order_charges',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Order Charges',
                'name' => 'delete-order_charges',
                'description' => 'Can delete ordercharge',
                'module' => 'order_charges',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Dining Tables',
                'name' => 'view-dining_tables',
                'description' => 'Can view diningtables list',
                'module' => 'dining_tables',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Dining Tables',
                'name' => 'create-dining_tables',
                'description' => 'Can create new diningtable',
                'module' => 'dining_tables',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Edit Dining Tables',
                'name' => 'edit-dining_tables',
                'description' => 'Can edit diningtable',
                'module' => 'dining_tables',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Dining Tables',
                'name' => 'delete-dining_tables',
                'description' => 'Can delete diningtable',
                'module' => 'dining_tables',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Access POS Kasir',
                'name' => 'view-pos',
                'description' => 'Can access POS module',
                'module' => 'orders',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Reports',
                'name' => 'view-reports',
                'description' => 'Can view detailed reports and analytics',
                'module' => 'reports',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Cash Opnames',
                'name' => 'view-cash_opnames',
                'description' => 'Can view cashopnames list',
                'module' => 'cash_opnames',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Cash Opnames',
                'name' => 'create-cash_opnames',
                'description' => 'Can create new cashopname',
                'module' => 'cash_opnames',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Update Cash Opnames',
                'name' => 'edit-cash_opnames',
                'description' => 'Can update cashopname',
                'module' => 'cash_opnames',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Cash Opnames',
                'name' => 'delete-cash_opnames',
                'description' => 'Can delete cashopname',
                'module' => 'cash_opnames',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'View Backups',
                'name' => 'view-backups',
                'description' => 'Can view database backups',
                'module' => 'system',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Create Backup',
                'name' => 'create-backup',
                'description' => 'Can create and upload backups',
                'module' => 'system',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Restore Backup',
                'name' => 'restore-backup',
                'description' => 'Can restore database from backups',
                'module' => 'system',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'display_name' => 'Delete Backup',
                'name' => 'delete-backup',
                'description' => 'Can delete backups',
                'module' => 'system',
                'is_active' => true,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                $permission
            );
        }

        $this->command->info('System permissions created');



        // Create Administrator Role
        $administratorRole = Role::updateOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            [
                'display_name' => 'Administrator',
                'name' => 'admin',
                'description' => 'Full system access',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $this->command->info('Administrator role created');

        // Assign operational permissions to Administrator role (exclude raw logs & permission management)
        $allPermissions = Permission::whereNotIn('name', ['view-laravel-logs', 'view-permissions', 'create-permissions', 'edit-permissions', 'delete-permissions'])->get();
        if ($allPermissions->count() > 0) {
            $administratorRole->syncPermissions($allPermissions);
            $this->command->info('Operational permissions assigned to Administrator role (sensitive permissions hidden)');
        }

        // Assign Administrator role to admin user
        if ($administratorRole) {
            $admin->assignRole($administratorRole);
            $this->command->info('Admin user assigned to Administrator role');
        }

        // Create Kasir Role
        $kasirRole = Role::updateOrCreate(
            ['name' => 'kasir', 'guard_name' => 'web'],
            [
                'display_name' => 'Kasir',
                'name' => 'kasir',
                'description' => 'Akses Kasir POS dan pemesanan',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $kasirPermissions = Permission::whereIn('name', [
            'view-pos',
            'view-orders',
            'create-orders',
            'edit-orders',
            'view-products',
            'view-categories',
            'view-dining_tables',
            'view-cash_opnames',
            'create-cash_opnames',
        ])->get();
        
        if ($kasirPermissions->count() > 0) {
            $kasirRole->syncPermissions($kasirPermissions);
            $this->command->info('Kasir permissions assigned');
        }

        if ($kasir && $kasirRole) {
            $kasir->assignRole($kasirRole);
            $this->command->info('Kasir user assigned to Kasir role');
        }

        // Create Developer Role
        $developerRole = Role::updateOrCreate(
            ['name' => 'developer', 'guard_name' => 'web'],
            [
                'display_name' => 'Developer',
                'name' => 'developer',
                'description' => 'Access to view Laravel logs and system debugging',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $this->command->info('Developer role created');

        // Assign Laravel Logs permission to Developer role
        $viewLaravelLogsPermission = Permission::where('name', 'view-laravel-logs')->first();
        if ($viewLaravelLogsPermission) {
            $developerRole->givePermissionTo($viewLaravelLogsPermission);
            $this->command->info('Laravel Logs permission assigned to Developer role');
        }
    }
}
