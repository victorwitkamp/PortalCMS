<?php

/**
 * Class View
 * The part that handles all the output
 */
class View
{

    /**
     * Renders pure JSON to the browser, useful for API construction
     * @param $data
     */
    public function renderJSON($data)
    {
        header("Content-Type: application/json");
        echo json_encode($data);
    }

    /**
     * renders the feedback messages into the view
     */
    public static function renderFeedbackMessages()
    {
        // echo out the feedback messages (errors and success messages etc.),
        // they are in $_SESSION["feedback_positive"] and $_SESSION["feedback_negative"]

        // get the feedback (they are arrays, to make multiple positive/negative messages possible)
        $feedback_positive = Session::get('feedback_positive');
        $feedback_negative = Session::get('feedback_negative');

        // echo out positive messages
        if (isset($feedback_positive)) {
            foreach ($feedback_positive as $feedback) {
                // echo '<div class="feedback success">'.$feedback.'</div>';
                echo '<div class="alert alert-success alert-dismissible fade show">'.$feedback.'</div>';
            }
        }
        // echo out negative messages
        if (isset($feedback_negative)) {
            foreach ($feedback_negative as $feedback) {
                // echo '<div class="feedback error">'.$feedback.'</div>';
                echo '<div class="alert alert-danger alert-dismissible fade show">'.$feedback.'</div>';
            }
        }
        // delete these messages (as they are not needed anymore and we want to avoid to show them twice
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    }

    /**
     * Converts characters to HTML entities
     * This is important to avoid XSS attacks, and attempts to inject malicious code in your page.
     *
     * @param  string $str The string.
     * @return string
     */
    public function encodeHTML($str)
    {
        return htmlentities($str, ENT_QUOTES, 'UTF-8');
    }
}
