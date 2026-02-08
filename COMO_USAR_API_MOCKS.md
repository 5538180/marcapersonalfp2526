# Como usar la API que reemplaza los mocks

## 0) Resumen de todo lo implementado

Este backend ya se dejo preparado para reemplazar los 4 mocks de frontend y dar CRUD real en Laravel.

Se implemento:

- Lectura de mocks como fuente de verdad:
- `eporfolio_daschboard_privadoNube/src/Mocks/mock-roles.js`
- `eporfolio_daschboard_privadoNube/src/Mocks/mock-impartidos.js`
- `eporfolio_daschboard_privadoNube/src/Mocks/mock-matriculados.js`
- `eporfolio_daschboard_privadoNube/src/Mocks/mock-administrador.js`
- Endpoints exactos de frontend:
- `GET /api/v1/roles`
- `GET /api/v1/modulos/impartidos`
- `GET /api/v1/modulos/matriculados`
- `GET /api/v1/menu/administrador`
- CRUD REST completo:
- `/api/v1/roles` (index frontend + `?crud=1` para listado paginado)
- `/api/v1/modulos`
- `/api/v1/matriculas`
- `/api/v1/docentes-modulos`
- `/api/v1/menu-opciones`
- Respuesta estandar CRUD:
- `index` -> `{ data, meta }`
- `show/store/update` -> `{ data }`
- `destroy` -> `204`
- Manejo de conflictos de borrado:
- si hay restriccion FK, responde `409`
- Auth:
- Sanctum activo con token en `POST /api/v1/tokens`
- fallback dev solo en `local/testing` con `?user=` o header `X-User`

Archivos agregados/modificados para esto:

- Rutas:
- `routes/api.php`
- Controladores API:
- `app/Http/Controllers/API/RoleController.php`
- `app/Http/Controllers/API/ModuloController.php`
- `app/Http/Controllers/API/MatriculaController.php`
- `app/Http/Controllers/API/DocenteModuloController.php`
- `app/Http/Controllers/API/MenuOpcionController.php`
- `app/Http/Controllers/API/TokenController.php` (reutilizado)
- Helpers de API:
- `app/Http/Controllers/API/Concerns/ApiResponseHelpers.php`
- `app/Http/Controllers/API/Concerns/ResolvesApiUser.php`
- Form Requests:
- `app/Http/Requests/StoreRoleRequest.php`
- `app/Http/Requests/UpdateRoleRequest.php`
- `app/Http/Requests/StoreModuloRequest.php`
- `app/Http/Requests/UpdateModuloRequest.php`
- `app/Http/Requests/StoreMatriculaRequest.php`
- `app/Http/Requests/UpdateMatriculaRequest.php`
- `app/Http/Requests/StoreDocenteModuloRequest.php`
- `app/Http/Requests/UpdateDocenteModuloRequest.php`
- `app/Http/Requests/StoreMenuOpcionRequest.php`
- `app/Http/Requests/UpdateMenuOpcionRequest.php`
- Resources:
- `app/Http/Resources/RoleResource.php`
- `app/Http/Resources/ModuloResource.php`
- `app/Http/Resources/MatriculaResource.php`
- `app/Http/Resources/DocenteModuloResource.php`
- `app/Http/Resources/MenuOpcionResource.php`
- Modelos:
- `app/Models/Role.php`
- `app/Models/Modulo.php`
- `app/Models/Matricula.php`
- `app/Models/DocenteModulo.php`
- `app/Models/MenuOpcion.php`
- `app/Models/User.php` (relaciones nuevas)
- Migraciones:
- `database/migrations/2026_02_08_180001_create_roles_table.php`
- `database/migrations/2026_02_08_180002_create_role_user_table.php`
- `database/migrations/2026_02_08_180003_create_modulos_table.php`
- `database/migrations/2026_02_08_180004_create_docentes_modulos_table.php`
- `database/migrations/2026_02_08_180005_create_matriculas_table.php`
- `database/migrations/2026_02_08_180006_create_menu_opciones_table.php`
- Seeders:
- `database/seeders/MockUsersSeeder.php`
- `database/seeders/MockRolesSeeder.php`
- `database/seeders/MockModulosSeeder.php`
- `database/seeders/MockMenuOpcionesSeeder.php`
- `database/seeders/DatabaseSeeder.php` (registro de los nuevos seeders)

Datos mock sembrados:

