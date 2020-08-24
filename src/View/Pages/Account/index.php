<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Config\Config;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Text;

$pageName = Text::get('TITLE_MY_ACCOUNT');
require DIR_ROOT . 'login/ext/fb/config.php';
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email'];
$loginUrl = $helper->getLoginUrl(Config::get('FB_ASSIGN_URL'), $permissions);
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>
<!--    <script src="/includes/js/pass_req.js"></script>-->
<?= $this->end() ?>
<?= $this->push('main-content') ?>
    <div class="container">
        <div class="row mt-5">
            <h1><?= $pageName ?></h1>
        </div>
        <hr>
        <?php Alert::renderFeedbackMessages(); ?>
    </div>
    <div class="container">
        <?php
        require DIR_VIEW . 'Pages/Account/inc/accountDetails.inc.php';
        require DIR_VIEW . 'Pages/Account/inc/changePassword.inc.php';
        require DIR_VIEW . 'Pages/Account/inc/changeUsername.inc.php';
        ?>
    </div>
<?= $this->end() ?>
<?= $this->push('scripts') ?>
    <script type="text/javascript">
        function validatePassword() {
            document.getElementById("newPassword").value === document.getElementById("newConfirmPassword").value ?
                document.getElementById("newConfirmPassword").setCustomValidity("") :
                document.getElementById("newConfirmPassword").setCustomValidity("Passwords do not match. Please repeat the password to confirm it.")
        }
        window.onload = function () {
            document.getElementById("newPassword").onchange = validatePassword,
            document.getElementById("newConfirmPassword").onchange = validatePassword,
            Array.prototype.filter.call(document.getElementsByClassName("needs-validation"), function (e) {
                e.addEventListener("submit", function (t) {
                    e.checkValidity() || (t.preventDefault(),
                        t.stopPropagation()),
                        e.classList.add("was-validated")
                }, !1)
            })
        };
    </script>
    <!--    <script>-->
    <!--        $('#newPassword').PassRequirements({-->
    <!--            popoverPlacement: 'bottom',-->
    <!--            defaults: true,-->
    <!--            trigger: 'click'-->
    <!--        });-->
    <!--    </script>-->
<?= $this->end();
