<?php

namespace PortalCMS\Core\View;

use PortalCMS\Core\Session\Session;

class Alert
{
    public static function render($feedback, $style)
    {
        if (!empty($feedback)) {
            if (!empty($style)) {
                echo '<div class="alert alert-';
                echo $style;
                echo ' alert-dismissible fade show" role="alert">';
                echo $feedback;
                echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                echo '<span aria-hidden="true">&times;</span>';
                echo '</button>';
                echo '</div>';
            }
        }
    }

    /**
     * Renders the feedback messages into the view
     * Stored in $_SESSION["feedback_positive"] and $_SESSION["feedback_negative"]
     */
    public static function renderFeedbackMessages()
    {
        $feedback_positive = Session::get('feedback_positive');
        $feedback_warning = Session::get('feedback_warning');
        $feedback_negative = Session::get('feedback_negative');

        if (isset($feedback_positive)) {
            foreach ((array) $feedback_positive as $feedback) {
                self::render($feedback, 'success');
            }
        }

        if (isset($feedback_warning)) {
            foreach ((array) $feedback_warning as $feedback) {
                self::render($feedback, 'warning');
            }
        }

        if (isset($feedback_negative)) {
            foreach ((array) $feedback_negative as $feedback) {
                self::render($feedback, 'danger');
            }
        }

        Session::set('feedback_positive', null);
        // unset($_SESSION['feedback_positive']);
        Session::set('feedback_warning', null);
        Session::set('feedback_negative', null);
    }
}
