<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Bank
            'bank-list',
            'bank-create',
            'bank-edit',

            // Department
            'dep-list',
            'dep-create',
            'dep-edit',

            // Rekening Sumber
            'reksumber-list',
            'reksumber-create',
            'reksumber-edit',

            // Mata Uang
            'matauang-list',
            'matauang-create',
            'matauang-edit',

            // Rekening Tujuan
            'rektujuan-list',
            'rektujuan-create',
            'rektujuan-edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }
    }
}
