<?php
$pageName = 'Nieuw bericht';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
if (!Auth::checkPrivilege("mail-scheduler")) {
    Redirect::permissionError();
    die();
}
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
                <div class="col-sm-12"><h1><?php echo $pageName ?></h1></div>
            </div>
            <hr>
            <form method="post">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <p class="card-title">Leden selecteren</p>
                            </div>
                            <div class="card-body">
                                <?php foreach (Member::getMembers() as $row): ?>
                                    <input type="checkbox" name='recipients[]' value="<?php echo $row['id']; ?>"><?php echo $row['voornaam'].' '.$row['achternaam']; ?><br/>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <p class="card-title">Template selecteren</p>
                            </div>
                            <div class="card-body">
                                <select name='templateid'>
                                    <?php foreach (MailTemplate::getTemplatesByType('member') as $row): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['subject']; ?></option>
                                    <?php endforeach ?>
                                </select>
                                <input type="hidden" name="type" value="member">
                                <input type="submit" name="createMailWithTemplate">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
</body>
</html>