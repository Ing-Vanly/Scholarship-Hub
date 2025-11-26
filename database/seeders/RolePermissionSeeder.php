<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name'       => 'user.view',
                'guard_name' => 'web',
            ],
            [
                'name'   => 'user.create',
                'guard_name' => 'web',
            ],
            [
                'name'   => 'user.edit',
                'guard_name' => 'web',
            ],
            [
                'name'   => 'user.delete',
                'guard_name' => 'web',
            ],
        ];
        Permission::INSERT($data);
    }
}
