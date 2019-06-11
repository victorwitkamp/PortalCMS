<a href="index.php"
class="btn btn-sm btn-primary">
    <span class="fa fa-arrow-left"></span>
</a>

<a href="edit.php?id=<?php echo $contract['id']; ?>"
class="btn btn-sm btn-warning">
    <span class="fa fa-edit"></span>
</a>

<form method="post">
    <input type="hidden" name="id" value="<?php echo $contract['id']; ?>">
    <button type="submit" name="deleteContract"
            class="btn btn-danger btn-sm"
            onclick="return confirm(\'Weet u zeker dat u het contract van <?php echo $contract['band_naam']; ?> wilt verwijderen?\')">
    <span class="fa fa-trash"></span>
    </button>
</form>