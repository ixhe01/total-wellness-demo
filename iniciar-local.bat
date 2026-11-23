@echo off
title Total Wellness - servidor local
cd /d "%~dp0"
where php >nul 2>nul
if errorlevel 1 (
  echo.
  echo  No encontre PHP en tu PC. Instalalo una sola vez con:
  echo     winget install PHP.PHP.8.3
  echo  Cierra y vuelve a abrir esta ventana y da doble clic otra vez.
  echo.
  pause
  exit /b
)
echo.
echo  Sitio:          http://localhost:8000
echo  Panel de citas: http://localhost:8000/admin   (admin@totalwellness.test / demo1234)
echo  Para detener: cierra esta ventana.
echo.
start "" http://localhost:8000
php -S localhost:8000
