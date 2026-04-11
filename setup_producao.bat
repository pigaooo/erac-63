REM Como executar este arquivo .bat:
REM - No Windows Explorer: dê duplo-clique no arquivo.
REM - Ou no PowerShell/CMD: abra uma janela, navegue até a pasta do projeto e execute:
REM     .\setup_producao.bat
REM Observação: certifique-se de que `composer`, `npm` e `php` estejam no PATH.
REM Nota: o comando Laravel correto é migrate:fresh (não "migration:fresh").

@echo off
SETLOCAL
cd /d %~dp0
echo =============================================
echo Setup do site para PRODUCAO - Iniciando
echo =============================================
echo.

echo [1/4] Composer install (sem dependências de desenvolvimento)...
call composer update
call composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
if %ERRORLEVEL% NEQ 0 (
  echo ERRO: composer install falhou.
  pause
  exit /b %ERRORLEVEL%
)

echo.
echo [2/4] npm ci...
call npm ci
if %ERRORLEVEL% NEQ 0 (
  echo ERRO: npm ci falhou.
  pause
  exit /b %ERRORLEVEL%
)

echo.
echo [3/4] npm run build (Vite)...
call npm run build
if %ERRORLEVEL% NEQ 0 (
  echo ERRO: npm run build falhou.
  pause
  exit /b %ERRORLEVEL%
)

echo.
echo [4/4] php artisan migrate:fresh --seed...
call php artisan migrate --seed --force --no-interaction
if %ERRORLEVEL% NEQ 0 (
  echo ERRO: php artisan migrate:fresh falhou.
  pause
  exit /b %ERRORLEVEL%
)

echo.
echo =============================================
echo Setup concluido com sucesso.
echo =============================================
pause
ENDLOCAL
exit /b 0
