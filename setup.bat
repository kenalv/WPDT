@echo off
REM WPDT Setup Script for Windows

echo === WordPress Docker Template (WPDT) Setup ===
echo.

REM Check if .env file exists
if not exist ".env" (
    echo WARNING: Creating .env file from template...
    copy .env.example .env
    echo SUCCESS: .env file created. Please edit it with your Azure MySQL password.
    echo.
)

REM Check if Azure SSL certificate exists
if not exist "DigiCertGlobalRootCA.crt.pem" (
    echo WARNING: Downloading Azure MySQL SSL certificate...
    powershell -Command "Invoke-WebRequest -Uri 'https://cacerts.digicert.com/DigiCertGlobalRootCA.crt.pem' -OutFile 'DigiCertGlobalRootCA.crt.pem'"
    echo SUCCESS: SSL certificate downloaded.
    echo.
)

echo SUCCESS: Setup complete! Choose your startup option:
echo.
echo For Azure MySQL (production):
echo   docker-compose up -d
echo.
echo For Local MySQL (development):
echo   docker-compose --profile local up -d
echo.
echo Access points:
echo   WordPress: http://localhost:8090
echo   phpMyAdmin: http://localhost:8091 (local mode only)
echo.
echo Don't forget to:
echo   1. Edit .env with your Azure MySQL credentials
echo   2. Create your database in Azure MySQL
echo.
pause
