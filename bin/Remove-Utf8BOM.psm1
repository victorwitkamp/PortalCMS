#The sample scripts are not supported under any Microsoft standard support
#program or service. The sample scripts are provided AS IS without warranty
#of any kind. Microsoft further disclaims all implied warranties including,
#without limitation, any implied warranties of merchantability or of fitness for
#a particular purpose. The entire risk arising out of the use or performance of
#the sample scripts and documentation remains with you. In no event shall
#Microsoft, its authors, or anyone else involved in the creation, production, or
#delivery of the scripts be liable for any damages whatsoever (including,
#without limitation, damages for loss of business profits, business interruption,
#loss of business information, or other pecuniary loss) arising out of the use
#of or inability to use the sample scripts or documentation, even if Microsoft
#has been advised of the possibility of such damages.

Function Remove-UTF8BOM
{
    Param
    (
        [Parameter(Mandatory=$true)]
        [String] $FilePath
    )
    Try
    {
        [System.IO.FileInfo] $file = Get-Item -Path $FilePath
        $sequenceBOM = New-Object System.Byte[] 3
        $reader = $file.OpenRead()
        $bytesRead = $reader.Read($sequenceBOM, 0, 3)
        $reader.Dispose()
        #A UTF-8+BOM string will start with the three following bytes. Hex: 0xEF0xBB0xBF, Decimal: 239 187 191
        if ($bytesRead -eq 3 -and $sequenceBOM[0] -eq 239 -and $sequenceBOM[1] -eq 187 -and $sequenceBOM[2] -eq 191)
        {
            $utf8NoBomEncoding = New-Object System.Text.UTF8Encoding($False)
            [System.IO.File]::WriteAllLines($FilePath, (Get-Content $FilePath), $utf8NoBomEncoding)
            Write-Host "Remove UTF-8 BOM successfully"
        }
        Else
        {
            Write-Warning "Not UTF-8 BOM file"
        }
    }
    Catch [Exception]
    {
        Write-Error $_.Exception.ToString()
    }
}