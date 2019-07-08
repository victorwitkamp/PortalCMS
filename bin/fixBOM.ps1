$files=get-childitem -Path . -Include @("*.php") -Recurse
foreach ($f in $files)
{
(Get-Content $f.PSPath) |
Foreach-Object {$_ -replace "\xEF\xBB\xBF", ""} |
Set-Content $f.PSPath
}