- Usuarios:
- `id=1, name=Víctor, email=victor@example.com`
- `id=2, name=Antonio, email=antonio@example.com`
- `id=3, name=Alberto, email=alberto@example.com`
- Roles:
- `docente`, `estudiante`, `administrador`
- Asignaciones:
- Victor -> docente, estudiante, administrador
- Antonio -> estudiante
- Alberto -> docente, administrador
- Menu administrador:
- 9 opciones con sus rutas exactas del mock
- Modulos/impartidos/matriculados:
- Se guardan snapshots en `docentes_modulos` y `matriculas` para devolver exactamente lo que espera frontend.

Nota de consistencia de mocks:

- Los mocks tienen conflicto de nombre/codigo para algunos IDs entre impartidos y matriculados.
- Para no romper el frontend:
- `GET /modulos/impartidos` toma datos de `docentes_modulos`.
- `GET /modulos/matriculados` toma datos de `matriculas`.
- Asi cada endpoint responde exactamente su mock original.

## 1) Preparar backend

```bash
cd /home/alumno/Documentos/laravel/marcapersonalfp
php artisan migrate --seed --force
php artisan serve
```

Base URL recomendada (entorno local con dominio):

```text
http://marcapersonalfp.test
```

Si usas `php artisan serve`, usa:

```text
http://127.0.0.1:8000
```

## 2) Usuarios semilla (mock)

- `victor@example.com` / `password`
- `antonio@example.com` / `password`
- `alberto@example.com` / `password`

## 3) Autenticacion (estado actual)

Actualmente la API esta abierta temporalmente (sin `auth:sanctum`) para pruebas.

Puedes consumir todos los endpoints sin token.

Sanctum sigue disponible por si quieres volver a activarlo.

Generar token:

```bash
curl -X POST http://marcapersonalfp.test/api/v1/tokens \
  -H "Content-Type: application/json" \
  -d '{"email":"victor@example.com","password":"password"}'
```

Respuesta:

```json
{
  "token_type": "Bearer",
  "access_token": "..."
}
```

Usar token:

```bash
-H "Authorization: Bearer TU_TOKEN"
```

## 4) Fallback usuario (si quieres forzar usuario)

Si no usas token en local/testing:

- Query param: `?user=Victor`
- Header: `X-User: Antonio`

Ejemplo:

```bash
curl "http://marcapersonalfp.test/api/v1/roles?user=Victor"
```

## 5) Endpoints que reemplazan mocks (formato exacto frontend)

### GET `/api/v1/roles`

Devuelve exactamente el objeto del mock de roles:

```json
{
  "Victor": { "id": 1, "name": "Víctor", "roles": ["docente", "estudiante", "administrador"] },
  "Antonio": { "id": 2, "name": "Antonio", "roles": ["estudiante"] },
  "Alberto": { "id": 3, "name": "Alberto", "roles": ["docente", "administrador"] }
}
```

### GET `/api/v1/modulos/impartidos`

```json
{
  "Victor": {
    "buscando": false,
    "lista": [
      { "id": 12, "ciclo_formativo_id": 91, "nombre": "Desarrollo web en entorno cliente", "codigo": "0612" },
      { "id": 13, "ciclo_formativo_id": 91, "nombre": "Sistemas Informáticos", "codigo": "0483" }
    ]
  },
  "Alberto": {
    "buscando": false,
    "lista": [
      { "id": 14, "ciclo_formativo_id": 91, "nombre": "Desarrollo web en entorno servidor", "codigo": "0613" }
    ]
  }
}
```

### GET `/api/v1/modulos/matriculados`

```json
{
  "Victor": {
    "buscando": false,
    "lista": [
      { "id": 12, "ciclo_formativo_id": 91, "nombre": "Desarrollo web en entorno cliente", "codigo": "0612" },
      { "id": 13, "ciclo_formativo_id": 91, "nombre": "Desarrollo web en entorno servidor", "codigo": "0613" },
      { "id": 14, "ciclo_formativo_id": 91, "nombre": "Entornos de Desarrollo", "codigo": "0487" }
    ]
  },
  "Antonio": {
    "buscando": false,
    "lista": [
      { "id": 12, "ciclo_formativo_id": 91, "nombre": "Desarrollo web en entorno cliente", "codigo": "0612" }
    ]
  }
}
```

### GET `/api/v1/menu/administrador`

```json
{
  "administrador": [
    { "nombre": "Familias profesionales", "ruta": "/familiasprofesionales" },
    { "nombre": "Ciclos formativos", "ruta": "/ciclosformativos" }
  ]
}
```

## 5.1) Rutas exactas de endpoints (metodo + URL completa)

Prefijo base de API:

```text
/api/v1
```

Base URL recomendada:

```text
http://marcapersonalfp.test/api/v1
```

Endpoints de frontend (reemplazo de mocks):

