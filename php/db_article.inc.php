<?php

namespace Blog;

require 'db_link.inc.php';

use DB\DBLink;
use PDO;

/**
 * Représente un article du blog
 */
class Article
{
    public $id;
    public $titre;
    public $contenu;
    public $nom_img;
}

/**
 * Classe ArticleRepository : gestionnaire des articles
 */

class ArticleRepository
{
    const TABLE_NAME = 'blog_article';
    /**
     * Récupère tous les articles depuis la base de données.
     *
     * @param string &$message Référence à une variable de message d'état
     * @param string $tri Champ utilisé pour le tri (id par defaut)
     * @return array Tableau d'objets Article ou tableau vide
     */
    public function getAllArticles(string &$message, string $tri = 'id'): array
    {
        $result = [];
        $bdd = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            if (!$bdd) return $result;
            //protection 
            if (!in_array($tri, ['id', 'titre', 'date'], true)) {
                $tri = 'id';
            }
            $sql = "SELECT * FROM " . self::TABLE_NAME . " ORDER BY $tri";
            $stmt = $bdd->prepare($sql);

            if ($stmt->execute()) {
                $stmt->setFetchMode(PDO::FETCH_CLASS, "Blog\Article");
                $result = $stmt->fetchAll();
            } else {
                $message .= "Erreur lors de l'exécution de la requête (code : " . $stmt->errorCode() . ").<br>";
            }

            $stmt = null;
        } catch (\Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }

        DBLink::disconnect($bdd);
        return $result;
    }

    /**
     * Récupère un article à partir de son identifiant.
     *
     * @param int $id Identifiant de l'article
     * @param string &$message Référence à une variable de message d'état
     * @return \Blog\Article|null Article trouvé ou null si non trouvé
     */

    public function getArticleById(int $id, string &$message): ?\Blog\Article
    {
        $result = null;
        $bdd    = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            if (!$bdd) return $result;
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE id = :id_article");
            $stmt->bindValue(':id_article', $id, \PDO::PARAM_INT);
            if ($stmt->execute()) {
                $obj = $stmt->fetchObject(\Blog\Article::class);
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
     * Insère un nouvel article dans la base de données.
     *
     * @param \Blog\Article $article Objet représentant l'article à insérer (doit contenir `titre` et `contenu`)
     * @param string &$message Référence à une variable de message d'état
     * @return bool Retourne `true` si l'insertion est réussie, sinon `false`
     */
    public function insertArticle(\Blog\Article $article, string &$message): bool
    {
        $noError = false;
        $bdd   = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            if (!$bdd) return $noError;
            $stmt = $bdd->prepare("INSERT INTO " . self::TABLE_NAME . " (titre, contenu, nom_img ) VALUES (:titre, :contenu, :nom_img )");
            $stmt->bindValue(':titre', $article->titre);
            $stmt->bindValue(':contenu', $article->contenu);
            $stmt->bindValue(':nom_img', $article->nom_img);
            if ($stmt->execute()) {
                $noError = true;
            } else {
                $message .= 'Une erreur système est survenue.<br> 
                    Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. 
                    (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (\Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $noError;
    }

    /**
     * Supprime un article
     *
     * @param String $id Identifiant de l'article à supprimer
     * @param string &$message Référence à une variable de message d'état
     * @return bool Retourne `true` si la suppression est réussie, sinon `false`     *
     */
    public static function deleteArticle(int $id, string &$message): bool
    {
        $noError = false;
        $bdd   = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            if (!$bdd) return $noError;
            $stmt = $bdd->prepare("DELETE FROM  " . self::TABLE_NAME . " WHERE id = :id_article");
            $stmt->bindValue(':id_article', $id);
            if ($stmt->execute()) {
                $noError = true;
            } else {
                $message .= 'Une erreur système est survenue.<br> 
                    Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. 
                    (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (\Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $noError;
    }
    /**
     * Modifie un article existant dans la base de données.
     *
     * @param \Blog\Article $article Objet article à modifier (doit contenir `id`, `titre`, `contenu`)
     * @param string &$message Référence à une variable de message d'état
     * @return bool Retourne `true` si la modification est réussie, sinon `false`
     */
    public static function updateArticle(\Blog\Article $article, string &$message): bool
    {
        $noError = false;
        $bdd   = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            if (!$bdd) return $noError;
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET titre = :titre, contenu = :contenu, nom_img= :nom_img    WHERE id = :id_article");
            $stmt->bindValue(':id_article', $article->id);
            $stmt->bindValue(':titre', $article->titre);
            $stmt->bindValue(':contenu', $article->contenu);
            $stmt->bindValue(':nom_img', $article->nom_img);
            if ($stmt->execute()) {
                $noError = true;
            } else {
                $message .= 'Une erreur système est survenue.<br> 
                    Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. 
                    (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (\Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $noError;
    }
}
