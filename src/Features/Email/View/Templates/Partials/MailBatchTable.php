<?php

declare(strict_types=1);

use PortalCMS\Core\View\Text;

?>
<form method="post">
    <table id="batches" class="table table-sm" style="width:100%;" data-page-length="25">
        <thead class="table-dark">
        <tr>
            <th class="text-center"><input type="checkbox" id="selectall"/></th>
            <th>Batch ID</th>
            <th>Messages</th>
            <th>UsedTemplate</th>
            <?php if ($pageType === 'history') { ?>
                <th>Verzonden op</th>
            <?php } ?>
            <th>Status</th>
            <th>CreationDate</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($batches as $batch) { ?>
            <tr>
                <td class="text-center">
                    <input type="checkbox" name="id[]" id="checkbox<?= $batch->id ?>" value="<?= $batch->id ?>"/>
                </td>
                <td><?= $batch->id ?></td>
                <td>
                    <a href="Messages?batch_id=<?= $batch->id ?>"><?= $messageCounts[$batch->id] ?? 0 ?></a>
                </td>
                <td><?= $batch->UsedTemplate ?></td>
                <?php if ($pageType === 'history') { ?>
                    <td><?= $batch->DateSent?->format('Y-m-d H:i:s') ?></td>
                <?php } ?>
                <td>
                    <?php if ($batch->status === 1) { ?>
                        <span class="badge bg-secondary">Klaar voor verzending</span>
                    <?php } elseif ($batch->status === 2) { ?>
                        <span class="badge bg-success">Uitgevoerd</span>
                    <?php } ?>
                </td>
                <td><?= $batch->CreationDate->format('Y-m-d H:i:s') ?></td>
                <td>
                    <a href="Messages?batch_id=<?= $batch->id ?>" title="Details" class="btn btn-success btn-sm">
                        <i class="fas fa-info"></i>
                    </a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <hr>
    <?php if ($pageType === 'index') { ?>
        <button type="submit" class="btn btn-primary" formaction="/Email/Batches/Send">
            <?= Text::get('LABEL_SEND_EMAIL') ?>
        </button>
    <?php } ?>
    <button type="submit" class="btn btn-danger" formaction="/Email/Batches/Delete">
        <?= Text::get('LABEL_DELETE_EMAIL') ?>
    </button>
</form>
<script>
    document.getElementById("selectall").addEventListener("change", function () {
        document.querySelectorAll("input[type='checkbox']").forEach(function (checkbox) {
            checkbox.checked = this.checked;
        }, this);
    });
</script>
