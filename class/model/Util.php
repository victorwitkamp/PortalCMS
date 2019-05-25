<?php

/**
 * Class : Util (Util.class.php)
 * Details : Util Class.
 */

class Util
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public static function displayMessage() {
        $time = date('H:i:s');
        $class = array(
            "info"=>"alert alert-info alert-dismissible fade show",
            "warning"=>"alert alert-warning alert-dismissible fade show",
            "error"=>"alert alert-danger alert-dismissible fade show",
            "success"=>"alert alert-success alert-dismissible fade show"
        );
        $button = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        echo '<div>';
        if (isset($_SESSION['response']) && !empty($_SESSION['response'])) {
                // Associative Array
                // Example: $_SESSION['response'][] = array("status"=>"info","message"=>"De sessie is hervat.");
                foreach ($_SESSION['response'] as $key => $value) {
                    echo '<div class="';
                    echo $class[$value['status']];
                    echo '">';
                    echo $time;
                    echo ' - ';
                    echo $value['status'];
                    echo ' - ';
                    echo stripslashes($value['message']).$button.'</div>';
                }
        }
        echo '</div>';
        unset($_SESSION['response']);
    }

    public static function displayPopup() {
        if (isset($_SESSION['popup']) && !empty($_SESSION['popup'])) {
            // Associative Array
            // EXAMPLE: $_SESSION['popup'][] = array("title"=>"Titel","message"=>"Bericht.");
            foreach ($_SESSION['popup'] as $key => $value) {
              include '\include\modal.php';
            }
        }
        unset($_SESSION['popup']);
    }


}