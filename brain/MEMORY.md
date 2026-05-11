# 🧠 Brain - Índice de Documentación

Documentación centralizada del proyecto Student Notepad AI. Accede a los temas rápidamente desde aquí.

## 📌 Inicio rápido

- [Overview del Proyecto](brain/project/overview.md) — Qué es, objetivos, estado actual
- [Setup de Desarrollo](brain/development/setup.md) — Cómo instalar y correr localmente
- [Troubleshooting](brain/development/troubleshooting.md) — Soluciones a problemas comunes

## 📚 Estructura de documentación

### Proyecto (`brain/project/`)

- [overview.md](brain/project/overview.md) — Descripción, objetivos, características
- [team.md](brain/project/team.md) — Miembros del equipo, roles, comunicación
- [roadmap.md](brain/project/roadmap.md) — Fases, sprints, timeline

### Arquitectura (`brain/architecture/`)

- [stack.md](brain/architecture/stack.md) — Tech stack, versiones, dependencias
- [database.md](brain/architecture/database.md) — Esquema ER, tablas, migraciones, relaciones
- [infrastructure.md](brain/architecture/infrastructure.md) — Estructura del proyecto, rutas, configuración
- [api.md](brain/architecture/api.md) — Endpoints, Jobs, Controladores, Policies

### Especificaciones por miembro (`brain/specs/`)

- [ricardo.md](brain/specs/ricardo.md) — Tareas de Ricardo (Setup + Breeze)
- [cesar.md](brain/specs/cesar.md) — Tareas de Cesar (Modelos + Policies)
- [abelardo.md](brain/specs/abelardo.md) — Tareas de Abelardo (Queue + Jobs)
- [avril.md](brain/specs/avril.md) — Tareas de Avril (Vistas + Layout)

### Desarrollo (`brain/development/`)

- [setup.md](brain/development/setup.md) — Instalación paso a paso, comandos útiles
- [workflow.md](brain/development/workflow.md) — Flujo de git, branches, Pull Requests
- [troubleshooting.md](brain/development/troubleshooting.md) — Problemas comunes y soluciones

## 🎯 Acceso por rol

### Si eres **Ricardo** (DevOps/Setup)
→ Lee: [ricardo.md](brain/specs/ricardo.md), [infrastructure.md](brain/architecture/infrastructure.md)

### Si eres **Cesar** (Backend/ORM)
→ Lee: [cesar.md](brain/specs/cesar.md), [database.md](brain/architecture/database.md), [api.md](brain/architecture/api.md)

### Si eres **Abelardo** (Queue/Jobs)
→ Lee: [abelardo.md](brain/specs/abelardo.md), [api.md](brain/architecture/api.md)

### Si eres **Avril** (Frontend)
→ Lee: [avril.md](brain/specs/avril.md), [infrastructure.md](brain/architecture/infrastructure.md)

### Si estás nuevo en el equipo
→ Lee en este orden:
1. [overview.md](brain/project/overview.md)
2. [setup.md](brain/development/setup.md)
3. [stack.md](brain/architecture/stack.md)
4. [database.md](brain/architecture/database.md)

## 🚀 Comandos rápidos

```bash
# Setup
composer install && npm install && npm run build

# Correr servidor
php artisan serve

# Procesar jobs
php artisan queue:work

# Crear usuario de prueba
php artisan tinker
# App\Models\User::create(['name'=>'Test','email'=>'t@t.com','password'=>bcrypt('pass')])

# Ver rutas
php artisan route:list

# Resetear BD
php artisan migrate:fresh
```

## 📝 Información rápida

| Aspecto | Valor |
|--------|-------|
| **Proyecto** | Student Notepad AI |
| **Estado** | 100% Completado (Fase 1) |
| **Equipo** | Ricardo, Cesar, Abelardo, Avril |
| **Repo** | https://github.com/ToledoRicardo/student_notepad_ai |
| **Tech Stack** | Laravel 12, SQLite, Tailwind, Vite |
| **Queue Driver** | Database |
| **Auth** | Laravel Breeze |
| **IA Integration** | Claude API (Anthropic) |

## 🔍 Buscar por tema

### Base de datos
→ [database.md](brain/architecture/database.md)

### Autenticación
→ [infrastructure.md](brain/architecture/infrastructure.md#autenticación-y-authorization)

### Rutas y endpoints
→ [api.md](brain/architecture/api.md)

### Procesamiento de IA
→ [api.md#jobs-asincronicos](brain/architecture/api.md#jobs-asincronicos)

### Vistas y frontend
→ [avril.md](brain/specs/avril.md)

### Configuración de ambiente
→ [setup.md](brain/development/setup.md)

### Flujo de trabajo con git
→ [workflow.md](brain/development/workflow.md)

### Resolver problemas
→ [troubleshooting.md](brain/development/troubleshooting.md)

## ✅ Estado de implementación

| Tarea | Responsable | Status |
|-------|------------|--------|
| Setup + Breeze | Ricardo | ✅ |
| Modelos + Policies | Cesar | ✅ |
| Queue + Jobs | Abelardo | ✅ |
| Vistas + Layout | Avril | ✅ |
| Documentación | Equipo | ✅ |

## 🔗 Enlaces externos

- **GitHub:** https://github.com/ToledoRicardo/student_notepad_ai
- **Laravel Docs:** https://laravel.com/docs
- **Breeze Docs:** https://laravel.com/docs/breeze
- **Tailwind CSS:** https://tailwindcss.com/docs
- **Claude API:** https://console.anthropic.com/

---

**Última actualización:** 5 de mayo de 2026
**Versión:** 1.0 (Documentación Inicial)
