<?php 
$pageName = 'Profiel';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
$row = Contract::getById($_GET['id']); 
$pageName = 'Contract van '.$row['band_naam'];
require_once DIR_INCLUDES.'functions.php';
require_once DIR_INCLUDES.'head.php';
displayHeadCSS(); 
PortalCMS_JS_headJS(); ?>
</head>
<body>

<?php require DIR_INCLUDES.'nav.php'; ?>
<main>
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <h1><?php echo $pageName; ?></h1>
            </div>
        </div>
        <div class="container">
            <?php require 'contracts_buttons.php'; ?>
            <a href="invoices.php?id=<?php echo $_GET['id']; ?>">Facturen bekijken</a>
            <hr>
            <?php require 'contract_details_table.php'; ?>
            <hr>
            <?php require 'contracts_buttons.php'; ?>
        </div>
    </div>
</main>
<?php require DIR_INCLUDES.'footer.php'; ?>
</body>
</html>