<?php

?>
<form method="post" action="index.php">
    <a href="index.php" class="btn btn-sm btn-primary"><span class="fa fa-arrow-left"></span></a>
    <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
    <button type="submit" name="deleteuser" onclick="return confirm('Weet je zeker dat je <?php echo $row['user_name']; ?> wilt verwijderen?')" class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></button>
</form>
