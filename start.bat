@echo off
:: Abady Live Server Starter Wrapper
title Abady Live Server Starter
cd /d "%~dp0"
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0start.ps1"
pause
