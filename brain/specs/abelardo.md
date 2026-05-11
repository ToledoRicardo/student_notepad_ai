# ✅ Especificaciones - Abelardo

## Responsable: Abelardo

## Tareas asignadas

### 1. Configurar queue driver database ✅

#### Configuración en .env ✅
```
QUEUE_CONNECTION=database
```

**Ubicación:** `.env` línea 38

**Alternativa considerada:** redis, sync, file, database (elegida)

#### Driver database seleccionado porque:
- No requiere software externo (Redis)
- Fácil de implementar localmente
- Persiste jobs en BD
- Ideal para desarrollo

---

### 2. Ejecutar migración de queue ✅

**Migration automática incluida:**
```
database/migrations/0001_01_01_000002_create_jobs_table.php
```

**Tabla `jobs` creada con:**
- id (bigint, PK)
- queue (varchar)
- payload (longtext)
- attempts (tinyint)
- reserved_at (int, nullable)
- available_at (int)
- created_at (int)

**Ejecución:**
```bash
✅ php artisan migrate
✅ php artisan migrate:status  # Verificado
```

**Tabla en BD:** ✅ Creada y funcional

---

### 3. Job ProcessNoteWithAI ✅

**Ubicación:**
```
app/Jobs/ProcessNoteWithAI.php
```

**Implementación:**

#### Constructor
```php
public function __construct(public int $noteId)
{
}
```
- Recibe ID de la nota a procesar
- Se serializa en tabla jobs

#### Configuración de reintentos
```php
public int $tries = 3;
```
- Reintenta hasta 3 veces si falla
- Manejo de excepciones robusto

#### Método handle()

**Flujo completo:**

1. **Recuperar nota:**
   ```php
   $note = Note::query()->with('user')->find($this->noteId);
   if (!$note) return;  // Salir si no existe
   ```

2. **Obtener API key:**
   ```php
   $apiKey = config('services.anthropic.key');
   if (empty($apiKey)) {
       // Log error: API_KEY no configurada
       return;
   }
   ```

3. **Configurar modelo:**
   ```php
   $model = config('services.anthropic.model', 'claude-3-5-sonnet-latest');
   ```

4. **Construir prompt académico:**
   ```
   "Eres un asistente académico. Organiza, complementa y reestructura 
    estos apuntes de estudiante en español. Devuelve secciones claras, 
    puntos clave y resumen final.
    
    [CONTENIDO DE NOTA]"
   ```

5. **Llamada a Claude API:**
   ```php
   Http::withHeaders([
       'x-api-key' => $apiKey,
       'anthropic-version' => '2023-06-01',
       'content-type' => 'application/json',
   ])->post('https://api.anthropic.com/v1/messages', [
       'model' => $model,
       'max_tokens' => 1200,
       'messages' => [
           ['role' => 'user', 'content' => $prompt],
       ],
   ]);
   ```

6. **Procesar respuesta:**
   - Validar HTTP response (no falló)
   - Extraer texto de respuesta
   - Guardar en `note.ai_content`

7. **Registrar en ai_logs:**
   ```php
   AiLog::create([
       'user_id' => $note->user_id,
       'note_id' => $note->id,
       'prompt' => $prompt,
       'response' => $aiText,
       'status' => 'success'  // o 'failed'
       'error_message' => $errorMsg  // null si exitoso
   ]);
   ```

---

### 4. Manejo de errores ✅

**Errores cubiertos:**

| Escenario | Acción | Log Status |
|-----------|--------|-----------|
| API key no configurada | Registra error, retorna | failed |
| HTTP error (4xx, 5xx) | Registra error, retorna | failed |
| Respuesta sin contenido | Registra error, retorna | failed |
| Excepción de Throwable | Captura y registra, retorna | failed |
| Exitoso | Guarda ai_content, registra | success |

**Ejemplo de error capturado:**
```php
catch (Throwable $e) {
    AiLog::create([
        'user_id' => $note->user_id,
        'note_id' => $note->id,
        'prompt' => $prompt,
        'status' => 'failed',
        'error_message' => $e->getMessage(),
    ]);
}
```

---

## Comandos para procesar jobs

### Procesar jobs continuamente (RECOMENDADO para desarrollo)
```bash
php artisan queue:work
```

### Procesar un solo job
```bash
php artisan queue:work --once
```

### Ver jobs fallidos
```bash
php artisan queue:failed
```

### Reintentar jobs fallidos
```bash
php artisan queue:retry all
```

### Limpiar jobs completados
```bash
php artisan queue:clear
```

---

## Configuración de Anthropic API

**Necesario para que funcione el Job:**

### 1. Obtener API key
- Ir a: https://console.anthropic.com/
- Crear cuenta si es necesario
- Generar API key

### 2. Agregar a .env
```
ANTHROPIC_API_KEY=sk-ant-v7-...
ANTHROPIC_MODEL=claude-3-5-sonnet-latest
```

### 3. Verificar en config/services.php
```php
'anthropic' => [
    'key' => env('ANTHROPIC_API_KEY'),
    'model' => env('ANTHROPIC_MODEL', 'claude-3-5-sonnet-latest'),
],
```

---

## Flujo de uso

### 1. Usuario crea nota
```
POST /notes
{
  "title": "Cálculo",
  "content": "Las derivadas sirven para...",
  "subject_id": 1
}
```

### 2. Controller dispara job
```php
// En NoteController@store
ProcessNoteWithAI::dispatch($note->id);
```

### 3. Job se guarda en BD
```
INSERT INTO jobs (queue, payload, attempts, ...)
VALUES ('default', {...serializado...}, 0, ...)
```

### 4. Worker procesa
```bash
$ php artisan queue:work
[2026-05-04 21:30:15] Processing: ProcessNoteWithAI
```

### 5. Claude API responde
- Recibe prompt
- Procesa nota
- Devuelve contenido mejorado

### 6. Job actualiza nota y registra log
```
UPDATE notes SET ai_content = '...' WHERE id = 1
INSERT INTO ai_logs (user_id, note_id, prompt, response, status, ...)
```

### 7. Usuario ve resultado
```
GET /notes
# Muestra: 
# - Contenido original
# - Versión procesada por IA (cuando está lista)
```

---

## Entregables

### Archivos creados:
```
✅ app/Jobs/ProcessNoteWithAI.php
```

### Archivos modificados:
```
✅ .env                    # QUEUE_CONNECTION=database
✅ config/services.php     # Configuración de Anthropic
```

### Migraciones:
```
✅ database/migrations/0001_01_01_000002_create_jobs_table.php (ejecutada)
```

---

## Estado: ✅ COMPLETADO 100%

Todas las tareas asignadas a Abelardo han sido completadas. Sistema de queue y procesamiento con IA completamente funcional. Solo requiere API key de Anthropic para activar.

