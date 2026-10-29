$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
foreach ($relativePath in @('themes\muneeb-mart','plugins\muneeb-mart-core','plugins\woocommerce')) {
    $sourcePath = Join-Path $projectRoot ('wordpress\wp-content\' + $relativePath)
    $targetPath = Join-Path $projectRoot ('.local\wordpress\wp-content\' + $relativePath)
    New-Item -ItemType Directory -Path $targetPath -Force | Out-Null
    Copy-Item -Path (Join-Path $sourcePath '*') -Destination $targetPath -Recurse -Force
}
Write-Output 'Custom source synchronized to the ignored local WordPress installation.'
