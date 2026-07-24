<?php

declare(strict_types=1);

use PortalCMS\Core\View\Text;

?>
<form method="post">
    <table id="messages" class="table table-sm" style="width:100%;" data-page-length="25">
        <thead class="table-dark">
        <tr>
            <th class="text-center"><input type="checkbox" id="selectall"/></th>
            <th>ID</th>
            <th>Batch ID</th>
            <th>Recipients</th>
            <th>Subject</th>
            <?php if ($pageType === 'history') { ?>
                <th>Verzonden op</th>
            <?php } ?>
            <th>Status</th>
            <th>CreationDate</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($mails as $mail) { ?>
            <tr>
                <td class="text-center">
                    <input type="checkbox" name="id[]" id="checkbox<?= $mail->id ?>" value="<?= $mail->id ?>"/>
                </td>
                <td><?= $mail->id ?></td>
                <td><?= $mail->batch_id ?></td>
                <td><?= $mail->recipients()->count() ?></td>
                <td><?= $mail->subject ?></td>
                <?php if ($pageType === 'history') { ?>
                    <td><?= $mail->DateSent?->format('Y-m-d H:i:s') ?></td>
                <?php } ?>
                <td>
                    <?php if ($mail->status === 1) { ?>
                        <span class="badge bg-secondary">Klaar voor verzending</span>
                    <?php } elseif ($mail->status === 2) { ?>
                        <span class="badge bg-success">Verzonden</span>
                    <?php } elseif ($mail->status === 3) { ?>
                        <span class="badge bg-danger">Fout bij verzenden</span>
                    <?php } ?>
                </td>
                <td><?= $mail->CreationDate->format('Y-m-d H:i:s') ?></td>
                <td>
                    <a href="Details?id=<?= $mail->id ?>" title="Details" class="btn btn-success btn-sm">
                        <i class="fas fa-info"></i>
                    </a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <hr>
    <?php if ($pageType === 'index') { ?>
        <button type="submit" class="btn btn-primary" formaction="/Email/Messages/Send">
            <?= Text::get('LABEL_SEND_EMAIL') ?>
        </button>
    <?php } ?>
    <button type="submit" class="btn btn-danger" formaction="/Email/Messages/Delete">
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
