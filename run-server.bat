@echo off
cd /d "%~dp0"
title Z2M Codes - Local Server
echo Starting Z2M Codes at http://localhost:8080
echo.
echo Open in browser: http://localhost:8080
echo Press Ctrl+C to stop the server.
echo.
c:\xampp\php\php.exe -S localhost:8080 router.php
pause
