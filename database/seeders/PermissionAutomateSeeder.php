<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionAutomateSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Automate permissions
            'automate-list',
            'automate-create',
            'automate-edit',
            'automate-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }
    }
}
