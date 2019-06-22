 $arrayInput = ("C:\inetpub\portal\includes\js")

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

 $arrayInput2 = ("C:\inetpub\portal\includes\css")

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
