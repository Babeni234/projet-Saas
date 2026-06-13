@echo off
REM Exécute le scheduler Laravel (à appeler toutes les minutes)
cd /d "%~dp0"
php artisan schedule:run 2>&1
