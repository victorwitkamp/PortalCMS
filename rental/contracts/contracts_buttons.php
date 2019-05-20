<a href="index.php" class="btn btn-sm btn-primary"><span class="fa fa-arrow-left"></span></a>

<a href="edit.php?id=<?php 
echo $row['id']; 
?>" class="btn btn-sm btn-warning"><span class="fa fa-edit"></span></a>

<a href="contracten.php?action=delete&id=<?php 
echo $row['id']; 
?>" onclick="return confirm('Weet je zeker dat je <?php 
echo $row['voornaam']; 
?> <?php 
echo $row['achternaam']; 
?> wilt verwijderen?')" class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></a>