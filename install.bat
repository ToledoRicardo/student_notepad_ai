@echo off
echo ========================================
echo  Instalando Student Notepad AI
echo ========================================
echo.

echo [1/6] Instalando dependencias de PHP...
call composer install
if errorlevel 1 (
    echo ERROR: Fallo composer install
    pause
    exit /b 1
)
echo.

echo [2/6] Creando archivo .env...
if not exist .env (
    copy .env.example .env
    echo Archivo .env creado
) else (
    echo Archivo .env ya existe
)
echo.

echo [3/6] Generando clave de aplicacion...
call php artisan key:generate
echo.

echo [4/6] Ejecutando migraciones de base de datos...
call php artisan migrate
echo.

echo [5/6] Instalando dependencias de Node.js...
call npm install
if errorlevel 1 (
    echo ERROR: Fallo npm install
    pause
    exit /b 1
)
echo.

echo [6/6] Compilando assets del frontend...
call npm run build
if errorlevel 1 (
    echo ERROR: Fallo npm run build
    pause
    exit /b 1
)
echo.

echo ========================================
echo  Instalacion completada exitosamente!
echo ========================================
echo.
echo Para iniciar el servidor ejecuta:
echo   php artisan serve
echo.
echo Luego abre tu navegador en:
echo   http://127.0.0.1:8000
echo.
pause
