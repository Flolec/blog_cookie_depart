<?php
//si l'utilisateur n'est pas enregistré dans le tableau des sessions, on redirige vers la page de connexion
if (!isset($_SESSION['nom_user'])) {
    header("location:" . BASE_URL . "/public/connect.php");
    exit();
}
