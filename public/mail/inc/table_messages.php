<form method="post" name="myform" action="">

<table id="messages" class="table table-sm" style="width:100%" data-page-length='25'>
    <thead class="thead-dark">
        <tr>
            <th class="text-center" ><input type="checkbox" id="selectall"/></th>

            <th>Mail ID</th>
            <th>Batch ID</th>
            <th>Recipient</th>
            <th>Subject</th>
            <?php

            use PortalCMS\Core\Text;

            if ($pageType === 'history') {
                echo '<th>Verzonden op</th>';
            }
            ?>
            <th>Status</th>
                    <th></th>

        </tr>

    </thead>
    <tbody>
        <?php

        foreach ($result as $row) {  ?>
        <tr>

            <td class="text-center" >
                <?php
                    echo '<input type="checkbox" name="id[]" id="checkbox" value="'.$row['id'].'"/>';
                ?>
            </td>

            <td><?php echo $row['id']; ?></td>
            <td><?php echo($row['batch_id']); ?></td>
            <td><?php echo($row['recipient_email']) ?></td>
            <td><?php echo $row['subject']; ?></td>
            <?php if ($pageType === 'history') { echo '<td>'.$row['DateSent'].'</td>';
            } ?>
            <td>
                <?php
                if ($row['status'] === '1') {
                    echo '<span class="badge badge-secondary">Klaar voor verzending</span>';
                }
                if ($row['status'] === '2') {
                    echo '<span class="badge badge-success">Verzonden</span>';
                }
                if ($row['status'] === '3') {
                    echo '<span class="badge badge-danger">Fout bij verzenden</span>';
                }
                ?>

            </td>
                                                <td>
                <a href="details.php?id=<?php echo $row['id']; ?>" title="Details" class="btn btn-success btn-sm">
                <i class="fas fa-info"></i></a>

            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<hr>
<?php
if ($pageType === 'index') {
    echo '<input type="submit" class="btn btn-primary" name="sendScheduledMailById" value="';
    echo Text::get('LABEL_SEND_EMAIL');
    echo '">';
}
echo '<input type="submit" class="btn btn-danger" name="deleteScheduledMailById" value="';
echo Text::get('LABEL_DELETE_EMAIL');
echo '">';
?>
</form>
<script>
$("#selectall").on('change', function(){
    if (this.checked) {
        $("input[type='checkbox']").prop('checked', true)
    } else {
        $("input[type='checkbox']").prop('checked', false)
    }
});
</script>
