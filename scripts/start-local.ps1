param([switch]$Stop)
$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$statePath = Join-Path $projectRoot '.local\node-processes.json'
if ($Stop) {
    if (Test-Path -LiteralPath $statePath) {
        $state = Get-Content -LiteralPath $statePath -Raw | ConvertFrom-Json
        foreach ($processId in @($state.php, $state.database)) {
            $ownedProcess = Get-Process -Id $processId -ErrorAction SilentlyContinue
            if ($ownedProcess -and $ownedProcess.Path -and $ownedProcess.Path.StartsWith(((Join-Path $projectRoot '.local') + [IO.Path]::DirectorySeparatorChar), [StringComparison]::OrdinalIgnoreCase)) { Stop-Process -Id $processId }
        }
    }
    Write-Output 'Local store stopped.'
    exit
}
$databaseExe = Join-Path $projectRoot '.local\mariadb-11.4.13-winx64\bin\mariadbd.exe'
$dataDirectory = Join-Path $projectRoot '.local\db'
$databaseProcess = Get-NetTCPConnection -LocalPort 3308 -State Listen -ErrorAction SilentlyContinue | Select-Object -First 1
if (-not $databaseProcess) {
    $database = Start-Process -FilePath $databaseExe -ArgumentList @('--no-defaults', ('--datadir="' + $dataDirectory + '"'), '--port=3308', '--bind-address=127.0.0.1', '--console') -WorkingDirectory $projectRoot -WindowStyle Hidden -PassThru -RedirectStandardOutput (Join-Path $projectRoot '.local\database.log') -RedirectStandardError (Join-Path $projectRoot '.local\database-error.log')
    $databaseId = $database.Id
} else { $databaseId = $databaseProcess.OwningProcess }
$webProcess = Get-NetTCPConnection -LocalPort 8088 -State Listen -ErrorAction SilentlyContinue | Select-Object -First 1
if (-not $webProcess) {
    $php = Start-Process -FilePath (Join-Path $projectRoot '.local\php.exe') -ArgumentList @('-S','127.0.0.1:8088','-t', '".local\wordpress"','"scripts\router.php"') -WorkingDirectory $projectRoot -WindowStyle Hidden -PassThru -RedirectStandardOutput (Join-Path $projectRoot '.local\server.log') -RedirectStandardError (Join-Path $projectRoot '.local\server-error.log')
    $phpId = $php.Id
} else { $phpId = $webProcess.OwningProcess }
@{php=$phpId;database=$databaseId} | ConvertTo-Json | Set-Content -LiteralPath $statePath
Write-Output 'MUNEEB MART: http://127.0.0.1:8088'
