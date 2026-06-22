# Abady Live Server Starter - PowerShell script

# Set console title
$host.ui.RawUI.WindowTitle = "Abady Live Server Starter"

# Force UTF-8 encoding for console output
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8

Write-Host "=============================================" -ForegroundColor Gray
Write-Host "       Starting Abady Server & Tunnel        " -ForegroundColor Cyan -Bold
Write-Host "=============================================" -ForegroundColor Gray

# Start PHP Artisan Serve in the background
$phpJob = Start-Process php -ArgumentList "artisan serve" -NoNewWindow -PassThru -ErrorAction SilentlyContinue

# Start Ngrok in the background
$ngrokJob = Start-Process "O:\System\Ngrok\ngrok.exe" -ArgumentList "http 8000" -NoNewWindow -PassThru -ErrorAction SilentlyContinue

Write-Host "Spinning up local server (port 8000) and ngrok..." -ForegroundColor Yellow
Start-Sleep -Seconds 4

# Retrieve the public URL from ngrok local API
try {
    $tunnels = Invoke-RestMethod -Uri "http://127.0.0.1:4040/api/tunnels" -ErrorAction Stop
    $publicUrl = $tunnels.tunnels[0].public_url
    
    Write-Host "`n==================================================" -ForegroundColor Green -Bold
    Write-Host "   🎉 WEBSITE IS NOW LIVE AND PUBLICLY ACCESSIBLE!" -ForegroundColor Green -Bold
    Write-Host "==================================================" -ForegroundColor Green -Bold
    Write-Host "   Public URL : " -NoNewline
    Write-Host $publicUrl -ForegroundColor Cyan -Bold
    Write-Host "   Admin URL  : " -NoNewline
    Write-Host "$publicUrl/admin/login" -ForegroundColor Cyan -Bold
    Write-Host "==================================================" -ForegroundColor Green -Bold
    
    Write-Host "`nOpening the website in your browser..." -ForegroundColor Gray
    Start-Process $publicUrl
} catch {
    Write-Host "`n⚠️ Warning: Could not retrieve the ngrok public URL." -ForegroundColor Red
    Write-Host "Please check if your authtoken is configured and your network is active." -ForegroundColor Yellow
}

Write-Host "`n👉 Press CTRL+C or close this window to stop all servers." -ForegroundColor Magenta

# Trap CTRL+C or termination signals
$cleanup = {
    Write-Host "`n`nClosing local servers and ngrok tunnel..." -ForegroundColor Red -Bold
    Stop-Process -Id $phpJob.Id -Force -ErrorAction SilentlyContinue
    Stop-Process -Id $ngrokJob.Id -Force -ErrorAction SilentlyContinue
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
