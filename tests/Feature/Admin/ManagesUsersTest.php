<?php

namespace Tests\Feature\Admin;

use App\helpers\ImageManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ManagesUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_user_with_profile_image(): void
    {
        $this->withoutExceptionHandling();

        $role = Role::create([
            'name' => 'manager',
            'guard_name' => 'web',
        ]);

        $admin = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $admin->assignRole($role);

        $payload = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'username' => 'janedoe',
            'user_id' => 'EMP001',
            'role_id' => $role->id,
            'phone' => '123456789',
            'telegram' => '@jane',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'image' => UploadedFile::fake()->image('avatar.jpg', 200, 200),
        ];

        $response = $this->actingAs($admin)->post(route('admin.user.store'), $payload);

        $response->assertRedirect(route('admin.user.index'));

        $user = User::where('email', $payload['email'])->firstOrFail();
        $this->assertNotNull($user->image);

        $assetPath = public_path('uploads/users/' . $user->image);
        $this->assertFileExists($assetPath);

        ImageManager::delete('uploads/users/' . $user->image);
    }
}
