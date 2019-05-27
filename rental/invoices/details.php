<?php
$pageName = 'Factuur';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
if (!Permission::hasPrivilege("rental-invoices")) {
    Redirect::permissionerror();
    die();
}
require DIR_ROOT.'includes/functions.php';

if ($invoice = Invoice::getInvoiceById($_GET['id'])) {
    $pageName = 'Factuur: '.$invoice['factuurnummer'];
} else {
    Session::add('feedback_negative', "Geen resultaten voor opgegeven factuur ID.");
    Redirect::Error();
}
require DIR_ROOT.'includes/head.php';
displayHeadCSS();
PortalCMS_JS_headJS(); ?>
</head>
<body>

<?php require DIR_ROOT.'includes/nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h1><?php echo $pageName; ?></h1>
            </div>
            <?php
            View::renderFeedbackMessages(); ?>
            <hr>
            <h3>Details</h3>

            <table class="table table-striped table-condensed">
                <tr>
                    <th>Factuurnummer</th>
                    <td>
                        <?php echo $invoice['factuurnummer']; ?>
                    </td>
                </tr>
                <tr>
                    <th>Huurder</th>
                    <td>
                        <?php
                            $row = Contract::getById($invoice['contract_id']);
                            echo $row['band_naam'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>CreationDate</th>
                    <td>
                        <?php echo $invoice['CreationDate']; ?>
                    </td>
                </tr>
            </table>

            <h3>Items</h3>
            <table class="table table-striped table-condensed">
                <tr>
                    <th>Acties</th>
                    <th>Omschrijving</th>
                    <th>Prijs</th>
                </tr>
                <?php
                    $invoiceitems = Invoice::getInvoiceItemsById($invoice['id']);
                    foreach ($invoiceitems as $invoiceitem) {
                ?>
                <tr>
                    <td>
                        <?php if ($invoice['status'] === '0') { ?>
                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo $invoiceitem['id']; ?>">
                            <button type="submit" name="deleteinvoiceitem" onclick="return confirm('Weet je zeker dat je <?php echo $invoiceitem['id']; ?> wilt verwijderen?')" class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></button>
                        </form>
                        <?php } ?>
                    </td>
                    <td>
                        <?php echo $invoiceitem['name']; ?>
                    </td>
                    <td>
                        <?php echo '&euro; '.$invoiceitem['price']; ?>
                    </td>
                </tr>
                <?php } ?>
            </table>

            <h3>Items toevoegen</h3>

            <?php if ($invoice['status'] === '0') { ?>
            <form method="post">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Omschrijving</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Prijs</label>
                        <input type="text" name="price" class="form-control">
                    </div>
                </div>
                <input type="hidden" name="invoiceid" value="<?php echo $_GET['id']; ?>">
                <input type="submit" name="addinvoiceitem" class="btn btn-primary">
            </form>
            <?php } else { ?>
            <p>Je kunt de factuur niet meer bewerken</p>
            <?php } ?>

        </div>
    </div>
</main>
<?php require DIR_ROOT.'includes/footer.php'; ?>
</body>
</html>