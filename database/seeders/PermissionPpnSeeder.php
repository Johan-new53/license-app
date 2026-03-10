<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionPpnSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'ppn-list',
            'ppn-create',
            'ppn-edit',
            'ppn-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }
    }
}
