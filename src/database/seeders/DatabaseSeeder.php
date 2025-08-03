<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('permission.default_roles') as $key => $roleName) {
            // Create default roles if they do not exist
            // Using firstOrCreate to avoid duplicates
            // This will check if the role exists, and if not, it will create it
            // This is useful for seeding roles in a fresh database
            // or when roles might have been deleted.
            // It ensures that the seeder can be run multiple times without causing errors.
            Role::firstOrCreate(['name' => $roleName]);
        }

        foreach (config('permission.default_permissions') as $key => $permissionName) {
            // Create default permissions if they do not exist
            // Using firstOrCreate to avoid duplicates
            // This will check if the permission exists, and if not, it will create it
            // This is useful for seeding permissions in a fresh database
            // or when permissions might have been deleted.
            // It ensures that the seeder can be run multiple times without causing errors.
            // This is particularly useful in applications where permissions are dynamic
            // and can be added or removed over time.
            Permission::firstOrCreate(['name' => $permissionName]);
        }
    }
}
