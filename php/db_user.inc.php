<?php

namespace Blog;

require 'db_link.inc.php';

use DB\DBLink;
use PDO;

/**
 * Représente un utilisateur du blog
 */
class User
{
    public $id;
    public $nom;
    public $mdp;
    public $is_admin;
}

/**
 * Classe UserRepository : gestionnaire des utilisateurs
 */

class UserRepository
{
    const TABLE_NAME = 'blog_user';
    /**
     * Récupère tous les utilisateurs depuis la base de données.
     *
     * @param string &$message Référence à une variable de message d'état
     * @return  array Tableau vide | tableau peuplé d'objets  \Blog\User
     */
    public function getAllUsers(string &$message): array
    {
        $result = [];
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            if (!$bdd) return $result;
            $stmt  = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " order by id", PDO::FETCH_CLASS,  \Blog\User::class);
            $result = $stmt->fetchAll();
        } catch (\Exception $e) {
            $message .= $e->getMessage() . '<br>';
        } finally {
            DBLink::disconnect($bdd);
        }
        return $result;
    }

    /**
     * Récupère un utilisateur à partir de son identifiant.
     *
     * @param int $id Identifiant de l'utilisateur
     * @param string &$message Référence à une variable de message d'état
     * @return \Blog\User|null utilisateur trouvé ou null si non trouvé
     */

    public function getUserById(int $id, string &$message): ?\Blog\User
    {
        $result = null;
        $bdd    = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            if (!$bdd) return $result;
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE id = :id_user");
            $stmt->bindValue(':id_user', $id, \PDO::PARAM_INT);
            if ($stmt->execute()) {
                $obj = $stmt->fetchObject(\Blog\User::class);
                $result = ($obj !== false ? $obj : null);
            } else {
                $message .= 'Une erreur système est survenue.<br> 
                    Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. 
                    (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
        } catch (\Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    /**
     * Vérifie les identifiants de connexion d'un utilisateur.
     *
     * @param string $username Nom d'utilisateur
     * @param string $password Mot de passe en clair (sera haché)
     * @param string &$message Référence à une variable de message d'état
     * @return \Blog\User|null Objet utilisateur si authentifié, sinon null
     */
    public function checkLogin(string $username, string $password, string &$message): ?\Blog\User
    {
        $result = null;
        $bdd = null;
        $hashedPassword = hash("sha512", $password);

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            if (!$bdd) return  $result;

            $stmt = $bdd->prepare(
                "SELECT * FROM " . self::TABLE_NAME . "  WHERE nom = :username AND mdp = :password"
            );
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':password', $hashedPassword);

            if ($stmt->execute()) {
                $obj = $stmt->fetchObject(\Blog\User::class);
                $result = $obj !== false ? $obj : null;
            } else {
                $message .= 'Une erreur système est survenue.<br> 
                Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. 
                (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
        } catch (\Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }

        DBLink::disconnect($bdd);
        return $result;
    }
}
