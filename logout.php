<!-- Logout script -->
<?php
    session_start();
    session_destroy();
    $_SESSION = [];
    header('Location: login.php', true, 301);
	die();
?>