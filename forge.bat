@echo off
set FENNEC_BASE_PATH=%~dp0
php "%FENNEC_BASE_PATH%vendor\fennectra\framework\bin\cli" %*
