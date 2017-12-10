<?php
   /**
    * Builds an error message
    *
    * :param $errMsg: description of error
    */
    function errMsg($errMsg) {
       $msg  = "<div style='padding: 0.25em 0;
                            color: red;'>";
       $msg .= "<img src='imgs/err_img.png'
                          alt='[Red X Mark Image]'
                          style='width: 1.1em;
                                 height: 1.1em;
                                 vertical-align: top;'> ";
       $msg .= "<span style='padding: 0.1em;'>";
       $msg .= $errMsg;
       $msg .= "</span>";
       $msg .= "</div>";

       return $msg;
    }

    function successMsg($success) {
      $msg  = "<div style='padding: 0.25em 0;
                           color: green;'>";
      $msg .= "<img src='imgs/check_mark.png'
                    alt='[Green check Mark Image]'
                    style='width: 1.1em; height: 1.1em;'> ";
      $msg .= "<span style='vertical-align: top'>";
      $msg .= $success;
      $msg .= "</span>";
      $msg .= "</div>";

      return $msg;
    }
?>
