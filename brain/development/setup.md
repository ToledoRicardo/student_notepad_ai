# 🚀 Setup Ambiente de Desarrollo

## Requisitos previos

Verifica que tengas instalado:

```bash
# PHP 8.2+
php --version

# Composer
composer --version

# Node.js y npm
node --version
npm --version

# Git
git --version
```

Si algo falta, descárgalo de:
- **PHP:** https://www.php.net/
- **Composer:** https://getcomposer.org/
- **Node.js:** https://nodejs.org/ (incluye npm)
- **Git:** https://git-scm.com/

---

## Instalación paso a paso

### 1. Clonar el repositorio

```bash
git clone https://github.com/ToledoRicardo/student_notepad_ai.git
cd student_notepad_ai
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

**Espera:** ~2 minutos

### 3. Crear archivo .env

```bash
# Windows (CMD)
copy .env.example .env

# Windows (Git Bash), Linux, Mac
cp .env.example .env
```

### 4. Generar clave de aplicación

```bash
php artisan key:generate
```

Debería ver: `Application key set successfully.`

### 5. Ejecutar migraciones

```bash
php artisan migrate
```

Debería crear tablas en `database/database.sqlite`

### 6. Instalar dependencias de Node.js

```bash
npm install
```

**Espera:** ~1 minuto

### 7. ⚠️ COMPILAR ASSETS (CRÍTICO)

```bash
npm run build
```

Sin este paso, verá error: `Vite manifest not found`

### 8. Iniciar servidor de desarrollo

En una terminal:
```bash
php artisan serve
```

Debería ver: `Server running on [http://127.0.0.1:8000]`

### 9. (Opcional) Procesar jobs en segundo plano

En otra terminal:
```bash
php artisan queue:work
```

Esto procesará notas con IA cuando se creen.

---

## Acceder a la aplicación

Abre tu navegador en:
- **http://127.0.0.1:8000** o **http://localhost:8000**

---

## Crear cuenta de prueba

### Opción 1: Usar el formulario de registro
1. Click en "Register"
2. Completa los datos
3. Click en "Register"

### Opción 2: Crear usuario desde terminal (Tinker)

```bash
php artisan tinker
```

Luego:
```php
App\Models\User::create([
  'name' => 'Test User',
  'email' => 'test@example.com',
  'password' => bcrypt('password')
]);
exit;
```

---

## Comandos útiles

### Ver rutas disponibles
```bash
php artisan route:list
```

### Limpiar caché
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Ejecutar migraciones en reversa
```bash
php artisan migrate:rollback
```

### Resetear base de datos
```bash
php artisan migrate:refresh    # Rollback + migrate
php artisan migrate:fresh      # Tabla jobs + migrate (más limpio)
```

### Ver logs
```bash
# Último error
tail -f storage/logs/laravel.log

# O en Windows
Get-Content storage/logs/laravel.log -Wait
```

### Entrar a la shell de PHP (Tinker)
```bash
php artisan tinker
```

```php
# Ver usuarios
App\Models\User::all();

# Ver notas
App\Models\Note::all();

# Ver logs de IA
App\Models\AiLog::all();

# Salir
exit;
```

---

## Variables de entorno importantes

Abre `.env` y ajusta si es necesario:

```bash
APP_NAME=Laravel              # Nombre de la app
APP_ENV=local                 # Entorno (local/production)
APP_DEBUG=true                # Mostrar errores en web
APP_URL=http://localhost      # URL local

DB_CONNECTION=sqlite          # Motor de BD
QUEUE_CONNECTION=database     # Procesamiento de jobs
SESSION_DRIVER=database       # Almacenar sesiones en BD

# IMPORTANTE para funcionar con IA:
ANTHROPIC_API_KEY=sk-ant-... # Tu API key de Anthropic
ANTHROPIC_MODEL=claude-3-5-sonnet-latest
```

---

## Desarrollo con hot reload (Vite)

Para que los cambios de CSS/JS se reflejen automáticamente:

En una terminal (separada de `php artisan serve`):
```bash
npm run dev
```

Verá: `Local: http://127.0.0.1:5173`

Ahora los cambios de:
- `resources/css/app.css`
- `resources/js/app.js`
- Blade templates

Se reflejarán inmediatamente sin recargar el navegador.

---

## Estructura de carpetas para desarrollo

```
resources/
├── css/
│   └── app.css              # Estilos globales (Tailwind)
├── js/
│   └── app.js               # JavaScript principal
└── views/
    ├── layouts/             # Layouts reutilizables
    │   ├── app.blade.php    # Layout autenticado
    │   ├── guest.blade.php  # Layout público
    │   └── navigation.blade.php
    ├── auth/                # Vistas de autenticación
    ├── notes/               # Vistas de notas (TU CARPETA)
    │   ├── index.blade.php  # Listar notas
    │   └── create.blade.php # Crear nota
    └── ...

app/
├── Models/                  # Modelos Eloquent (TUS MODELOS)
│   ├── User.php
│   ├── Note.php
│   ├── Subject.php
│   └── AiLog.php
├── Http/
│   └── Controllers/         # Controladores
│       └── NoteController.php
├── Jobs/                    # Jobs asincronicos
│   └── ProcessNoteWithAI.php
└── Policies/                # Authorization
    └── NotePolicy.php

database/
├── migrations/              # Cambios de BD
└── database.sqlite          # BD local
```

---

## Tips de desarrollo

### 1. Usar dd() para debug
```php
dd($variable);  // Die and dump - imprime y para
```

### 2. Ver SQL queries
```bash
DB::listen(function($query) {
    echo $query->sql;
});
```

### 3. Validar formularios rápido
```php
$validated = $request->validate([
    'title' => 'required|string|max:255',
    'content' => 'required|string',
]);
```

### 4. Usar Tinker para testing rápido
```bash
php artisan tinker
```

### 5. Verificar rutas en web.php
Ver `routes/web.php` para entender cómo están mapeadas las URLs

---

## Troubleshooting

Ver [troubleshooting.md](troubleshooting.md) para problemas comunes.

