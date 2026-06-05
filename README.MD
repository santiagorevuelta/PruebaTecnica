# Sistema de Biblioteca

Aplicacion web para gestion de libros, autores, usuarios y prestamos. Construida con Laravel 13, PostgreSQL (Neon) y una seccion frontend en Blade con JavaScript vanilla.

---

## Requisitos

- PHP 8.3 o superior
- Composer
- Node.js 18 o superior
- Acceso a la base de datos PostgreSQL en Neon (credenciales en `.env`)

---

## Instalacion

```bash
# 1. Instalar dependencias PHP
composer install

# 2. Copiar variables de entorno
cp .env.example .env

# 3. Generar clave de la aplicacion
php artisan key:generate

# 4. Instalar dependencias Node y compilar assets
npm install
npm run build

# 5. Correr migraciones
php artisan migrate

# 6. Poblar la base de datos con datos de prueba
php artisan db:seed
```

---

## Correr en desarrollo

```bash
composer run dev
```

Esto levanta en paralelo el servidor PHP, la cola de trabajos, los logs y Vite.

O si se prefiere solo el servidor:

```bash
php artisan serve
```

La app queda disponible en `http://localhost:8000`.

---

## Acceso

| Recurso | URL |
|---------|-----|
| Dashboard biblioteca | `http://localhost:8000` |
| Lista de libros | `http://localhost:8000/biblioteca/libros` |
| Prestamos | `http://localhost:8000/biblioteca/prestamos` |
| API base | `http://localhost:8000/api` |

**Usuario de la app (login API y dashboard):**

| Email | Contrasena |
|-------|-----------|
| `admin@biblioteca.com` | `password123` |

---

## Estructura del proyecto

```
app/
  Http/
    Controllers/
      AuthApiController.php      Control de login y logout con Sanctum
      AutorController.php        CRUD autores
      LibroController.php        CRUD libros con soft delete
      PrestamoController.php     Gestion de prestamos
    Requests/
      StoreLibroRequest.php
      UpdateLibroRequest.php
      StorePrestamoRequest.php
    Resources/
      AutorResource.php
      LibroResource.php
      PrestamoResource.php
  Models/
    Autor.php
    AutorLibro.php               Pivot personalizado con campo orden_autor
    Libro.php
    Prestamo.php
    Usuario.php
  Traits/
    ApiResponse.php              Respuestas JSON estandarizadas
  Console/Commands/
    MarcarPrestamosVencidos.php
    ReporteBiblioteca.php

database/
  migrations/                    Migraciones de todas las tablas
  seeders/                       Datos de prueba: 10 autores, 20 libros, 15 usuarios, 10 prestamos

resources/views/biblioteca/
  layout.blade.php               Layout con nav y login via token
  dashboard.blade.php            Estadisticas generales
  libros.blade.php               Lista con busqueda en tiempo real y CRUD
  prestamos.blade.php            Lista y formulario de nuevo prestamo

routes/
  api.php                        15 endpoints REST protegidos con Sanctum
  web.php                        Rutas Blade
```

---

## API REST

Todos los endpoints requieren el header `Authorization: Bearer <token>`.

Obtener token:

```bash
POST /api/login
{ "email": "admin@biblioteca.com", "password": "password123" }
```

### Endpoints disponibles

| Metodo | Ruta | Descripcion |
|--------|------|-------------|
| POST | `/api/login` | Obtener token |
| POST | `/api/logout` | Revocar token |
| GET | `/api/libros` | Listar libros (filtros: titulo, anio, autor_id, solo_disponibles) |
| GET | `/api/libros/{id}` | Ver libro con autores |
| POST | `/api/libros` | Crear libro |
| PUT | `/api/libros/{id}` | Actualizar libro |
| DELETE | `/api/libros/{id}` | Eliminar libro (soft delete) |
| GET | `/api/prestamos` | Listar prestamos (filtros: estado, usuario_id, libro_id) |
| POST | `/api/prestamos` | Crear prestamo |
| PUT | `/api/prestamos/{id}/devolver` | Marcar como devuelto |
| GET | `/api/autores` | Listar autores |
| GET | `/api/autores/{id}` | Ver autor con libros |
| DELETE | `/api/autores/{id}` | Eliminar autor (falla si tiene libros) |
| GET | `/api/estadisticas` | Contadores para el dashboard |

Formato de respuesta:

```json
{
  "success": true,
  "message": "...",
  "data": { },
  "meta": { "current_page": 1, "last_page": 2, "per_page": 10, "total": 20 }
}
```

---

## Comandos Artisan

```bash
# Marcar prestamos activos con mas de 15 dias como vencidos
php artisan biblioteca:marcar-vencidos

# Ver reporte: libros mas prestados, usuarios con vencidos y libros sin stock
php artisan biblioteca:reporte
```

---

## Base de datos

Esquema `biblioteca` en PostgreSQL (Neon).

| Tabla | Descripcion |
|-------|-------------|
| `autores` | Autores con datos biograficos |
| `libros` | Libros con soft delete |
| `autores_libros` | Pivot con campo `orden_autor` |
| `usuarios` | Socios de la biblioteca |
| `prestamos` | Registro de prestamos con estado |
| `users` | Usuarios del sistema (login) |
| `personal_access_tokens` | Tokens Sanctum |

---

## Testing

Importar `biblioteca-postman-collection.json` en Postman. La coleccion incluye variables de entorno, tests automatizados y casos de error para los 15 endpoints.
