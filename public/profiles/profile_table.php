<?php

?>
<table class="table table-striped table-condensed">
    <tr>
        <th width="20%">ID</th><td><?php echo $row['user_id']; ?></td>
    </tr>
    <tr>
        <th>user_emails</th><td><?php echo $row['user_email']; ?></td>
    </tr>
    <tr>
        <th>user_active</th><td><?php echo $row['user_active']; ?></td>
    </tr>
    <tr>
        <th>user_account_type</th><td><?php echo $row['user_account_type']; ?></td>
    </tr>
    <tr>
        <th>user_last_login_timestamp</th><td><?php echo $row['user_last_login_timestamp']; ?></td>
    </tr>
</table>
