# Student Notepad IA (Laravel)

Proyecto base en Laravel para que estudiantes creen apuntes y un Job en cola procese cada nota con Claude API para organizar y complementar el contenido.

## Requisitos

- PHP 8.2+
- Composer
- Node.js + npm
- MySQL/MariaDB

## Setup rápido

1. Instalar dependencias:

	composer install
	npm install

2. Configurar entorno:

	- Copiar y ajustar `.env`
	- Configurar base de datos MySQL
	- Definir `ANTHROPIC_API_KEY`

3. Migrar base de datos:

	php artisan migrate

4. Compilar assets:

	npm run build

5. Levantar app:

	php artisan serve

## Funcionalidades incluidas

- Autenticación con Breeze (login y registro)
- Módulo de notas por usuario
- Policy para impedir acceso a notas de otros usuarios
- Procesamiento asíncrono de notas con cola `database`
- Integración con Claude API y bitácora en `ai_logs`
