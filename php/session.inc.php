<?php

if (isset($_SESSION['nom_user'])) {
    $nom_session = $_SESSION['nom_user'];
    $id_user_session = $_SESSION['id_user'];
} else {
    $path = $_SERVER['PHP_SELF'];
    $file = basename($path);
    if ($file !== 'connect.php') header("location:" . BASE_URL . "/public/connect.php");
}
