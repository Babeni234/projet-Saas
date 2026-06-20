<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::where('email', 'superadmin@propertyai.com')->first();

        if (!$superAdmin) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@propertyai.com',
                'password' => Hash::make('password'),
                'account_type' => 'Super ADMIN', // The user requested "Super ADMIN"
                'subscription_plan' => 'enterprise',
                'status' => 'active',
            ]);
        }
    }
}
