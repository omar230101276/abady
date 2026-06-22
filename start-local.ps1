# Abady Local Server Starter - PowerShell script

# Set console title
$host.ui.RawUI.WindowTitle = "Abady Local Server Starter"

# Force UTF-8 encoding for console output
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8

Write-Host "=============================================" -ForegroundColor Gray
Write-Host "       Starting Abady Server (Local Only)     " -ForegroundColor Cyan -Bold
Write-Host "=============================================" -ForegroundColor Gray

# Start PHP Artisan Serve in the background
$phpJob = Start-Process php -ArgumentList "artisan serve" -NoNewWindow -PassThru -ErrorAction SilentlyContinue

# Start npm run dev (Vite) in the background
$viteJob = Start-Process npm -ArgumentList "run dev" -NoNewWindow -PassThru -ErrorAction SilentlyContinue

Write-Host "Spinning up local server (port 8000) and Vite..." -ForegroundColor Yellow
Start-Sleep -Seconds 3

$localUrl = "http://127.0.0.1:8000"

Write-Host "`n==================================================" -ForegroundColor Green -Bold
Write-Host "   🎉 WEBSITE IS NOW RUNNING LOCALLY!" -ForegroundColor Green -Bold
Write-Host "==================================================" -ForegroundColor Green -Bold
Write-Host "   Local URL : " -NoNewline
Write-Host $localUrl -ForegroundColor Cyan -Bold
Write-Host "   Admin URL : " -NoNewline
Write-Host "$localUrl/admin/login" -ForegroundColor Cyan -Bold
Write-Host "==================================================" -ForegroundColor Green -Bold

Write-Host "`nOpening the website in your browser..." -ForegroundColor Gray
Start-Process $localUrl

Write-Host "`n👉 Press CTRL+C or close this window to stop all servers." -ForegroundColor Magenta

# Trap CTRL+C or termination signals
$cleanup = {
    Write-Host "`n`nClosing local servers..." -ForegroundColor Red -Bold
    Stop-Process -Id $phpJob.Id -Force -ErrorAction SilentlyContinue
    Stop-Process -Id $viteJob.Id -Force -ErrorAction SilentlyContinue
    Write-Host "Done! Goodbye." -ForegroundColor Gray
}

# Infinite loop to keep process alive and wait for exit
try {
    while ($true) {
        Start-Sleep -Seconds 1
    }
} finally {
    & $cleanup
}
