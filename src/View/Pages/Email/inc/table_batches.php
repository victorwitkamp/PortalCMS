<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Email\Batch\MailBatch;
use PortalCMS\Core\View\Text;

?>

<form method="post">
    <div class="row">
        <div class="col-8">
            <p>Aantal: <?= count($batches) ?></p>
        </div>
        <div class="col-4">
            <?php
            if ($pageType === 'index') { ?>
                <input type="submit" class="btn btn-outline-primary float-right" name="sendBatchById" value="<?= Text::get('LABEL_SEND_EMAIL') ?>"><?php
            } ?>
            <input type="submit" class="btn btn-danger float-right" name="deleteBatchById" value="<?= Text::get('LABEL_DELETE_EMAIL') ?>">
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table id="batches" class="table table-sm" style="width:100%;" data-page-length='25'>
                <thead class="thead-dark">
                <tr>
                    <th class="nosort text-center"><input type="checkbox" id="selectall"/></th>
                    <th>Batch ID</th>
                    <th>Messages</th>
                    <th>UsedTemplate</th>
                    <?php if ($pageType === 'history') { ?>
                        <th>Verzonden op</th>
                    <?php } ?>
                    <th>Status</th>
                    <th>CreationDate</th>
                    <th class="nosort">Openen</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($batches as $row) { ?>
                    <tr>
                        <td class="text-center"><input type="checkbox" name="id[]" id="checkbox<?= $row['id'] ?>"
                                                       value="<?= $row['id'] ?>"/></td>
                        <td><?= $row['id'] ?></td>
                        <td><a href="Messages?batch_id=<?= $row['id'] ?>"><?= MailBatch::countMessages($row['id']) ?></a></td>
                        <td><?= $row['UsedTemplate'] ?></td>
                        <?php if ($pageType === 'history') { ?>
                            <td><?= $row['DateSent'] ?></td>
                        <?php } ?>
                        <td>
                            <?php
                            if ($row['status'] === '1') {
                                ?><span class="badge badge-secondary">Klaar voor verzending</span><?php
                            }
                            if ($row['status'] === '2') {
                                ?><span class="badge badge-success">Uitgevoerd</span><?php
                            }
                            ?>
                        </td>
                        <td><?= $row['CreationDate'] ?></td>
                        <td><a href="Messages?batch_id=<?= $row['id'] ?>" title="Details" class="btn btn-outline-success btn-sm"><i class="fas fa-info"></i></a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <script>
                $(document).ready(function () {
                    $('#batches').DataTable({
                        "columnDefs": [ {
                            "targets": 'nosort',
                            "orderable": false
                        } ],
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Dutch.json'
                        },
                        ordering: true,
                        order: [[1, 'asc']]
                    })
                })
            </script>
        </div>
    </div>
</form>
<script>
    $("#selectall").on('change', function () {
        if (this.checked) {
            $("input[type='checkbox']").prop('checked', true)
        } else {
            $("input[type='checkbox']").prop('checked', false)
        }
    });
</script>
