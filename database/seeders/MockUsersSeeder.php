<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MockUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Datos extraidos de mock-roles.js (ids, keys y nombres).
     */
    public function run(): void
    {
        $users = [
            ['id' => 1, 'name' => 'Víctor', 'email' => 'victor@example.com'],
            ['id' => 2, 'name' => 'Antonio', 'email' => 'antonio@example.com'],
            ['id' => 3, 'name' => 'Alberto', 'email' => 'alberto@example.com'],
        ];

        foreach ($users as $userData) {
            User::query()->updateOrCreate(
                ['id' => $userData['id']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}
