<?php
session_start();
require_once  '../../php/config_perso.inc.php';
require_once  '../../php/db_user.inc.php';
require_once  '../../php/utils.inc.php';

use Blog\UserRepository;

$message = $messageErreur = '';
$userRepository = new UserRepository();
$users = $userRepository->getAllUsers($messageErreur);

?>

<?php include   '../../inc/head.inc.php' ?>
<?php include   '../../inc/header.inc.php' ?>
<main class="centrage ">

    <h1>Gestion des utilisateurs</h1>

    <?php
    afficherAlerte($message, 'success');
    afficherAlerte($messageErreur, 'danger');
    ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user->id; ?></td>
                    <td><?= nettoyage($user->nom); ?></td>
                    <td><?= ($user->is_admin) ? "Admin" : "Simple User"; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>


<?php include  '../../inc/footer.inc.php' ?>