<?php

/**
 * Class : Util (Util.class.php)
 * Details : Util Class.
 */

class Util 
{
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
                    echo '<div class="'.$class[$value['status']].'">'.$time.' - '.$value['status'].' - '.stripslashes($value['message']).$button.'</div>';
                }
        }
        echo '</div>';
        unset($_SESSION['response']);
    }

    public static function displayPopup() {
        $beforetitle = '<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">';
                $aftertitle= '</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">';
        $after = '</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div><script>$("#exampleModalCenter").modal("show")</script>';

        if (isset($_SESSION['popup']) && !empty($_SESSION['popup'])) {
            // Associative Array
            // EXAMPLE: $_SESSION['popup'][] = array("title"=>"Titel","message"=>"Bericht.");
            foreach ($_SESSION['popup'] as $key => $value) {
                echo $beforetitle.stripslashes($value['title']).$aftertitle.stripslashes($value['message']).$after;
            }
        }
        unset($_SESSION['popup']);
    }


}