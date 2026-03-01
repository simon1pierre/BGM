param(
    [Parameter(Mandatory = $true)]
    [ValidateSet('mysql', 'sqlite')]
    [string]$Mode
)

$ErrorActionPreference = 'Stop'

$root = Split-Path -Parent $PSScriptRoot
$envPath = Join-Path $root '.env'
$sqliteFile = Join-Path $root 'database\database.sqlite'

if (-not (Test-Path $envPath)) {
    throw ".env not found at $envPath"
}

$lines = Get-Content $envPath

function Get-EnvValue {
    param([string[]]$Source, [string]$Key)
    $line = $Source | Where-Object { $_ -match "^\s*$([regex]::Escape($Key))\s*=" } | Select-Object -First 1
    if (-not $line) { return $null }
    $value = ($line -replace "^\s*$([regex]::Escape($Key))\s*=\s*", '')
    return $value.Trim()
}

function Set-EnvValue {
    param([ref]$Source, [string]$Key, [string]$Value)
    $pattern = "^\s*$([regex]::Escape($Key))\s*="
    $replacement = "$Key=$Value"
    $updated = $false
    for ($i = 0; $i -lt $Source.Value.Count; $i++) {
        if ($Source.Value[$i] -match $pattern) {
            $Source.Value[$i] = $replacement
            $updated = $true
            break
        }
    }
    if (-not $updated) {
        $Source.Value += $replacement
    }
}

function Ensure-HelperKey {
    param([ref]$Source, [string]$HelperKey, [string]$DefaultValue)
    $current = Get-EnvValue -Source $Source.Value -Key $HelperKey
    if ([string]::IsNullOrWhiteSpace($current)) {
        Set-EnvValue -Source $Source -Key $HelperKey -Value $DefaultValue
    }
}

$dbConnection = (Get-EnvValue -Source $lines -Key 'DB_CONNECTION')
$dbHost = (Get-EnvValue -Source $lines -Key 'DB_HOST')
$dbPort = (Get-EnvValue -Source $lines -Key 'DB_PORT')
$dbName = (Get-EnvValue -Source $lines -Key 'DB_DATABASE')
$dbUser = (Get-EnvValue -Source $lines -Key 'DB_USERNAME')
$dbPass = (Get-EnvValue -Source $lines -Key 'DB_PASSWORD')

if ($dbConnection -eq 'mysql') {
    Ensure-HelperKey -Source ([ref]$lines) -HelperKey 'DB_MYSQL_HOST' -DefaultValue ($dbHost ?? '127.0.0.1')
    Ensure-HelperKey -Source ([ref]$lines) -HelperKey 'DB_MYSQL_PORT' -DefaultValue ($dbPort ?? '3306')
    Ensure-HelperKey -Source ([ref]$lines) -HelperKey 'DB_MYSQL_DATABASE' -DefaultValue ($dbName ?? 'bgm')
    Ensure-HelperKey -Source ([ref]$lines) -HelperKey 'DB_MYSQL_USERNAME' -DefaultValue ($dbUser ?? 'root')
    Ensure-HelperKey -Source ([ref]$lines) -HelperKey 'DB_MYSQL_PASSWORD' -DefaultValue ($dbPass ?? '')
}

Ensure-HelperKey -Source ([ref]$lines) -HelperKey 'DB_SQLITE_DATABASE' -DefaultValue 'database/database.sqlite'

$mysqlHost = (Get-EnvValue -Source $lines -Key 'DB_MYSQL_HOST') ?? '127.0.0.1'
$mysqlPort = (Get-EnvValue -Source $lines -Key 'DB_MYSQL_PORT') ?? '3306'
$mysqlName = (Get-EnvValue -Source $lines -Key 'DB_MYSQL_DATABASE') ?? 'bgm'
$mysqlUser = (Get-EnvValue -Source $lines -Key 'DB_MYSQL_USERNAME') ?? 'root'
$mysqlPass = (Get-EnvValue -Source $lines -Key 'DB_MYSQL_PASSWORD') ?? ''
$sqlitePath = (Get-EnvValue -Source $lines -Key 'DB_SQLITE_DATABASE') ?? 'database/database.sqlite'

if ($Mode -eq 'sqlite') {
    if (-not (Test-Path $sqliteFile)) {
        New-Item -ItemType File -Path $sqliteFile | Out-Null
    }

    Set-EnvValue -Source ([ref]$lines) -Key 'DB_CONNECTION' -Value 'sqlite'
    Set-EnvValue -Source ([ref]$lines) -Key 'DB_DATABASE' -Value $sqlitePath
}
else {
    Set-EnvValue -Source ([ref]$lines) -Key 'DB_CONNECTION' -Value 'mysql'
    Set-EnvValue -Source ([ref]$lines) -Key 'DB_HOST' -Value $mysqlHost
    Set-EnvValue -Source ([ref]$lines) -Key 'DB_PORT' -Value $mysqlPort
    Set-EnvValue -Source ([ref]$lines) -Key 'DB_DATABASE' -Value $mysqlName
    Set-EnvValue -Source ([ref]$lines) -Key 'DB_USERNAME' -Value $mysqlUser
    Set-EnvValue -Source ([ref]$lines) -Key 'DB_PASSWORD' -Value $mysqlPass
}

Set-Content -Path $envPath -Value $lines

Write-Host "Switched DB mode to: $Mode"
Write-Host "Run: php artisan optimize:clear"
Write-Host "Then: php artisan migrate"

