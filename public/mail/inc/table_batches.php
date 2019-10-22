<?php
use PortalCMS\Core\Email\Batch\MailBatch;
?>
<form method="post" name="myform" action="">

<table id="batches" class="table table-sm" style="width:100%" data-page-length='25'>
    <thead class="thead-dark">
        <tr>
            <th class="text-center" ><input type="checkbox" id="selectall"/></th>

            <th>Batch ID</th>
            <th>Messages</th>
            <th>UsedTemplate</th>

            <?php

            use PortalCMS\Core\View\Text;

            if ($pageType === 'history') {
                echo '<th>Verzonden op</th>';
            }
            ?>
            <th>Status</th>
            <th>CreationDate</th>
                    <th></th>

        </tr>

    </thead>
    <tbody>
        <?php

        foreach ($batches as $row) {  ?>
        <tr>

            <td class="text-center" >
                <?php
                    echo '<input type="checkbox" name="id[]" id="checkbox" value="'.$row['id'].'"/>';
                ?>
            </td>

            <td><?php echo $row['id']; ?></td>
            <td><a href="messages.php?batch_id=<?php echo $row['id']; ?>"><?php echo MailBatch::countMessages($row['id']); ?></a></td>
                        <td><?php echo $row['UsedTemplate']; ?></td>

            <?php if ($pageType === 'history') {
                    echo '<td>'.$row['DateSent'].'</td>';
                } ?>
            <td>
                <?php
                if ($row['status'] === '1') {
                    echo '<span class="badge badge-secondary">Klaar voor verzending</span>';
                }
                if ($row['status'] === '2') {
                    echo '<span class="badge badge-success">Uitgevoerd</span>';
                }
                ?>

            </td>
                        <td><?php echo $row['CreationDate']; ?></td>

                                                <td>
                <a href="messages.php?batch_id=<?php echo $row['id']; ?>" title="Details" class="btn btn-success btn-sm">
                <i class="fas fa-info"></i></a>

            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<hr>
<?php
if ($pageType === 'index') {
                    echo '<input type="submit" class="btn btn-primary" name="sendBatchById" value="';
                    echo Text::get('LABEL_SEND_EMAIL');
                    echo '">';
                }
echo '<input type="submit" class="btn btn-danger" name="deleteBatchById" value="';
echo Text::get('LABEL_DELETE_EMAIL');
echo '">';
?>
</form>
<script>
$( '#selectall' ).click( function () {
    $( '#checkbox' ).prop('checked', this.checked)
})
</script>
