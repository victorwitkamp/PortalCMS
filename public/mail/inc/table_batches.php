<?php
use PortalCMS\Core\Email\Batch\MailBatch;
use PortalCMS\Core\View\Text;
?>
<form method="post">
    <table id="batches" class="table table-sm" style="width:100%" data-page-length='25'>
        <thead class="thead-dark">
            <tr>
                <th class="text-center" ><input type="checkbox" id="selectall" /></th>
                <th>Batch ID</th>
                <th>Messages</th>
                <th>UsedTemplate</th>
                <?php
                if ($pageType === 'history') { ?>
                <th>Verzonden op</th>
                <?php } ?>
                <th>Status</th>
                <th>CreationDate</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($batches as $row) {  ?>
                <tr>
                    <td class="text-center">
                        <input type="checkbox" name="id[]" id="checkbox" value="<?php echo $row['id']; ?>"/>
                    </td>
                    <td>
                        <?php echo $row['id']; ?>
                    </td>
                    <td>
                        <a href="messages.php?batch_id=<?php echo $row['id']; ?>"><?php echo MailBatch::countMessages($row['id']); ?></a>
                    </td>
                    <td>
                        <?php echo $row['UsedTemplate']; ?>
                    </td>
                    <?php if ($pageType === 'history') { ?>
                    <td>
                        <?php echo $row['DateSent']; ?>
                    </td>
                    <?php } ?>
                    <td>
                        <?php
                        if ($row['status'] === '1') { ?>
                        <span class="badge badge-secondary">Klaar voor verzending</span>
                        <?php }
                        if ($row['status'] === '2') { ?>
                        <span class="badge badge-success">Uitgevoerd</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php echo $row['CreationDate']; ?>
                    </td>
                    <td>
                        <a href="messages.php?batch_id=<?php echo $row['id']; ?>" title="Details" class="btn btn-success btn-sm"><i class="fas fa-info"></i></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <hr>
    <?php
    if ($pageType === 'index') { ?>
    <input type="submit" class="btn btn-primary" name="sendBatchById" value="<?php echo Text::get('LABEL_SEND_EMAIL'); ?>"><?php
    } ?>
    <input type="submit" class="btn btn-danger" name="deleteBatchById" value="<?php echo Text::get('LABEL_DELETE_EMAIL'); ?>">
</form>
<script>
    $("#selectall").on('change', function() {
        if (this.checked) {
            $("input[type='checkbox']").prop('checked', true)
        } else {
            $("input[type='checkbox']").prop('checked', false)
        }
    });
</script>
