# 📚 Tech Stack

## Backend

| Componente | Tecnología | Versión | Propósito |
|-----------|-----------|---------|----------|
| **Framework** | Laravel | 12.56.0 | Framework web principal |
| **PHP** | PHP | 8.2.12 | Runtime |
| **Autenticación** | Laravel Breeze | 2.4.1 | Auth scaffold con login/registro |
| **ORM** | Eloquent | Built-in | Mapeo de BD con objetos |
| **Migrations** | Laravel Migrations | Built-in | Versionamiento de esquema BD |
| **Queue** | Laravel Queue | database | Procesamiento asincrónico |
| **HTTP Client** | Guzzle | 7.10.0 | Llamadas HTTP a Claude API |
| **Testing** | PHPUnit | 11.5.55 | Tests unitarios |

## Frontend

| Componente | Tecnología | Versión | Propósito |
|-----------|-----------|---------|----------|
| **Bundler** | Vite | 7.3.2 | Build tool moderno |
| **CSS Framework** | Tailwind CSS | Incluido en Breeze | Estilos utilitarios |
| **JavaScript** | Vanilla JS + Alpine.js | Breeze default | Interactividad |
| **Templating** | Blade | Built-in Laravel | Templates PHP |

## Base de Datos

| Componente | Tecnología | Propósito |
|-----------|-----------|----------|
| **Motor** | SQLite | Persistencia de datos |
| **Archivos** | `database/database.sqlite` | BD local |
| **Sesiones** | Database session store | Persistencia de sesiones |
| **Cache** | Database cache | Almacenamiento en caché |

## Integraciones Externas

| Servicio | Uso | Configuración |
|---------|-----|-----------------|
| **Claude API** | Procesamiento de IA de notas | `.env: ANTHROPIC_API_KEY` |
| **GitHub** | Control de versiones | `https://github.com/ToledoRicardo/student_notepad_ai` |

## Dependencias principales

```json
{
  "require": {
    "laravel/framework": "^12.56",
    "laravel/breeze": "^2.4",
    "guzzlehttp/guzzle": "^7.10",
    "nesbot/carbon": "^3.11",
    "illuminate/broadcasting": "^12.0"
  },
  "require-dev": {
    "phpunit/phpunit": "^11.5",
    "laravel/pint": "^1.29",
    "laravel/sail": "^1.57"
  }
}
```

## Versiones de Node.js y npm

- **Node.js:** 16+ (recomendado 18+)
- **npm:** 8+ (incluido con Node.js)

## Variables de entorno principales

```
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=sqlite
QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database
ANTHROPIC_API_KEY=sk-ant-... (necesario para IA)
```

## Diagrama de dependencias

```
Laravel Framework
├── Eloquent ORM
│   └── Database (SQLite)
├── Breeze Authentication
│   └── Database Sessions
├── Laravel Queue
│   └── Database Queue
└── HTTP Client (Guzzle)
    └── Claude API

Frontend
├── Blade Templates
├── Tailwind CSS
└── Vite Build Tool
```
