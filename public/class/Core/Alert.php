<?php

class Alert
{
    public static function render($feedback, $style) {
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
     */
    public static function renderFeedbackMessages()
    {
        // Stored in $_SESSION["feedback_positive"] and $_SESSION["feedback_negative"]
        $feedback_positive = Session::get('feedback_positive');
        $feedback_warning = Session::get('feedback_warning');
        $feedback_negative = Session::get('feedback_negative');

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

        Session::set('feedback_positive', NULL);
        // unset($_SESSION['feedback_positive']);
        Session::set('feedback_warning', NULL);
        Session::set('feedback_negative', NULL);
    }
}
