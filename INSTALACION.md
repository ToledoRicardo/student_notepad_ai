# Pasos de Instalación - Student Notepad AI

Sigue estos pasos después de clonar el repositorio:

## Requisitos previos
- PHP 8.2 o superior
- Composer
- Node.js y npm (para el frontend)

## Pasos de instalación

### 1. Clonar el repositorio
```bash
git clone <url-del-repositorio>
cd student_notepad_ai
```

### 2. Instalar dependencias de PHP
```bash
composer install
```

### 3. Configurar el archivo de entorno
```bash
# En Windows (CMD)
copy .env.example .env

# En Windows (Git Bash) o Linux/Mac
cp .env.example .env
```

### 4. Generar la clave de aplicación
```bash
php artisan key:generate
```

### 5. Ejecutar las migraciones
El proyecto usa SQLite por defecto, no necesitas configurar MySQL.
```bash
php artisan migrate
```

Si quieres usar MySQL en lugar de SQLite, abre el archivo `.env` y configura:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=student_notepad
DB_USERNAME=root
DB_PASSWORD=tu_password
```

### 6. Instalar dependencias de Node.js ⚠️ IMPORTANTE
```bash
npm install
```

### 7. ⚠️ COMPILAR LOS ASSETS DEL FRONTEND (CRÍTICO)
**SIN ESTE PASO LA APLICACIÓN NO FUNCIONARÁ**
```bash
npm run build
```

Si vas a estar desarrollando y quieres que los cambios se reflejen automáticamente, en una terminal separada ejecuta:
```bash
npm run dev
```

### 8. Iniciar el servidor de desarrollo
```bash
php artisan serve
```

## Acceder a la aplicación
- Abre tu navegador en: http://127.0.0.1:8000
- O http://localhost:8000

## Comandos útiles

### Detener el servidor
Presiona `Ctrl + C` en la terminal donde está corriendo

### Limpiar caché
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Ver rutas disponibles
```bash
php artisan route:list
```

## Problemas comunes

### ⚠️ Error: "Vite manifest not found"
Este es el error MÁS COMÚN. Significa que no compilaste los assets del frontend.
```bash
npm install
npm run build
```

### Error: "No application encryption key has been specified"
```bash
php artisan key:generate
```

### Error: "Class not found"
```bash
composer dump-autoload
```

### Error al instalar composer
Verifica que tengas Composer instalado: https://getcomposer.org/

### Error al instalar npm
Verifica que tengas Node.js instalado: https://nodejs.org/

### Error con permisos (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
```
