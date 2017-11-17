<?php
   /**
    * Builds an error message
    *
    * :param $errMsg: description of error
    */
    function errMsg($errMsg) {
       $msg  = "<br><span style='color: red;'>";
       $msg .= "<img src='imgs/err_img.png'
                          alt='Check Mark Image'
                          style='width: 1.1em; height: 1.1em;'> ";
       $msg .= "<span style='vertical-align: top'>";
       $msg .= $errMsg;
       $msg .= "</span>";
       $msg .= "</span>";
    
       return $msg;
    }
?>