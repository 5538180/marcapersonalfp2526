<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Concerns\ApiResponseHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreModuloRequest;
use App\Http\Requests\UpdateModuloRequest;
use App\Http\Resources\ModuloResource;
use App\Models\DocenteModulo;
use App\Models\Matricula;
use App\Models\Modulo;
use Illuminate\Database\Eloquent\Model;

class ModuloController extends Controller
{
    use ApiResponseHelpers;

    public function index()
    {
        $paginator = Modulo::query()->orderBy('id')->paginate(10);

        return $this->paginatedResponse(
            collect(ModuloResource::collection($paginator->getCollection())->resolve()),
            $paginator
        );
    }

    public function store(StoreModuloRequest $request)
    {
        $modulo = Modulo::query()->create($request->validated());

        return $this->dataResponse((new ModuloResource($modulo))->resolve());
    }

    public function show(Modulo $modulo)
    {
        return $this->dataResponse((new ModuloResource($modulo))->resolve());
    }

    public function update(UpdateModuloRequest $request, Modulo $modulo)
    {
        $modulo->update($request->validated());

        return $this->dataResponse((new ModuloResource($modulo))->resolve());
    }

    public function destroy(Modulo $modulo)
    {
        return $this->deleteModel($modulo);
    }

    public function impartidos()
    {
        return response()->json(
            $this->buildMockModulesShape(
                DocenteModulo::class,
                [1 => 'Victor', 3 => 'Alberto']
            )
        );
    }

    public function matriculados()
    {
        return response()->json(
            $this->buildMockModulesShape(
                Matricula::class,
                [1 => 'Victor', 2 => 'Antonio']
            )
        );
    }

    /**
     * @param class-string<Model> $modelClass
     * @param array<int, string> $usersById
     * @return array<string, array<string, mixed>>
     */
    private function buildMockModulesShape(string $modelClass, array $usersById): array
    {
        $shape = [];
        foreach ($usersById as $mockKey) {
            $shape[$mockKey] = [
                'buscando' => false,
                'lista' => [],
            ];
        }

        $rows = $modelClass::query()
            ->whereIn('user_id', array_keys($usersById))
            ->orderBy('user_id')
            ->orderBy('modulo_id')
            ->get([
                'user_id',
                'modulo_id as id',
                'ciclo_formativo_id',
                'nombre',
                'codigo',
            ]);

        foreach ($rows as $row) {
            $mockKey = $usersById[(int) $row->user_id] ?? null;
            if (! $mockKey) {
                continue;
            }

            $shape[$mockKey]['lista'][] = [
                'id' => (int) $row->id,
                'ciclo_formativo_id' => (int) $row->ciclo_formativo_id,
                'nombre' => $row->nombre,
                'codigo' => $row->codigo,
            ];
        }

        return $shape;
    }
}
