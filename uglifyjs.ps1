 $arrayInput = ("C:\inetpub\portal\includes")
  Write-Host "--------------------------"
  Write-Host "JS"
  Write-Host "--------------------------"
 foreach ($input in $arrayInput)
 {
    $folders =  Get-ChildItem -path $input -Recurse -include *.js
    Foreach ($fldr in $folders)
    {
        if($fldr.Attributes -ne 'Directory')
        {
            uglifyjs --compress --output $fldr.FullName  $fldr.FullName
            Write-Host $fldr.FullName "has been minified."
        }
    }
 }

 $arrayInput2 = ("C:\inetpub\portal\includes")
  Write-Host "--------------------------"
  Write-Host "CSS"
  Write-Host "--------------------------"
 foreach ($input in $arrayInput2)
 {
    $folders =  Get-ChildItem -path $input -Recurse -include *.css
    Foreach ($fldr in $folders)
    {
        if($fldr.Attributes -ne 'Directory')
        {
            uglifycss --output $fldr.FullName  $fldr.FullName
            Write-Host $fldr.FullName "has been minified."
        }
    }
 }
