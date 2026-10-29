$ErrorActionPreference = 'Stop'
$packageScript = Join-Path $PSScriptRoot 'package.py'
& python $packageScript
if ($LASTEXITCODE -ne 0) { throw 'Release packaging failed.' }
