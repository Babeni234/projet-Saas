# HABITATUM — Enregistre la tâche planifiée Windows pour le scheduler Laravel
# Auto-élévation si pas en Administrateur

# Vérifie si le script est déjà en Administrateur
$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
if (-not $isAdmin) {
    $scriptPath = $MyInvocation.MyCommand.Path
    Write-Host "Relance en tant qu'Administrateur..." -ForegroundColor Yellow
    Start-Process powershell -Verb RunAs -ArgumentList "-NoProfile -ExecutionPolicy Bypass -File `"$scriptPath`""
    exit
}

$TaskName = "HABITATUM-LaravelScheduler"
$ProjectDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$BatchFile = Join-Path $ProjectDir "schedule-run.bat"

if (!(Test-Path $BatchFile)) {
    Write-Host "ERREUR : $BatchFile introuvable." -ForegroundColor Red
    exit 1
}

$Action = New-ScheduledTaskAction -Execute $BatchFile -WorkingDirectory $ProjectDir
$Trigger = New-ScheduledTaskTrigger -Once -At (Get-Date) -RepetitionInterval (New-TimeSpan -Minutes 1) -RepetitionDuration (New-TimeSpan -Days 365)
$Settings = New-ScheduledTaskSettingsSet -AllowStartIfOnBatteries -DontStopIfGoingOnBatteries -StartWhenAvailable
$Principal = New-ScheduledTaskPrincipal -UserId "SYSTEM" -LogonType ServiceAccount -RunLevel Highest

Register-ScheduledTask -TaskName $TaskName -Action $Action -Trigger $Trigger -Settings $Settings -Principal $Principal -Force

Write-Host "Tâche '$TaskName' créée. Le scheduler Laravel s'exécutera toutes les minutes." -ForegroundColor Green
Write-Host "Pour vérifier : Get-ScheduledTask -TaskName '$TaskName'" -ForegroundColor Cyan
Write-Host "Pour supprimer : Unregister-ScheduledTask -TaskName '$TaskName' -Confirm:`$false" -ForegroundColor Yellow
