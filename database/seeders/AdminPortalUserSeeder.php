<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminPortalUserSeeder extends Seeder
{
    /**
     * Seed or update an admin credential for the portal.
     */
    public function run(): void
    {
        $email    = env('ADMIN_PORTAL_EMAIL', 'admin@mylaundrypos.com');
        $password = env('ADMIN_PORTAL_PASSWORD', 'Admin@12345');
        $name     = env('ADMIN_PORTAL_NAME', 'LaundryPOS Portal Admin');

        $user = User::updateOrCreate(
            ['email' => $email],
            array_filter([
                'name'              => $name,
                'password'          => Hash::make($password),
                'role'              => Schema::hasColumn('users', 'role') ? 'Super Admin' : null,
                'is_active'         => Schema::hasColumn('users', 'is_active') ? true : null,
                'email_verified_at' => now(),
            ], static fn($value) => $value !== null)
        );

        if (Schema::hasColumn('users', 'user_account_id') && ! $user->user_account_id) {
            $user->user_account_id = $user->id;
            $user->save();
        }
    }
}
