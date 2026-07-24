<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

$pageName = 'Nieuw bericht';

?>
<?= $this->layout('View::Layout/ApplicationLayout', [ 'title' => $pageName ]) ?>
<?= $this->push('head-extra') ?>


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
        <form method="post" action="/Email/GenerateMember/Year">
            <div class="mb-3">
                <div class="row">
                    <div class="col-md-12">
                        <label><?= Text::get('YEAR') ?></label>
                        <input type="number" name="year" value="<?= $year ?>"/>
                        <button type="submit">Jaar selecteren</button>
                    </div>
                </div>
            </div>
        </form>
        <form method="post" action="/Email/GenerateMember">
            <div class="mb-3">
                <div class="row">
                    <div class="col-md-12">
                        <label>Template selecteren</label>
                        <select name='templateid'>
                            <?php
                            foreach ($mailTemplates as $template) : ?>
                                <option value="<?= $template->id ?>"><?= $template->subject ?></option>
                            <?php endforeach ?>
                        </select>
                        <input type="hidden" name="type" value="member">
                        <button type="submit">Berichten aanmaken</button>
                    </div>
                </div>
            </div>


            <div class="mb-3 form-check">
                <div class="row">
                    <div class="col-md-12">
                        <input type="checkbox" id="selectall">
                        <label>Alles selecteren</label>
                    </div>
                </div>
            </div>

            <div class="mb-3 form-check">
                <div id="example" class="row">
                    <?php

                    if (!empty($members)) {
                        $count = 0;
                        foreach ($members as $member) {
                            $count++;
                            if (!empty($member->emailadres)) { ?>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name='recipients[]'
                                               id="customCheck<?= $count ?>" value="<?= $member->id ?>">
                                        <label class="form-check-label"
                                               for="customCheck<?= $count ?>"><?= $member->voornaam . ' ' . $member->achternaam ?></label>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="col-md-4">
                                    <input type="checkbox" class="form-check-input" value="<?= $member->id ?>"
                                           disabled><s> <?= $member->voornaam . ' ' . $member->achternaam ?></s><br/>
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
        <script>
            document.getElementById('selectall').onclick = function () {
                var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                for (var checkbox of checkboxes) {
                    checkbox.checked = this.checked;
                }
            }
        </script>
    </div>
<?= $this->end();
