@echo off
setlocal

set SERVER_PORT=80
cd /d C:\xampp\htdocs\IM

if not exist writable\logs mkdir writable\logs

echo [%date% %time%] Starting Entra user sync... >> writable\logs\entra_sync_scheduler.log
C:\xampp\php\php.exe spark entra:sync-users >> writable\logs\entra_sync_scheduler.log 2>&1
echo [%date% %time%] Finished Entra user sync. ExitCode=%ERRORLEVEL% >> writable\logs\entra_sync_scheduler.log
echo. >> writable\logs\entra_sync_scheduler.log

endlocal
