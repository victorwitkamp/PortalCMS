<form method="post" name="myform" action="">

<table id="example" class="table table-sm" style="width:100%">
    <thead class="thead-dark">
        <tr>
            <th class="text-center" ><input type="checkbox" id="selectall"/></th>
            <th></th>
            <th>#</th>
            <th>recipient_email</th>
            <th>subject</th>
            <?php if ($pageType = 'history') { echo '<th>Verzonden op</th>'; } ?>
            <th>status</th>
        </tr>

    </thead>
    <tbody>
        <?php
        $result = $stmt->fetchAll();
        foreach ($result as $row) {  ?>
        <tr>
            <td class="text-center" >
                <?php
                // if ($row['status'] === '1') {
                    echo '<input type="checkbox" name="id[]" id="checkbox" value="'.$row['id'].'"/>';
                // } else {
                    // echo '<input type="checkbox" disabled>';
                // }
                ?>
            </td>
            <td>
                <a href="details.php?id=<?php echo $row['id']; ?>" title="Details" class="btn btn-info btn-sm">
                <i class="fas fa-info"></i></a>

            </td>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['recipient_email']; ?></td>
            <td><?php echo $row['subject']; ?></td>
            <?php if ($pageType = 'history') { echo '<td>'.$row['DateSent'].'</td>'; } ?>
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
        </tr>
        <?php } ?>
    </tbody>
</table>
<hr>
<?php
if ($pageType == 'index') {
    echo '<input type="submit" class="btn btn-info" name="sendScheduledMailById" value="';
    echo Text::get('LABEL_SEND_EMAIL');
    echo '">';
}
if ($pageType == 'history') {
    echo '<input type="submit" class="btn btn-info" name="deleteScheduledMailById" value="';
    echo Text::get('LABEL_DELETE_EMAIL');
    echo '">';
}
?>
<?php  ?>
</form>
<script>
  $( '#selectall' ).click( function () {
    // $( '#example input[type="checkbox"]' ).prop('checked', this.checked)
    $( '#example #checkbox' ).prop('checked', this.checked)

})
</script>