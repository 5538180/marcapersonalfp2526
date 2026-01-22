<?php

use App\Http\Controllers\API\CicloController;
use App\Http\Controllers\API\FamiliaProfesionalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Psr\Http\Message\ServerRequestInterface;
use Tqdev\PhpCrudApi\Api;
use Tqdev\PhpCrudApi\Config\Config;
<<<<<<< HEAD
=======

>>>>>>> e49a2b3bf706af79acf4ff614c04f53ab6e083c0

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user()->curriculo;
});


<<<<<<< HEAD
Route::prefix('v1')->group(function () {
    Route::apiResource('ciclos', CicloController::class);
    Route::apiResource('familias_profesionales', FamiliaProfesionalController::class)->parameters([
        'familias_profesionales' => 'familiaProfesional'
    ]);

=======
// Rutas /api/vi1
Route::prefix('v1')->group(function () {

     Route::apiResource('ciclos', CicloController::class);

     Route::apiResource('familias_profesionales', FamiliaProfesionalController::class)->parameters([
        'familias_profesionales' => 'familiaProfesional'

    ]);
>>>>>>> e49a2b3bf706af79acf4ff614c04f53ab6e083c0
});



<<<<<<< HEAD

=======
// Rutas PHP-CURD-API
>>>>>>> e49a2b3bf706af79acf4ff614c04f53ab6e083c0
Route::any('/{any}', function (ServerRequestInterface $request) {
    $config = new Config([
        'address' => env('DB_HOST', '127.0.0.1'),
        'database' => env('DB_DATABASE', 'forge'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
        'basePath' => '/api',
    ]);
    $api = new Api($config);
    $response = $api->handle($request);

    try {
        $records = json_decode($response->getBody()->getContents())->records;
        $response = response()->json($records, 200, $headers = ['X-Total-Count' => count($records)]);
    } catch (\Throwable $th) {
<<<<<<< HEAD
    }
    return $response;
})->where('any', '.*');
=======

    }
    return $response;

})->where('any', '.*');
    //->middleware(['auth:sanctum']);
>>>>>>> e49a2b3bf706af79acf4ff614c04f53ab6e083c0
