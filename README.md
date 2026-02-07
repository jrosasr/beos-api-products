# BeOS API

Bienvenido a la API REST de BeOS. Este proyecto proporciona una interfaz backend robusta para la gestión de productos, divisas y usuarios, con autenticación segura y documentación OpenAPI (Scramble).

## 🚀 Requisitos Previos

- PHP 8.2+
- Composer
- Docker & Docker Compose (Opcional, para PostgreSQL)

## 🛠️ Instalación y Configuración

Sigue estos pasos para levantar el entorno de desarrollo:

### 1. Clonar el repositorio e instalar dependencias

```bash
git clone <URL_DEL_REPOSITORIO>
cd beos-api
composer install
```

### 2. Configurar variables de entorno

Copia el archivo de ejemplo:

```bash
cp .env.example .env
```

#### Opción A: Base de datos SQLite (Por defecto)

El archivo `.env` ya viene preconfigurado para usar SQLite (si copiaste el actualizado). Solo necesitas crear el archivo de base de datos:

```bash
touch database/database.sqlite
```

Asegúrate de que `DB_CONNECTION` en tu `.env` sea `sqlite`.

#### Opción B: Base de datos PostgreSQL con Docker

Si prefieres usar PostgreSQL, puedes levantar el contenedor incluido:

1.  Asegúrate de que el puerto 5439 esté libre o ajusta `FORWARD_DB_PORT` en el `.env`.
2.  Ejecuta:

```bash
docker compose up -d
```

3.  Actualiza tu `.env` con las credenciales de PostgreSQL:

```ini
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5439
DB_DATABASE=beos_api
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Generar Key de la aplicación

```bash
php artisan key:generate
```

### 4. Ejecutar Migraciones y Seeders

Este comando creará las tablas y poblará la base de datos con usuarios y datos de prueba:

```bash
php artisan migrate:fresh --seed
```

### 5. Configurar Tests (Opcional)

Si vas a ejecutar pruebas, asegúrate de configurar el entorno de testing:

```bash
cp .env.example .env.testing
# En .env.testing, asegura DB_CONNECTION=sqlite y borra otras configs de DB
```

Para correr las pruebas:

```bash
php artisan test
```

## 📚 Documentación de API

La documentación interactiva de la API se genera automáticamente con Scramble.

1.  Levanta el servidor:

```bash
php artisan serve
```

2.  Accede a la documentación en:

> **[http://localhost:8000/docs/api](http://localhost:8000/docs/api)**

### Funcionalidades Principales

-   **Autenticación**: Registro (`/api/register`), Login (`/api/login`), Logout y Perfil vía tokens Sanctum.
-   **Divisas**: Listado de monedas soportadas (`/api/currencies`).
-   **Productos**: CRUD completo de productos, incluyendo soporte para múltiples precios por moneda.

## 👤 Usuarios de Prueba

Al ejecutar los seeders, se crea un usuario administrador por defecto:

-   **Email**: `admin@admin.com`
-   **Password**: `password`
