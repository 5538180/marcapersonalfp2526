<?php

namespace App\Http\Controllers\API\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait ApiResponseHelpers
{
    protected function paginatedResponse(Collection $data, LengthAwarePaginator $paginator)
    {
        return response()->json([
            'data' => $data->values(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'from' => $paginator->firstItem(),
                'last_page' => $paginator->lastPage(),
                'path' => $paginator->path(),
                'per_page' => $paginator->perPage(),
                'to' => $paginator->lastItem(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    protected function dataResponse(array $data)
    {
        return response()->json(['data' => $data]);
    }

    protected function deleteModel(Model $model)
    {
        try {
            $model->delete();
            return response()->noContent();
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'No se puede eliminar el recurso por restricciones de integridad.',
            ], 409);
        }
    }
}
