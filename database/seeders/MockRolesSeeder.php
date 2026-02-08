<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class MockRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Datos extraidos de mock-roles.js.
     */
    public function run(): void
    {
        $now = now();

        Role::query()->upsert(
            [
                ['id' => 1, 'name' => 'docente', 'created_at' => $now, 'updated_at' => $now],
                ['id' => 2, 'name' => 'estudiante', 'created_at' => $now, 'updated_at' => $now],
                ['id' => 3, 'name' => 'administrador', 'created_at' => $now, 'updated_at' => $now],
            ],
            ['id'],
            ['name', 'updated_at']
        );

        $roleIdsByName = Role::query()
            ->whereIn('name', ['docente', 'estudiante', 'administrador'])
            ->pluck('id', 'name');

        $rolesByUserId = [
            1 => ['docente', 'estudiante', 'administrador'], // Victor
            2 => ['estudiante'], // Antonio
            3 => ['docente', 'administrador'], // Alberto
        ];

        foreach ($rolesByUserId as $userId => $roleNames) {
            $user = User::query()->find($userId);
            if (! $user) {
                continue;
            }

            $user->roles()->sync(
                collect($roleNames)
                    ->map(fn (string $roleName) => $roleIdsByName[$roleName] ?? null)
                    ->filter()
                    ->values()
                    ->all()
            );
        }
    }
}
