@echo off
:: Abady Local Server Starter Wrapper
title Abady Local Server Starter
cd /d "%~dp0"
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0start-local.ps1"
pause
