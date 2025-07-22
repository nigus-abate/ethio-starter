<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define grouped permissions
        $groupedPermissions = [
            'Users' => [
                'view users',
                'create users',
                'edit users',
                'delete users',
                'impersonate users',
            ],
            'Roles' => [
                'view roles',
                'create roles',
                'edit roles',
                'delete roles',
            ],
            'Permissions' => [
                'view permissions',
                'create permissions',
                'edit permissions',
                'delete permissions',
            ],
            'Settings' => [
                'view settings',
                'create settings',
                'edit settings',
                'delete settings',
            ],

            'Activity Logs' => [
                'view activity logs',
                'delete activity logs',
            ],
            
            'Backups' => [
                'view backups',
                'create backups',
                'edit backups',
                'delete backups',
                'download backups',
                'restore backups',
            ],
            'Job Queue' => [
                'view jobs',
                'delete jobs',
                'retry jobs',
            ],
        ];

        // Loop through grouped permissions
        foreach ($groupedPermissions as $group => $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission,
                ], [
                    'group' => $group,
                    'is_protected' => true,
                ]);
            }
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['is_protected' => true]);
        $adminRole->syncPermissions(Permission::all());

        $userRole = Role::firstOrCreate(['name' => 'user'], ['is_protected' => true]);
        $userRole->givePermissionTo('view users');

        // Create admin user
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'storage_limit' => 1024,
        ]);
        $admin->assignRole($adminRole);

        // Create basic user
        $user = User::firstOrCreate([
            'email' => 'user@example.com',
        ], [
            'name' => 'Regular User',
            'password' => Hash::make('password'),
            'storage_limit' => 1024,
        ]);
        $user->assignRole($userRole);
    }
}
