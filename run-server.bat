@echo off
echo Starting Z2M Codes server...
echo Open http://localhost:8000 in your browser
echo.
cd /d "%~dp0"
c:\xampp\php\php.exe -S localhost:8000 router.php
pause
