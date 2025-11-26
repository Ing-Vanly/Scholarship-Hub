<?php

namespace App\Actions\User;

use App\Models\User;
use App\helpers\ImageManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateUserAction
{
    public function handle(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $role = Role::findOrFail($data['role_id']);

            $payload = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'name' => $data['username'],
                'user_id' => $data['user_id'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'telegram' => $data['telegram'] ?? null,
                'password' => Hash::make($data['password']),
            ];

            if (! empty($data['image'])) {
                $payload['image'] = ImageManager::upload('uploads/users', $data['image']);
            }

            $user = User::create($payload);
            $user->syncRoles([$role->name]);

            return $user;
        });
    }

    public static function run(array $data): User
    {
        return app(self::class)->handle($data);
    }
}
