<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'edutrack@softwebies.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'role' => 'super-admin',
            'membership_expiry_date' => now()->addYear(),
        ]);
        
        $this->call([
            MembershipSeeder::class,
            // SchoolSeeder::class,
            // ClassSeeder::class,
            // SectionSeeder::class,
            // StudentSeeder::class,
            // AttendanceSeeder::class,
            // DailyTestSeeder::class,
        ]);
    }
}
