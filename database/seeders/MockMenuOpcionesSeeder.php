<?php

namespace Database\Seeders;

use App\Models\MenuOpcion;
use App\Models\Role;
use Illuminate\Database\Seeder;

class MockMenuOpcionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Datos extraidos de mock-administrador.js.
     */
    public function run(): void
    {
        $adminRole = Role::query()->where('name', 'administrador')->first();
        if (! $adminRole) {
            return;
        }

        $now = now();
        $rows = [];
        $order = 1;

        foreach (self::OPCIONES_ADMINISTRADOR as $opcion) {
            $rows[] = [
                'role_id' => $adminRole->id,
                'nombre' => $opcion['nombre'],
                'ruta' => $opcion['ruta'],
                'orden' => $order++,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        MenuOpcion::query()->upsert(
            $rows,
            ['role_id', 'ruta'],
            ['nombre', 'orden', 'updated_at']
        );
    }

    private const OPCIONES_ADMINISTRADOR = [
        ['nombre' => 'Familias profesionales', 'ruta' => '/familiasprofesionales'],
        ['nombre' => 'Ciclos formativos', 'ruta' => '/ciclosformativos'],
        ['nombre' => 'Modulos formativos', 'ruta' => '/modulosformativos'],
        ['nombre' => 'Resultados de aprendizaje', 'ruta' => '/ra'],
        ['nombre' => 'Criterios de evaluacion', 'ruta' => '/ce'],
        ['nombre' => 'Crear usuarios', 'ruta' => '/crearusuarios'],
        ['nombre' => 'Asignar roles', 'ruta' => '/roles'],
        ['nombre' => 'Gestionar matriculas', 'ruta' => '/matriculas'],
        ['nombre' => 'Asignar docentes', 'ruta' => '/docentes'],
    ];
}
