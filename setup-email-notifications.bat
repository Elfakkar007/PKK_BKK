@echo off
REM Setup Email Notifications untuk BKK SMKN 1 Purwosari (Windows)

setlocal enabledelayedexpansion

cls
echo ==========================================
echo BKK Email Notifications Setup Script
echo ==========================================
echo.

REM Check if .env exists
if not exist .env (
    echo.ERROR: .env file not found!
    echo Please copy .env.example to .env first
    pause
    exit /b 1
)

echo [Step 1] Creating queue jobs table...
call php artisan queue:table
echo ^✓ Queue table migration created
echo.

echo [Step 2] Running migrations...
call php artisan migrate
echo ^✓ Database migrations completed
echo.

echo [Step 3] Clearing cache...
call php artisan config:clear
call php artisan cache:clear
echo ^✓ Cache cleared
echo.

echo [Step 4] Email Configuration
echo.
for /f "delims==" %%A in ('findstr "MAIL_MAILER" .env') do (
    echo Current MAIL_MAILER: %%B
)
echo.
echo Available options:
echo   1^) Gmail SMTP
echo   2^) Mailtrap (Testing)
echo   3^) Custom SMTP
echo   4^) Skip (use current config)
echo.

set /p option="Choose option (1-4): "

if "%option%"=="1" (
    echo.
    echo [Gmail Configuration]
    set /p gmail_email="Enter Gmail email: "
    set /p gmail_password="Enter Gmail App Password: "
    
    REM Update .env using PowerShell
    powershell -Command "(Get-Content .env) -replace '^MAIL_MAILER=.*', 'MAIL_MAILER=smtp' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace '^MAIL_HOST=.*', 'MAIL_HOST=smtp.gmail.com' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace '^MAIL_PORT=.*', 'MAIL_PORT=587' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace '^MAIL_USERNAME=.*', 'MAIL_USERNAME=%gmail_email%' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace '^MAIL_PASSWORD=.*', 'MAIL_PASSWORD=%gmail_password%' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace '^MAIL_ENCRYPTION=.*', 'MAIL_ENCRYPTION=tls' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace '^MAIL_FROM_ADDRESS=.*', 'MAIL_FROM_ADDRESS=%gmail_email%' | Set-Content .env"
    
    echo ^✓ Gmail configuration saved
) else if "%option%"=="2" (
    echo.
    echo [Mailtrap Configuration]
    set /p mailtrap_user="Enter Mailtrap username: "
    set /p mailtrap_pass="Enter Mailtrap password: "
    
    REM Update .env
    powershell -Command "(Get-Content .env) -replace '^MAIL_MAILER=.*', 'MAIL_MAILER=smtp' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace '^MAIL_HOST=.*', 'MAIL_HOST=smtp.mailtrap.io' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace '^MAIL_PORT=.*', 'MAIL_PORT=465' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace '^MAIL_USERNAME=.*', 'MAIL_USERNAME=%mailtrap_user%' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace '^MAIL_PASSWORD=.*', 'MAIL_PASSWORD=%mailtrap_pass%' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace '^MAIL_ENCRYPTION=.*', 'MAIL_ENCRYPTION=ssl' | Set-Content .env"
    
    echo ^✓ Mailtrap configuration saved
) else if "%option%"=="3" (
    echo.
    echo [Custom SMTP Configuration]
    set /p mail_host="Enter MAIL_HOST: "
    set /p mail_port="Enter MAIL_PORT: "
    set /p mail_user="Enter MAIL_USERNAME: "
    set /p mail_pass="Enter MAIL_PASSWORD: "
    
    REM Update .env
    powershell -Command "(Get-Content .env) -replace '^MAIL_MAILER=.*', 'MAIL_MAILER=smtp' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace '^MAIL_HOST=.*', 'MAIL_HOST=%mail_host%' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace '^MAIL_PORT=.*', 'MAIL_PORT=%mail_port%' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace '^MAIL_USERNAME=.*', 'MAIL_USERNAME=%mail_user%' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace '^MAIL_PASSWORD=.*', 'MAIL_PASSWORD=%mail_pass%' | Set-Content .env"
    
    echo ^✓ Custom SMTP configuration saved
) else (
    echo Skipping email configuration
)

echo.
echo [Clearing cache again]
call php artisan config:clear
call php artisan cache:clear
echo ^✓ Cache cleared
echo.

echo ==========================================
echo ^✓ Setup Complete!
echo ==========================================
echo.
echo [Next steps]
echo 1. Verify SMTP credentials in .env file
echo 2. Run: php artisan queue:work
echo    (Keep this running in a separate terminal/PowerShell)
echo 3. Test email notifications using tinker:
echo    php artisan tinker
echo.
echo For production, see QUEUE_EMAIL_SETUP.md
echo.
pause