- `POST http://marcapersonalfp.test/api/v1/tokens`
- `DELETE http://marcapersonalfp.test/api/v1/tokens`
- `GET http://marcapersonalfp.test/api/v1/roles`
- `GET http://marcapersonalfp.test/api/v1/modulos/impartidos`
- `GET http://marcapersonalfp.test/api/v1/modulos/matriculados`
- `GET http://marcapersonalfp.test/api/v1/menu/administrador`

CRUD Roles:

- `GET http://marcapersonalfp.test/api/v1/roles?crud=1`
- `POST http://marcapersonalfp.test/api/v1/roles`
- `GET http://marcapersonalfp.test/api/v1/roles/{role}`
- `PUT http://marcapersonalfp.test/api/v1/roles/{role}`
- `PATCH http://marcapersonalfp.test/api/v1/roles/{role}`
- `DELETE http://marcapersonalfp.test/api/v1/roles/{role}`

CRUD Modulos:

- `GET http://marcapersonalfp.test/api/v1/modulos`
- `POST http://marcapersonalfp.test/api/v1/modulos`
- `GET http://marcapersonalfp.test/api/v1/modulos/{modulo}`
- `PUT http://marcapersonalfp.test/api/v1/modulos/{modulo}`
- `PATCH http://marcapersonalfp.test/api/v1/modulos/{modulo}`
- `DELETE http://marcapersonalfp.test/api/v1/modulos/{modulo}`

CRUD Matriculas:

- `GET http://marcapersonalfp.test/api/v1/matriculas`
- `POST http://marcapersonalfp.test/api/v1/matriculas`
- `GET http://marcapersonalfp.test/api/v1/matriculas/{matricula}`
- `PUT http://marcapersonalfp.test/api/v1/matriculas/{matricula}`
- `PATCH http://marcapersonalfp.test/api/v1/matriculas/{matricula}`
- `DELETE http://marcapersonalfp.test/api/v1/matriculas/{matricula}`

CRUD Docentes-Modulos:

- `GET http://marcapersonalfp.test/api/v1/docentes-modulos`
- `POST http://marcapersonalfp.test/api/v1/docentes-modulos`
- `GET http://marcapersonalfp.test/api/v1/docentes-modulos/{docenteModulo}`
- `PUT http://marcapersonalfp.test/api/v1/docentes-modulos/{docenteModulo}`
- `PATCH http://marcapersonalfp.test/api/v1/docentes-modulos/{docenteModulo}`
- `DELETE http://marcapersonalfp.test/api/v1/docentes-modulos/{docenteModulo}`

CRUD Menu-Opciones:

- `GET http://marcapersonalfp.test/api/v1/menu-opciones`
- `POST http://marcapersonalfp.test/api/v1/menu-opciones`
- `GET http://marcapersonalfp.test/api/v1/menu-opciones/{menuOpcion}`
- `PUT http://marcapersonalfp.test/api/v1/menu-opciones/{menuOpcion}`
- `PATCH http://marcapersonalfp.test/api/v1/menu-opciones/{menuOpcion}`
- `DELETE http://marcapersonalfp.test/api/v1/menu-opciones/{menuOpcion}`

## 6) CRUD REST disponibles (auth:sanctum)

- `apiResource /api/v1/roles` (nota: `GET /roles` normal es endpoint frontend; para listado CRUD usar `GET /roles?crud=1`)
- `apiResource /api/v1/modulos`
- `apiResource /api/v1/matriculas`
- `apiResource /api/v1/docentes-modulos`
- `apiResource /api/v1/menu-opciones`

Formato:

- `index` -> `{ data, meta }` con `paginate(10)`
- `show/store/update` -> `{ data: ... }`
- `destroy` -> `204`

## 7) Ejemplos curl utiles

Roles (frontend):

```bash
curl http://marcapersonalfp.test/api/v1/roles \
  -H "Authorization: Bearer TU_TOKEN"
```

Impartidos:

```bash
curl http://marcapersonalfp.test/api/v1/modulos/impartidos \
  -H "Authorization: Bearer TU_TOKEN"
```

Matriculados:

```bash
curl http://marcapersonalfp.test/api/v1/modulos/matriculados \
  -H "Authorization: Bearer TU_TOKEN"
```

Menu administrador:

```bash
curl http://marcapersonalfp.test/api/v1/menu/administrador
```

CRUD modulos:

```bash
curl http://marcapersonalfp.test/api/v1/modulos \
  -H "Authorization: Bearer TU_TOKEN"

curl -X POST http://marcapersonalfp.test/api/v1/modulos \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"ciclo_formativo_id":92,"nombre":"Modulo API","codigo":"M92"}'
```
