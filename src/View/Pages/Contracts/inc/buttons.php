<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

?>
<form method="post">
    <a href="/Contracts" class="btn btn-sm btn-outline-primary">
        <span class="fa fa-arrow-left"></span>
    </a>
    <a href="/Contracts/Edit?id=<?= $contract->id ?>" class="btn btn-sm btn-warning">
        <span class="fa fa-edit"></span>
    </a>
    <input type="hidden" name="id" value="<?= $contract->id ?>">
    <button type="submit" name="deleteContract" class="btn btn-danger btn-sm">
        <span class="fa fa-trash"></span>
    </button>
</form>
