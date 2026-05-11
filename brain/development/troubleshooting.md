# 🔧 Troubleshooting - Problemas comunes

## Problemas de instalación

### Error: "No application encryption key has been specified"

**Síntoma:**
```
No application encryption key has been specified.
```

**Solución:**
```bash
php artisan key:generate
```

---

### Error: "Class not found"

**Síntoma:**
```
Class App\Models\Note not found
Class 'App\Http\Controllers\NoteController' not found
```

**Soluciones:**

```bash
# Regenerar autoloader
composer dump-autoload

# O más drástico
composer install --no-dev
```

---

### Error: "Vite manifest not found"

**Síntoma:**
```
Illuminate\Foundation\ViteManifestNotFoundException
Vite manifest not found at: public/build/manifest.json
```

**Solución:** ⚠️ **MÁS COMÚN**

```bash
npm install
npm run build
```

---

## Problemas de base de datos

### Error: "Database file not found"

**Síntoma:**
```
SQLSTATE[HY000]: General error: unable to open database file
```

**Soluciones:**

```bash
# Crear BD nuevamente
php artisan migrate

# O resetear completamente
php artisan migrate:fresh
```

---

### Error: "Table does not exist"

**Síntoma:**
```
SQLSTATE[HY000]: General error: 1030 Got error: 1 from storage engine
```

**Soluciones:**

```bash
# Ejecutar migraciones que faltaban
php artisan migrate

# Verificar estado
php artisan migrate:status

# Si algo falla, resetear
php artisan migrate:fresh --seed
```

---

### Error: "Foreign key constraint failed"

**Síntoma:**
```
INTEGRITY CONSTRAINT VIOLATION: 1452
```

**Causa:** Intentas crear una nota con `subject_id` que no existe

**Solución:**

```php
// En create()
$subjects = auth()->user()->subjects;
// Asegurar que existan subjects para el usuario

// O permitir subject_id nullable
```

---

## Problemas de autenticación

### Usuario no puede loguearse

**Soluciones:**

```bash
# Verificar contraseña es hash
php artisan tinker

App\Models\User::first();
# Debería mostrar password como hash (comienza con $2y$)

# Si está en texto plano, actualizar:
App\Models\User::first()->update(['password' => bcrypt('newpassword')]);
exit;
```

---

### Error de CSRF token

**Síntoma:**
```
CSRF token mismatch
```

**Soluciones:**

1. Asegurar que el formulario tiene `@csrf`:
```blade
<form method="POST" action="...">
    @csrf
    ...
</form>
```

2. Limpiar sesiones:
```bash
php artisan cache:clear
```

---

## Problemas de queue y jobs

### Jobs no se procesan

**Soluciones:**

1. Verificar que queue worker está corriendo:
```bash
php artisan queue:work
```

2. Si no ves cambios, ejecutar una sola vez:
```bash
php artisan queue:work --once
```

3. Ver jobs fallidos:
```bash
php artisan queue:failed
```

4. Reintentar jobs fallidos:
```bash
php artisan queue:retry all
```

---

### Error: "Column 'payload' does not have a default value"

**Solución:**

```bash
php artisan migrate  # Ejecutar migraciones pendientes
```

---

## Problemas de API / Claude

### Error: "ANTHROPIC_API_KEY no configurada"

**Síntoma:**
```
En ai_logs: error_message = "ANTHROPIC_API_KEY no configurada."
```

**Solución:**

1. Obtener API key en: https://console.anthropic.com/
2. Agregar a `.env`:
```
ANTHROPIC_API_KEY=sk-ant-v7-...
```
3. Limpiar config cache:
```bash
php artisan config:clear
```

---

### Error: "Error HTTP al consumir Claude API"

**Síntoma:**
```
En ai_logs: error_message = "Error HTTP al consumir Claude API."
status = "failed"
```

**Posibles causas:**
- API key inválida
- Límite de requests excedido
- Problema de conexión

**Soluciones:**

```bash
# Verificar API key
php artisan tinker
config('services.anthropic.key')  # Debería mostrar sk-ant-...

# Ver error completo en logs
tail -f storage/logs/laravel.log

# Reintentar job
php artisan queue:retry all
```

---

## Problemas de servidor

### Error: "Address already in use"

**Síntoma:**
```
Address already in use [127.0.0.1:8000]
```

**Soluciones:**

```bash
# Usar otro puerto
php artisan serve --port=8001

# O encontrar proceso en puerto 8000 y killarlo
# En Windows:
netstat -ano | findstr :8000
taskkill /PID <PID> /F

# En Linux/Mac:
lsof -i :8000
kill -9 <PID>
```

---

### Error: "PHP executable not found"

**Síntoma:**
```
'php' is not recognized as an internal or external command
```

**Soluciones:**

1. Verificar PHP está instalado:
```bash
php --version
```

2. Si no, descargar de: https://www.php.net/

3. Agregar PHP al PATH (Windows):
   - Buscar "Path" en variables de entorno
   - Agregar ubicación de PHP (ej: `C:\xampp\php`)
   - Reiniciar terminal

---

## Problemas de Node/npm

### Error: "npm: command not found"

**Solución:**

Descargar Node.js de: https://nodejs.org/

Node.js incluye npm automáticamente.

---

### Error: "node_modules not found"

**Solución:**

```bash
npm install
```

---

### Assets no se actualizan

**Solución:**

```bash
# Limpiar build
rm -rf public/build

# Recompilar
npm run build

# O en modo desarrollo con hot reload
npm run dev
```

---

## Problemas de permisos

### Error: "Permission denied" (Linux/Mac)

**Solución:**

```bash
chmod -R 755 storage bootstrap/cache
```

---

### Error al escribir en storage

**Solución:**

```bash
chmod -R 777 storage bootstrap/cache
```

---

## Problemas de git

### "Your branch is ahead of origin/master"

**Solución:**

```bash
git push  # Subir cambios
```

---

### "Merge conflict"

**Solución:**

Ver [workflow.md#resolución-de-conflictos](workflow.md#resolución-de-conflictos)

---

### Cambios sin trackear molestos

**Solución:**

```bash
# Ver qué está sin trackear
git status

# Descartar cambios locales (⚠️ CUIDADO)
git checkout .

# O guardar temporalmente
git stash
```

---

## Debugging general

### Ver todos los errores

**Activar debug en .env:**
```
APP_DEBUG=true
```

Ahora los errores muestran stack trace completo.

---

### Ver logs en tiempo real

```bash
tail -f storage/logs/laravel.log
```

---

### Entrar a shell SQL

```bash
sqlite3 database/database.sqlite
# Luego ejecutar queries SQL
```

---

### Ver variables de entorno cargadas

```bash
php artisan config:show
```

---

## Resetear todo (última opción)

```bash
# Limpiar todo
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Resetear BD
php artisan migrate:fresh

# Recompilar assets
npm run build

# Reiniciar servidor
# (Ctrl+C y volver a ejecutar php artisan serve)
```

---

## Aún no resuelve?

1. **Ver logs:** `storage/logs/laravel.log`
2. **Buscar en Google:** "Laravel" + tu error
3. **Preguntar al equipo:** En el chat grupal
4. **Documentación oficial:**
   - Laravel: https://laravel.com/docs
   - Breeze: https://laravel.com/docs/breeze
   - Tailwind: https://tailwindcss.com/docs

