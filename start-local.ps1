$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot
$ctxDataDirectory = Join-Path $PSScriptRoot 'storage/mysql-local'
$ctxMysqlBinary = 'C:/xampp/mysql/bin/mysqld.exe'
if (!(Test-Path -LiteralPath $ctxDataDirectory)) {
    throw 'Database lokal belum dibuat. Ikuti setup MySQL di README.md.'
}
$ctxListener = Get-NetTCPConnection -LocalPort 3307 -State Listen -ErrorAction SilentlyContinue
if (!$ctxListener) {
    Start-Process -FilePath $ctxMysqlBinary -ArgumentList @(
        '--no-defaults',
        '--basedir=C:/xampp/mysql',
        ('--datadir="' + $ctxDataDirectory + '"'),
        '--port=3307',
        '--bind-address=127.0.0.1',
        '--innodb-use-native-aio=0'
    ) -WorkingDirectory $ctxDataDirectory -WindowStyle Hidden
}
php artisan serve --host=127.0.0.1 --port=8000
