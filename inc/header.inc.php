<header>
    <div class="centrage">
        <nav>
            <ul class="containeurFlex">
                <li><a href="<?= BASE_URL ?>">Liste des articles</a></li>
                <li><a href="<?= BASE_URL ?>/public/admin/gestionUser.php">Liste des utilisateurs</a></li>
                <li>&nbsp;|&nbsp;</li>
                <li><a href="<?= BASE_URL ?>/public/admin/gestion.php">Gestion des articles</a></li>
                <li><a href="<?= BASE_URL ?>/public/admin/new.php">Ajout article</a></li>

                <?php
                if (isset($_SESSION['id_user'])) { ?>
                    <li>Bonjour <?= $_SESSION['nom_user']; ?></li>
                    <li>
                        <form method="post" action="<?= BASE_URL ?>/">
                            <button type="submit" name="logout" class="btn"><i class="fa fa-sign-out" aria-hidden="true"></i></button>
                        </form>
                    </li>
                <?php
                } else { ?> <li><a href="<?= BASE_URL ?>/public/connect.php">Connexion</a></li>
                <?php  }  ?>


            </ul>
        </nav>
    </div>
</header>