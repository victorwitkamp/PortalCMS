<?php

use PortalCMS\Core\Filter\Csrf;
use PortalCMS\Core\View\Text;

?><h3><?=

    Text::get('LABEL_CHANGE_USERNAME') ?></h3>
<form method="post" validate=true>
    <div class="form-group row">
        <label for="user_name" class="col-sm-4 col-form-label"><?= Text::get('LABEL_NEW_USERNAME') ?></label>
        <?php // btw http://stackoverflow.com/questions/774054/should-i-put-input-tag-inside-label-tag
        ?>
        <div class="col-sm-8">
            <input type="text" name="user_name" class="form-control" required />
        </div>
    </div>
    <?php // set CSRF token at the end of the form
    ?>
    <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken() ?>" />
    <input type="submit" name="changeUsername" value="<?= Text::get('LABEL_SUBMIT') ?>" class="btn btn-primary" />
</form>