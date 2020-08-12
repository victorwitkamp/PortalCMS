<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Email\Recipient\EmailRecipientCollectionCreator;
use PortalCMS\Core\View\Text;

?>
<form method="post">
    <div class="row">
        <div class="col-8">
            <p>Aantal: <?= $mailcount ?></p>
        </div>
        <div class="col-4">
            <?php
            if ($pageType === 'index') { ?>
                <input type="submit" class="btn btn-primary float-right" name="sendScheduledMailById" value="<?= Text::get('LABEL_SEND_EMAIL') ?>">
            <?php } ?>
            <input type="submit" class="btn btn-danger float-right" name="deleteScheduledMailById" value="<?= Text::get('LABEL_DELETE_EMAIL') ?>">
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table id="messages" class="table table-sm" style="width:100%;" data-page-length='25'>
                <thead class="thead-dark">
                    <tr>
                        <th class="nosort text-center"><input type="checkbox" id="selectall"/></th>
                        <th>ID</th>
                        <th>Batch ID</th>
                        <th>Recipients</th>
                        <th>Subject</th>
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
                foreach ($result as $row) { ?>
                    <tr>
                        <td class="text-center"><input type="checkbox" name="id[]" id="checkbox<?= $row['id'] ?>"
                                                       value="<?= $row['id'] ?>"/></td>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['batch_id'] ?></td>
                        <td>
                            <?php
                            echo $row['recipient_email'];
                            $creator = new EmailRecipientCollectionCreator();
                            $recipients = $creator->createCollection($row['id']);
                            if (!empty($recipients)) {
                                echo count($recipients);
                            }
                            ?>
                        </td>
                        <td><?= $row['subject'] ?></td>
                        <?php if ($pageType === 'history') { ?>
                            <td><?= $row['DateSent'] ?></td>
                        <?php } ?>
                        <td>
                            <?php
                            if ($row['status'] === 1) {
                                ?><span class="badge badge-secondary">Klaar voor verzending</span><?php
                            }
                            if ($row['status'] === 2) {
                                ?><span class="badge badge-success">Verzonden</span><?php
                            }
                            if ($row['status'] === 3) {
                                ?><span class="badge badge-danger">Fout bij verzenden</span><?php
                            }
                            ?>
                        </td>
                        <td><?= $row['CreationDate'] ?></td>
                        <td><a href="Details?id=<?= $row['id'] ?>" title="Details" class="btn btn-success btn-sm"><i class="fas fa-info"></i></a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <script>
                $(document).ready(function () {
                    $('#messages').DataTable({
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
