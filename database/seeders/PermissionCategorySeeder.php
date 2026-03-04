<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionCategorySeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Category permissions
            'category-list',
            'category-create',
            'category-edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }
    }
}
