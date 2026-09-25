<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = env('ADMIN_NAME');
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! is_string($name) || ! is_string($email) || ! is_string($password) || blank($name) || blank($email) || blank($password)) {
            throw new RuntimeException('Admin seeder requires ADMIN_NAME, ADMIN_EMAIL, and ADMIN_PASSWORD environment values.');
        }

        $admin = User::query()->firstOrNew(['email' => $email]);

        $admin->forceFill([
            'name' => $name,
            'role' => 'admin',
        ]);

        if (! $admin->exists) {
            $admin->forceFill([
                'password' => Hash::make($password),
            ]);
        }

        $admin->save();
    }
}
