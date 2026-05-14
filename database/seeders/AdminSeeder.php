<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'                => 'Admin SMARTKA',
            'email'               => 'admin@smartka.id',
            'password'            => Hash::make('admin123'),
            'role'                => 'admin',
            'email_verified_at'   => now(),
            'subscription_status' => 'premium_plus',
        ]);
    }
}