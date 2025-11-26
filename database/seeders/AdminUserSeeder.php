<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create admin role if not exists
        Role::firstOrCreate(['name' => 'admin']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'superadmin',
                'password' => Hash::make('123456'),
            ]
        );

        $admin->assignRole('admin');
    }
}
