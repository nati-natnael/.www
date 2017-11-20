<!-- Logout script -->
<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);

  session_start();
  $_SESSION = array();
  header('Location: login.php', true, 301);
  die();
?>
