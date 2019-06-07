<table id="example" class="table table-sm table-striped table-hover table-dark" style="width:100%">
    <thead class="thead-dark">
        <tr>
            <th>Acties</th>
            <th>ID</th>
            <th>name</th>
            <th>price</th>
            <th>type</th>
        </tr>
    </thead>
    <tbody>
    <?php 
        foreach ($products as $row) {
    echo '
        <tr>
            <td>
                <a href="product.php?id='.$row['id'].'" title="PDF maken" class="btn btn-primary btn-sm">
                    <span class="fa fa-user"></span>
                </a>
                <a href="edit_product.php?id='.$row['id'].'" title="Gegevens wijzigen" class="btn btn-warning btn-sm">
                    <span class="fa fa-edit"></span>
                </a>
                <a href="index.php?action=delete&id='.$row['id'].'" title="Verwijderen" onclick="return confirm(\'Weet u zeker dat u product '.$row['name']. " wilt verwijderen?\';)\"
                    class=\"btn btn-danger btn-sm\">
                    <span class=\"fa fa-trash\"></span>
                </a>
            </td>
            <td>'" .$row['id'].'</td>
            <td>'.$row['name'].'</td>
            <td>'.$row['price'].'</td>
            <td>'.$row['type'].'</td>
        </tr>
        ';
        $row['id']++;
    }
?>
    </tbody>

</table>