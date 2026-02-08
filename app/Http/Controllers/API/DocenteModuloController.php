<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Concerns\ApiResponseHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocenteModuloRequest;
use App\Http\Requests\UpdateDocenteModuloRequest;
use App\Http\Resources\DocenteModuloResource;
use App\Models\DocenteModulo;
use App\Models\Modulo;

class DocenteModuloController extends Controller
{
    use ApiResponseHelpers;

    public function index()
    {
        $paginator = DocenteModulo::query()->orderBy('id')->paginate(10);

        return $this->paginatedResponse(
            collect(DocenteModuloResource::collection($paginator->getCollection())->resolve()),
            $paginator
        );
    }

    public function store(StoreDocenteModuloRequest $request)
    {
        $payload = $this->completeModuleSnapshot($request->validated());
        $docenteModulo = DocenteModulo::query()->create($payload);

        return $this->dataResponse((new DocenteModuloResource($docenteModulo))->resolve());
    }

    public function show(DocenteModulo $docenteModulo)
    {
        return $this->dataResponse((new DocenteModuloResource($docenteModulo))->resolve());
    }

    public function update(UpdateDocenteModuloRequest $request, DocenteModulo $docenteModulo)
    {
        $payload = $this->completeModuleSnapshot($request->validated(), $docenteModulo->modulo_id);
        $docenteModulo->update($payload);

        return $this->dataResponse((new DocenteModuloResource($docenteModulo))->resolve());
    }

    public function destroy(DocenteModulo $docenteModulo)
    {
        return $this->deleteModel($docenteModulo);
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
