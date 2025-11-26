<?php

namespace App\Actions\User;

use App\Models\User;
use App\helpers\ImageManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UpdateUserAction
{
    public function handle(array $data, User $user): User
    {
        return DB::transaction(function () use ($data, $user) {
            $role = Role::findOrFail($data['role_id']);

            $payload = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'name' => $data['username'],
                'user_id' => $data['user_id'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'telegram' => $data['telegram'] ?? null,
            ];

            if (! empty($data['password'])) {
                $payload['password'] = Hash::make($data['password']);
            }

            if (! empty($data['image'])) {
                $payload['image'] = ImageManager::update('uploads/users', $user->image, $data['image']);
            }

            $user->update($payload);
            $user->syncRoles([$role->name]);

            return $user;
        });
    }

    public static function run(array $data, User $user): User
    {
        return app(self::class)->handle($data, $user);
    }
}
