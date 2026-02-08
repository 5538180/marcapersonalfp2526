<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Concerns\ApiResponseHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMatriculaRequest;
use App\Http\Requests\UpdateMatriculaRequest;
use App\Http\Resources\MatriculaResource;
use App\Models\Matricula;
use App\Models\Modulo;

class MatriculaController extends Controller
{
    use ApiResponseHelpers;

    public function index()
    {
        $paginator = Matricula::query()->orderBy('id')->paginate(10);

        return $this->paginatedResponse(
            collect(MatriculaResource::collection($paginator->getCollection())->resolve()),
            $paginator
        );
    }

    public function store(StoreMatriculaRequest $request)
    {
        $payload = $this->completeModuleSnapshot($request->validated());
        $matricula = Matricula::query()->create($payload);

        return $this->dataResponse((new MatriculaResource($matricula))->resolve());
    }

    public function show(Matricula $matricula)
    {
        return $this->dataResponse((new MatriculaResource($matricula))->resolve());
    }

    public function update(UpdateMatriculaRequest $request, Matricula $matricula)
    {
        $payload = $this->completeModuleSnapshot($request->validated(), $matricula->modulo_id);
        $matricula->update($payload);

        return $this->dataResponse((new MatriculaResource($matricula))->resolve());
    }

    public function destroy(Matricula $matricula)
    {
        return $this->deleteModel($matricula);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function completeModuleSnapshot(array $payload, ?int $fallbackModuloId = null): array
    {
        $moduloId = (int) ($payload['modulo_id'] ?? $fallbackModuloId);
        $modulo = Modulo::query()->findOrFail($moduloId);

        $payload['modulo_id'] = $moduloId;
        $payload['ciclo_formativo_id'] = $payload['ciclo_formativo_id'] ?? $modulo->ciclo_formativo_id;
        $payload['nombre'] = $payload['nombre'] ?? $modulo->nombre;
        $payload['codigo'] = $payload['codigo'] ?? $modulo->codigo;

        return $payload;
    }
}
