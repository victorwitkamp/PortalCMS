<?php
declare(strict_types=1);

use PortalCMS\Core\Email\Template\EmailTemplatePDOReader;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Members\MemberModel;

$pageName = 'Nieuw bericht';

$year = (int) Request::get('year');
if (empty($year)) {
    $year = (int) date('Y');
}
?>
<?= $this->layout('layout', ['title' => $pageName]) ?>
<?= $this->push('head-extra') ?>

<script>
    $('#selectall').click(function() {
        $('#example #checkbox').prop('checked', this.checked)
    })
</script>
<?= $this->end() ?>
<?= $this->push('main-content') ?>

<div class="container">
    <div class="row mt-5">
        <div class="col-sm-12">
            <h1><?= $pageName ?></h1>
        </div>
    </div>
    <hr>
</div>
<div class="container">
    <form method="post">
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <label><?= Text::get('YEAR') ?></label>
                    <input type="number" name="year" value="<?= $year ?>" />
                    <input type="submit" name="generateMemberSetYear" />
                </div>
            </div>
        </div>
    </form>
    <form method="post">
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <label>Template selecteren</label>
                    <select name='templateid'>
                        <?php
                        $templates = EmailTemplatePDOReader::getByType('member');
                        foreach ($templates as $template) : ?>
                            <option value="<?= $template->id ?>"><?= $template->subject ?></option>
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

                $members = MemberModel::getMembersByYear($year);
                if (!empty($members)) {
                    $count = 0;
                    foreach ($members as $member) {
                        $count++;
                        if (!empty($member->emailadres)) { ?>
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name='recipients[]' id="customCheck<?= $count ?>" value="<?= $member->id ?>">
                                    <label class="custom-control-label" for="customCheck<?= $count ?>"><?= $member->voornaam . ' ' . $member->achternaam ?></label>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="col-md-4">
                                <input type="checkbox" class="custom-control-input" value="<?= $member->id ?>" disabled><s> <?= $member->voornaam . ' ' . $member->achternaam ?></s><br />
                            </div>
                        <?php }
                    }
                } else {
                    echo 'Geen resultaten';
                }
                ?>
            </div>
        </div>
    </form>
</div>
<?= $this->end();
