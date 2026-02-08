<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Concerns\ApiResponseHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuOpcionRequest;
use App\Http\Requests\UpdateMenuOpcionRequest;
use App\Http\Resources\MenuOpcionResource;
use App\Models\MenuOpcion;

class MenuOpcionController extends Controller
{
    use ApiResponseHelpers;

    public function index()
    {
        $paginator = MenuOpcion::query()->orderBy('id')->paginate(10);

        return $this->paginatedResponse(
            collect(MenuOpcionResource::collection($paginator->getCollection())->resolve()),
            $paginator
        );
    }

    public function store(StoreMenuOpcionRequest $request)
    {
        $menuOpcion = MenuOpcion::query()->create($request->validated());

        return $this->dataResponse((new MenuOpcionResource($menuOpcion))->resolve());
    }

    public function show(MenuOpcion $menuOpcion)
    {
        return $this->dataResponse((new MenuOpcionResource($menuOpcion))->resolve());
    }

    public function update(UpdateMenuOpcionRequest $request, MenuOpcion $menuOpcion)
    {
        $menuOpcion->update($request->validated());

        return $this->dataResponse((new MenuOpcionResource($menuOpcion))->resolve());
    }

    public function destroy(MenuOpcion $menuOpcion)
    {
        return $this->deleteModel($menuOpcion);
    }

    public function administrador()
    {
        $opciones = MenuOpcion::query()
            ->whereHas('role', fn ($query) => $query->where('name', 'administrador'))
            ->orderBy('orden')
            ->get(['nombre', 'ruta'])
            ->map(fn (MenuOpcion $item) => [
                'nombre' => $item->nombre,
                'ruta' => $item->ruta,
            ])
            ->values();

        return response()->json([
            'administrador' => $opciones,
        ]);
    }
}
