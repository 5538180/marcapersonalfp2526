<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Concerns\ApiResponseHelpers;
use App\Http\Controllers\API\Concerns\ResolvesApiUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use ApiResponseHelpers;
    use ResolvesApiUser;

    public function index(Request $request)
    {
        // GET /api/v1/roles -> payload del frontend (usuario conectado).
        // GET /api/v1/roles?crud=1 -> listado CRUD paginado.
        if (! $request->boolean('crud')) {
            $user = $this->resolveApiUser($request);
            if (! $user) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'roles' => $user->roles()->orderBy('roles.id')->pluck('name')->values()->all(),
            ]);
        }

        $paginator = Role::query()->orderBy('id')->paginate(10);

        return $this->paginatedResponse(
            collect(RoleResource::collection($paginator->getCollection())->resolve()),
            $paginator
        );
    }

    public function store(StoreRoleRequest $request)
    {
        $role = Role::query()->create($request->validated());

        return $this->dataResponse((new RoleResource($role))->resolve());
    }

    public function show(Role $role)
    {
        return $this->dataResponse((new RoleResource($role))->resolve());
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update($request->validated());

        return $this->dataResponse((new RoleResource($role))->resolve());
    }

    public function destroy(Role $role)
    {
        return $this->deleteModel($role);
    }
}
