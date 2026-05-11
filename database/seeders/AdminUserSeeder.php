<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // updateOrCreate zodat je de seeder veilig meerdere keren kunt draaien zonder duplicaten
        User::updateOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name'              => 'Admin',
                'password'          => "demo",
                'email_verified_at' => now(),
            ]
        );
    }
}
