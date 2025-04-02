<?php
session_start();
require_once  '../php/config_perso.inc.php';
require_once  '../php/db_user.inc.php';
require_once  '../php/utils.inc.php';

use Blog\UserRepository;

//si l'utilisateur est connecté, on redirige vers son dashboard
if (isset($_SESSION['nom_user'])) {
    header("location:" . BASE_URL . "/public/admin/gestion.php");
}


$message = "";

$username = nettoyage($_POST['username'] ?? '');
$password = nettoyage($_POST['password'] ?? '');

if (isset($_POST['buttLogin'])) {
    $userRepository = new UserRepository();
    $user = $userRepository->checkLogin($username, $password, $message);

    if (!$user) {
        $message = 'Nom d\'utilisateur ou mot de passe incorrect';
    } else {
        $_SESSION['nom_user'] =  $user->nom;
        $_SESSION['id_user'] =  $user->id;
        header("location:" . BASE_URL . "/public/admin/gestion.php");
    }
}

?>
<?php include   '../inc/head.inc.php' ?>
<?php include   '../inc/header.inc.php' ?>
<main class="centrage boxOmbre">
    <h1>Connexion</h1>
    <?php
    afficherAlerte($message, 'danger'); ?>
    <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" autocomplete="off">
        <label for="username"> Nom d'utilisateur: </label><input type="text" id="username" name="username" value="<?= $username ?>">
        <label for="password"> Mot de passe: </label><input type="password" id="password" name="password" value="<?= $password ?>">
        <input class="btn btn-theme" type="submit" name="buttLogin" value="Se connecter">
    </form>

</main>

<?php include  '../inc/footer.inc.php' ?>