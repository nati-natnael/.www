<?php
   /**
    * Builds an error message
    *
    * :param $errMsg: description of error
    */
    function errMsg($errMsg) {
       $msg  = "<div style='color: red; margin-top: 0.5em;'>";
      //  $msg .= "<img src='imgs/err_img.png'
      //                     alt='Check Mark Image'
      //                     style='width: 1.1em; height: 1.1em;'> ";
       $msg .= "<span style='padding: 0.1em;'>";
       $msg .= $errMsg;
       $msg .= "</span>";
       $msg .= "</div>";

       return $msg;
    }
?>
