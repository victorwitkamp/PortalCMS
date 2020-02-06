<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\View;

use PortalCMS\Core\Session\Session;

class Alert
{
    public static function render(string $feedback, string $style)
    {
        if (!empty($feedback) && !empty($style)) {
            ?><div class="alert alert-<?= $style ?> alert-dismissible fade show" role="alert"><?= $feedback ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div><?php
        }
    }

    /**
     * Renders the feedback messages into the view
     * Stored in $_SESSION["feedback_positive"] and $_SESSION["feedback_negative"]
     */
    public static function renderFeedbackMessages()
    {
        $feedback_positive = (array) Session::get('feedback_positive', false);
        $feedback_warning = (array) Session::get('feedback_warning', false);
        $feedback_negative = (array) Session::get('feedback_negative', false);

        if (isset($feedback_positive)) {
            foreach ($feedback_positive as $feedback) {
                self::render($feedback, 'success');
            }
        }

        if (isset($feedback_warning)) {
            foreach ($feedback_warning as $feedback) {
                self::render($feedback, 'warning');
            }
        }

        if (isset($feedback_negative)) {
            foreach ($feedback_negative as $feedback) {
                self::render($feedback, 'danger');
            }
        }

        Session::set('feedback_positive', null);
        Session::set('feedback_warning', null);
        Session::set('feedback_negative', null);
    }
}
