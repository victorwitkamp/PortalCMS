<?php

?>
<a href="index.php" class="btn btn-sm btn-primary"><span class="fa fa-arrow-left"></span></a>
<?php
echo '<a href="edit.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning"><span class="fa fa-edit"></span></a>';

echo '<a href="index.php?action=delete&id='
. $row['id']
. '" onclick="return confirm("Weet je zeker dat je '
. $row['voornaam'] . ' ' . $row['achternaam'] . ' wilt verwijderen?")" class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></a>';
