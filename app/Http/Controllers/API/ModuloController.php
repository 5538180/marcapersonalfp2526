<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Concerns\ApiResponseHelpers;
use App\Http\Controllers\API\Concerns\ResolvesApiUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreModuloRequest;
use App\Http\Requests\UpdateModuloRequest;
use App\Http\Resources\ModuloResource;
use App\Models\DocenteModulo;
use App\Models\Matricula;
use App\Models\Modulo;
use Illuminate\Http\Request;

class ModuloController extends Controller
{
    use ApiResponseHelpers;
    use ResolvesApiUser;

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

    public function impartidos(Request $request)
    {
        $user = $this->resolveApiUser($request);
        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $lista = DocenteModulo::query()
            ->where('user_id', $user->id)
            ->orderBy('modulo_id')
            ->get([
                'modulo_id as id',
                'ciclo_formativo_id',
                'nombre',
                'codigo',
            ])
            ->map(fn (DocenteModulo $item) => [
                'id' => $item->id,
                'ciclo_formativo_id' => $item->ciclo_formativo_id,
                'nombre' => $item->nombre,
                'codigo' => $item->codigo,
            ])
            ->values();

        return response()->json([
            'buscando' => false,
            'lista' => $lista,
        ]);
    }

    public function matriculados(Request $request)
    {
        $user = $this->resolveApiUser($request);
        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $lista = Matricula::query()
            ->where('user_id', $user->id)
            ->orderBy('modulo_id')
            ->get([
                'modulo_id as id',
                'ciclo_formativo_id',
                'nombre',
                'codigo',
            ])
            ->map(fn (Matricula $item) => [
                'id' => $item->id,
                'ciclo_formativo_id' => $item->ciclo_formativo_id,
                'nombre' => $item->nombre,
                'codigo' => $item->codigo,
            ])
            ->values();

        return response()->json([
            'buscando' => false,
            'lista' => $lista,
        ]);
    }
}
