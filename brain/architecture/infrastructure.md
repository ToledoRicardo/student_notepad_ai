# 🏗️ Infraestructura

## Estructura del proyecto

```
student_notepad_ai/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Controladores
│   │   │   └── Auth/          # Breeze auth controllers
│   │   └── Middleware/         # Middleware personalizado
│   ├── Models/                 # Modelos Eloquent
│   │   ├── User.php
│   │   ├── Note.php
│   │   ├── Subject.php
│   │   └── AiLog.php
│   ├── Jobs/                   # Jobs asincronicos
│   │   └── ProcessNoteWithAI.php
│   ├── Policies/               # Authorization policies
│   │   └── NotePolicy.php
│   └── Providers/              # Service providers
├── bootstrap/                  # Bootstrap files
├── config/                     # Archivos de configuración
│   ├── app.php
│   ├── database.php
│   ├── queue.php
│   ├── services.php            # Config para Claude API
│   └── ...
├── database/
│   ├── migrations/             # Migraciones de BD
│   ├── factories/              # Model factories
│   ├── seeders/                # Seeders
│   ├── .gitignore              # Ignorar archivos SQLite temporales
│   └── database.sqlite         # BD SQLite
├── public/
│   ├── index.php              # Entry point
│   └── build/                 # Assets compilados por Vite
├── resources/
│   ├── views/
│   │   ├── layouts/           # Layouts principales
│   │   │   ├── app.blade.php  # Layout autenticado
│   │   │   ├── guest.blade.php # Layout público
│   │   │   └── navigation.blade.php # Navbar
│   │   ├── auth/              # Vistas de autenticación
│   │   ├── notes/             # Vistas de notas
│   │   │   ├── index.blade.php
│   │   │   └── create.blade.php
│   │   └── ...
│   ├── css/                   # Estilos
│   │   └── app.css
│   └── js/                    # JavaScript
│       └── app.js
├── routes/
│   ├── web.php                # Rutas web
│   └── api.php                # Rutas API (si aplica)
├── storage/                   # Storage local
├── tests/                     # Tests
├── vendor/                    # Dependencias Composer (gitignored)
├── node_modules/              # Dependencias npm (gitignored)
├── .env                       # Variables de entorno
├── .env.example               # Template de .env
├── composer.json              # Dependencias PHP
├── package.json               # Dependencias Node.js
├── vite.config.js             # Configuración de Vite
├── tailwind.config.js         # Configuración de Tailwind
├── artisan                    # CLI de Laravel
└── brain/                     # Documentación (este directorio)
```

## Configuración de entorno

### Archivos principales de config

**config/database.php**
- Configuración de conexiones de BD
- Actualmente: SQLite (`database/database.sqlite`)

**config/queue.php**
- Configuración de queue drivers
- Actualmente: database
- Jobs se almacenan en tabla `jobs`

**config/services.php**
- Configuración de servicios externos
- Necesita `services.anthropic.key` para Claude API

**config/app.php**
- Nombre y timezone de la app
- Locale y providers

## Rutas principales

```
GET /                       # Home (redirige a /login si no autenticado)
GET /login                  # Formulario de login
POST /login                 # Procesar login
GET /register               # Formulario de registro
POST /register              # Procesar registro
GET /logout                 # Logout

GET /dashboard              # Dashboard (Breeze default, podría ser /notes)
GET /notes                  # Listar notas del usuario
GET /notes/create           # Formulario para crear nota
POST /notes                 # Guardar nota
GET /notes/{id}             # Ver nota (no implementado aún)
GET /notes/{id}/edit        # Editar nota (no implementado aún)
PATCH /notes/{id}           # Actualizar nota
DELETE /notes/{id}          # Eliminar nota
```

## Queue y Jobs

### Configuración actual

```
QUEUE_CONNECTION=database
```

Los jobs se procesan de forma asincrónica:

**Flujo:**
1. Usuario crea nota con contenido
2. Controller dispara: `ProcessNoteWithAI::dispatch($note->id)`
3. Job se guarda en tabla `jobs`
4. Para procesar: `php artisan queue:work`
5. Job llama a Claude API
6. Resultado se guarda en `notes.ai_content`
7. Log se registra en `ai_logs`

**Procesar jobs manualmente:**
```bash
php artisan queue:work              # Procesa continuamente
php artisan queue:work --once       # Procesa un job
php artisan queue:failed            # Ver jobs fallidos
php artisan queue:retry             # Reintentar fallidos
```

## Autenticación y Authorization

### Autenticación (Breeze)
- Sessions basadas en cookies
- Tabla `users` con email/password
- Middleware `auth` protege rutas autenticadas
- Middleware `guest` redirige autenticados fuera de auth

### Authorization (Policies)

**NotePolicy:**
```php
$user->can('view', $note)    // Solo propietario puede ver
$user->can('update', $note)  // Solo propietario puede editar
$user->can('delete', $note)  // Solo propietario puede eliminar
```

Registrado en `app/Providers/AuthServiceProvider.php`

## Cache y Sessions

### Sessions
```
SESSION_DRIVER=database
```
Se almacenan en tabla `sessions`, persisten entre requests.

### Cache
```
CACHE_STORE=database
```
Usa tabla `cache` para almacenamiento temporal.

## Logs

**Ubicación:** `storage/logs/laravel.log`

**Niveles:** debug, info, notice, warning, error, critical, alert, emergency

**Config:** `config/logging.php`

Actualmente en `APP_DEBUG=true` muestra errores detallados en navegador.

## Variables de entorno críticas

```bash
APP_NAME=Laravel              # Nombre de la app
APP_ENV=local                 # Entorno (local/staging/production)
APP_KEY=base64:...           # Clave de encriptación
APP_DEBUG=true               # Mostrar debug errors
DB_CONNECTION=sqlite         # Driver de BD
QUEUE_CONNECTION=database    # Driver de queue
SESSION_DRIVER=database      # Driver de sessions
ANTHROPIC_API_KEY=sk-ant-... # Clave de Claude API (IMPORTANTE)
```

