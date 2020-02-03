<?php
declare(strict_types=1);

?>
<form method="post">
    <a href="/Contracts" class="btn btn-sm btn-primary">
        <span class="fa fa-arrow-left"></span>
    </a>
    <a href="Edit?id=<?= $contract->id ?>" class="btn btn-sm btn-warning">
        <span class="fa fa-edit"></span>
    </a>
    <input type="hidden" name="id" value="<?= $contract->id ?>">
    <button type="submit" name="deleteContract"
            class="btn btn-danger btn-sm"
            onclick="return confirm(;\'Weet u zeker dat u het contract van <?= $contract->band_naam ?> wilt verwijderen?\')">
    <span class="fa fa-trash"></span>
    </button>
</form>
