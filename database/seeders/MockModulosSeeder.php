<?php

namespace Database\Seeders;

use App\Models\DocenteModulo;
use App\Models\Matricula;
use App\Models\Modulo;
use Illuminate\Database\Seeder;

class MockModulosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Datos extraidos de mock-impartidos.js y mock-matriculados.js.
     */
    public function run(): void
    {
        $now = now();

        // Union por id (upsert). Si hay conflicto de id entre mocks, prevalece la ultima aparicion.
        $modulosById = [];
        foreach ([self::IMPARTIDOS, self::MATRICULADOS] as $dataset) {
            foreach ($dataset as $payload) {
                foreach ($payload['lista'] as $modulo) {
                    $modulosById[$modulo['id']] = [
                        'id' => $modulo['id'],
                        'ciclo_formativo_id' => $modulo['ciclo_formativo_id'],
                        'nombre' => $modulo['nombre'],
                        'codigo' => $modulo['codigo'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        Modulo::query()->upsert(
            array_values($modulosById),
            ['id'],
            ['ciclo_formativo_id', 'nombre', 'codigo', 'updated_at']
        );

        $docentesRows = [];
        foreach (self::IMPARTIDOS as $userKey => $payload) {
            $userId = self::USER_IDS[$userKey] ?? null;
            if (! $userId) {
                continue;
            }

            foreach ($payload['lista'] as $modulo) {
                $docentesRows[] = [
                    'user_id' => $userId,
                    'modulo_id' => $modulo['id'],
                    'ciclo_formativo_id' => $modulo['ciclo_formativo_id'],
                    'nombre' => $modulo['nombre'],
                    'codigo' => $modulo['codigo'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DocenteModulo::query()->upsert(
            $docentesRows,
            ['user_id', 'modulo_id'],
            ['ciclo_formativo_id', 'nombre', 'codigo', 'updated_at']
        );

        $matriculasRows = [];
        foreach (self::MATRICULADOS as $userKey => $payload) {
            $userId = self::USER_IDS[$userKey] ?? null;
            if (! $userId) {
                continue;
            }

            foreach ($payload['lista'] as $modulo) {
                $matriculasRows[] = [
                    'user_id' => $userId,
                    'modulo_id' => $modulo['id'],
                    'ciclo_formativo_id' => $modulo['ciclo_formativo_id'],
                    'nombre' => $modulo['nombre'],
                    'codigo' => $modulo['codigo'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        Matricula::query()->upsert(
            $matriculasRows,
            ['user_id', 'modulo_id'],
            ['ciclo_formativo_id', 'nombre', 'codigo', 'updated_at']
        );
    }

    private const USER_IDS = [
        'Victor' => 1,
        'Antonio' => 2,
        'Alberto' => 3,
    ];

    private const IMPARTIDOS = [
        'Victor' => [
            'buscando' => false,
            'lista' => [
                ['id' => 12, 'ciclo_formativo_id' => 91, 'nombre' => 'Desarrollo web en entorno cliente', 'codigo' => '0612'],
                ['id' => 13, 'ciclo_formativo_id' => 91, 'nombre' => 'Sistemas Informáticos', 'codigo' => '0483'],
            ],
        ],
        'Alberto' => [
            'buscando' => false,
            'lista' => [
                ['id' => 14, 'ciclo_formativo_id' => 91, 'nombre' => 'Desarrollo web en entorno servidor', 'codigo' => '0613'],
            ],
        ],
    ];

    private const MATRICULADOS = [
        'Victor' => [
            'buscando' => false,
            'lista' => [
                ['id' => 12, 'ciclo_formativo_id' => 91, 'nombre' => 'Desarrollo web en entorno cliente', 'codigo' => '0612'],
                ['id' => 13, 'ciclo_formativo_id' => 91, 'nombre' => 'Desarrollo web en entorno servidor', 'codigo' => '0613'],
                ['id' => 14, 'ciclo_formativo_id' => 91, 'nombre' => 'Entornos de Desarrollo', 'codigo' => '0487'],
            ],
        ],
        'Antonio' => [
            'buscando' => false,
            'lista' => [
                ['id' => 12, 'ciclo_formativo_id' => 91, 'nombre' => 'Desarrollo web en entorno cliente', 'codigo' => '0612'],
            ],
        ],
    ];
}
