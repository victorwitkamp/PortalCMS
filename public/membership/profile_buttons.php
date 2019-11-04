<?php

?>
<form method="post">
    <a href="index.php" class="btn btn-sm btn-primary">
        <span class="fa fa-arrow-left"></span>
    </a>
    <a href="edit.php?id=<?= $row->id ?>" class="btn btn-sm btn-warning">
        <span class="fa fa-edit"></span>
    </a>
    <input name="id" type="hidden" value="<?= $row->id ?>">
    <button name="deleteMember" type="submit" onclick="return confirm('Weet je zeker dat je <?= $row->voornaam ?> <?= $row->achternaam ?> wilt verwijderen?')" class="btn btn-sm btn-danger">
        <i class="far fa-trash-alt"></i>
    </button>
</form>