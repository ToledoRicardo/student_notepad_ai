# 💼 Workflow de Desarrollo

## Filosofía del equipo

- **Commits pequeños y descriptivos:** Cada commit debe hacer una cosa
- **Una rama por feature:** No trabajar directamente en master
- **Pull Requests para revisar:** Antes de mergear, otro miembro revisa
- **Tests antes de mergear:** Si existen tests, deben pasar

---

## Flujo de trabajo estándar

### Paso 1: Crear rama para tu feature

```bash
git checkout master                              # Asegúrate estar en master
git pull origin master                           # Traer cambios del remoto
git checkout -b feature/nombre-de-tu-feature    # Crear rama nueva
```

**Nombres de rama recomendados:**
```
feature/nueva-funcionalidad
fix/bug-que-arreglas
docs/mejora-documentacion
refactor/reorganizar-codigo
```

### Paso 2: Hacer cambios locales

Edita los archivos necesarios:
```bash
# Ver cambios
git status

# Ver diferencias
git diff

# Agregar archivos
git add archivo.php archivo2.php

# O agregar todo
git add .
```

### Paso 3: Hacer commits pequeños

```bash
git commit -m "Descripción clara de qué cambió"
```

**Formato de mensaje recomendado:**
```
tipo: descripción breve

Descripción más detallada si es necesario.
- Punto 1
- Punto 2

Fixes #123 (si cierra un issue)
```

**Tipos de commit:**
- `feat:` Nueva funcionalidad
- `fix:` Arreglo de bug
- `docs:` Cambios en documentación
- `refactor:` Reorganizar código (sin cambiar funcionalidad)
- `test:` Agregar o modificar tests
- `chore:` Tareas administrativas

**Ejemplos:**
```bash
git commit -m "feat: agregar vista de detalle de nota"
git commit -m "fix: resolver policy de autorización de notas"
git commit -m "docs: actualizar guía de setup"
```

### Paso 4: Subir tu rama al remoto

```bash
git push -u origin feature/nombre-de-tu-feature
```

La primera vez use `-u` para configurar tracking.

### Paso 5: Crear Pull Request en GitHub

1. Ve a: https://github.com/ToledoRicardo/student_notepad_ai
2. Haz click en "Compare & pull request"
3. Completa el formulario:
   - Título: Descripción clara (ej: "Add note detail view")
   - Descripción: Qué hace, por qué, cómo testearlo
4. Asigna reviewers (otros miembros del equipo)
5. Click en "Create pull request"

**Plantilla de PR sugerida:**
```markdown
## 📝 Descripción
Qué hace este PR

## 🎯 Objetivo
Por qué es necesario

## 🧪 Cómo testearlo
1. Paso 1
2. Paso 2

## 📋 Checklist
- [ ] Tests pasan (si aplica)
- [ ] Código revisado localmente
- [ ] Documentación actualizada
- [ ] Sin conflictos con master
```

### Paso 6: Revisar comentarios

Otros miembros pueden comentar o pedir cambios. Si hay cambios solicitados:

```bash
# Hacer los cambios
git add .
git commit -m "refactor: cambios solicitados en review"
git push
```

El PR se actualiza automáticamente.

### Paso 7: Mergear a master

Cuando un miembro aprueba, el autor (u otro) mergea:

**Opción A: Mergear desde GitHub UI**
1. Click en "Merge pull request"
2. Click en "Confirm merge"
3. Click en "Delete branch" (para limpiar)

**Opción B: Mergear desde terminal**
```bash
git checkout master
git pull origin master
git merge feature/nombre-de-tu-feature
git push origin master
git branch -d feature/nombre-de-tu-feature  # Eliminar rama local
git push origin -d feature/nombre-de-tu-feature  # Eliminar rama remota
```

### Paso 8: Actualizar tu rama local

```bash
git checkout master
git pull origin master
```

Ahora tu rama local tiene los cambios de todos.

---

## Resolución de conflictos

Si hay conflictos al mergear:

```bash
# Ver conflictos
git status

# Abrir archivo con conflicto
# Verás algo como:
# <<<<<<< HEAD
#   Tu código
# =======
#   Código del otro
# >>>>>>> rama-otra-persona

# Editar el archivo para resolver
# Dejar el código correcto (eliminar marcadores <<<, ===, >>>)

# Completar merge
git add .
git commit -m "merge: resolver conflictos con master"
git push
```

---

## Modelo de branches

```
master (producción)
  ↑
  │ (Pull Request)
  │
feature/nota-detail (en desarrollo)
  ↑
  │
[Tu computadora]
```

Nunca trabajar directamente en `master`.

---

## Reglas importantes

### ✅ HACER

- ✅ Crear rama nueva para cada tarea
- ✅ Commits pequeños y descriptivos
- ✅ Hacer git pull antes de empezar
- ✅ Abrir PR para revisar cambios
- ✅ Esperar aprobación antes de mergear
- ✅ Eliminar rama cuando ya está en master

### ❌ NO HACER

- ❌ Trabajar directamente en master
- ❌ Commits con mensajes genéricos ("update", "fix")
- ❌ Mergear tu propio PR sin revisor
- ❌ Pushear cambios incompletos
- ❌ Git push --force (puede perder trabajo)
- ❌ Commits con código no probado

---

## Ejemplo de workflow completo

```bash
# 1. Crear rama
git checkout master
git pull origin master
git checkout -b feature/edit-note

# 2. Hacer cambios
# ... editar archivos ...

# 3. Verificar y commitear
git status
git diff
git add app/Http/Controllers/NoteController.php resources/views/notes/edit.blade.php
git commit -m "feat: agregar vista para editar notas"

# 4. Subir
git push -u origin feature/edit-note

# 5. Crear PR en GitHub
# (en navegador)

# 6. Reviewer comenta "Necesitas agregar validación"

# 7. Hacer cambios
git add app/Http/Requests/UpdateNoteRequest.php
git commit -m "feat: agregar validación para update de nota"
git push

# 8. Reviewer aprueba

# 9. Mergear (desde GitHub UI)

# 10. Actualizar local
git checkout master
git pull origin master
```

---

## Tips útiles

### Ver todas tus ramas
```bash
git branch          # Local
git branch -r       # Remoto
git branch -a       # Todas
```

### Cambiar a rama existente
```bash
git checkout nombre-rama
```

### Ver commits de tu rama
```bash
git log origin/master..HEAD   # Commits en tu rama que no están en master
```

### Stash (guardar trabajo temporalmente)
```bash
git stash           # Guardar cambios sin commitear
git stash pop       # Recuperar cambios
```

### Deshacer último commit (antes de pushear)
```bash
git reset --soft HEAD~1   # Deshacer, guardar cambios
git reset --hard HEAD~1   # Deshacer todo
```

---

## Comunicación en el equipo

**Canales:**
- GitHub Issues: Para tareas/bugs a largo plazo
- Pull Request comments: Para revisar código
- Chat grupal: Para dudas rápidas

**Mencionar a alguien:**
```
@ricardo Necesito que revises esto
```

