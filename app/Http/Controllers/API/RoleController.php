<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Concerns\ApiResponseHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use ApiResponseHelpers;

    public function index(Request $request)
    {
        // GET /api/v1/roles -> payload exacto del mock frontend.
        // GET /api/v1/roles?crud=1 -> listado CRUD paginado.
        if (! $request->boolean('crud')) {
            $usersById = User::query()
                ->whereIn('id', [1, 2, 3])
                ->with(['roles' => fn ($query) => $query->orderBy('roles.id')])
                ->get()
                ->keyBy('id');

            $mockShape = [];
            foreach ([1 => 'Victor', 2 => 'Antonio', 3 => 'Alberto'] as $userId => $mockKey) {
                $user = $usersById->get($userId);
                if (! $user) {
                    continue;
                }

                $mockShape[$mockKey] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'roles' => $user->roles->pluck('name')->values()->all(),
                ];
            }

            return response()->json($mockShape);
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
