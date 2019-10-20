<?php

use PortalCMS\Authentication\Authentication;
use PortalCMS\Core\Redirect;
use PortalCMS\Models\Member;

$pageName = 'Nieuw bericht';
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Authentication::checkAuthentication();
if (!Authentication::checkPrivilege("mail-scheduler")) {
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
        </div>
        <div class="container-fluid">
            <form method="post">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Template selecteren</label>
                            <select name='templateid'>
                                <?php foreach (MailTemplateMapper::getTemplatesByType('member') as $row): ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['subject']; ?></option>
                                <?php endforeach ?>
                            </select>
                            <input type="hidden" name="type" value="member">
                            <input type="submit" name="createMailWithTemplate">
                        </div>
                    </div>
                </div>

                <div class="form-group form-check">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="checkbox" id="selectall">
                            <label>Alles selecteren</label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-check">



                    <div id="example" class="row">
                    <?php
                    foreach (Member::getMembers() as $row):
                        if (!empty($row['emailadres'])) {
                            ?>

                        <div class="col-md-4">
                            <input type="checkbox" name='recipients[]' id="checkbox" value="<?php echo $row['id']; ?>"> <?php echo $row['voornaam'].' '.$row['achternaam']; ?><br/>
                        </div>
                    <?php
                        } else {
                            ?>
                        <div class="col-md-4">
                            <input type="checkbox" name='recipients[]' value="<?php echo $row['id']; ?>" disabled><s> <?php echo $row['voornaam'].' '.$row['achternaam']; ?></s><br/>
                        </div>
                        <?php
                        }
                    endforeach ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
<script>
  $( '#selectall' ).click( function () {
    $( '#example #checkbox' ).prop('checked', this.checked)
})
</script>
</body>
</html>
