<?php
if (isset($_POST['logout'])) {
    $_SESSION = array();
    setcookie("PHPSESSID", "", time() - 3600, "/");
    session_destroy();
}
