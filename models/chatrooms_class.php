<?php
require_once('../classes/connection_class.php');

class Chatroom
{
    public $id;
    public $title;
    public $id_user;

    public $errors = [];

    public function __construct($id = null)
    {
        if (!is_null($id)) {
            $this->get($id);
        }
    }

    public function get($id = null)
    {
        if (!is_null($id)) {
            $dbh = Connection::get();
            //print_r($dbh);

            $stmt = $dbh->prepare("select * from chatrooms where id = :id limit 1");
            $stmt->execute(array(
                ':id' => $id
            ));
            // recupere les users et fout le resultat dans une variable sous forme de tableau de tableaux
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Chatroom');
            $chatroom = $stmt->fetch();

            $this->id = $chatroom->id;
            $this->title = $chatroom->title;
            $this->id_user = $chatroom->id_user;
        }
    }

    public function findAll()
    {
        $dbh = Connection::get();
        $stmt = $dbh->query("select * from chatrooms where id_user = (SELECT id FROM users WHERE login = '".$_SESSION['login']."')");
        // recupere les users et fout le resultat dans une variable sous forme de tableau de tableaux
        $users = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $users;
    }

    public function validateModif($data)
    {
        $this->errors = [];

        /* required fields */
        if (!isset($data['title'])) {
            $this->errors[] = 'champ title vide';
        }
        /* tests de formats */
        if (isset($data['title'])) {
            if (empty($data['title'])) {
                $this->errors[] = 'champ title vide';
                // si name > 50 chars
            } else if (mb_strlen($data['title']) > 45) {
                $this->errors[] = 'champ title trop long (45max)';
            }
        }

        if (!isset($data['id_user'])) {
            $this->errors[] = 'champ id_user vide';
        }

        if (count($this->errors) > 0) {
            return false;
        }
        return true;
    }

    public function modified($data)
    {
        if ($this->validateModif($data)) {

            /* syntaxe avec preparedStatements */
            $dbh = Connection::get();
            $sql = "UPDATE chatrooms SET title = :title,  id_user = :id_user WHERE title = '".$_SESSION['title']."'";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            if ($sth->execute(array(
                ':title' => $data['title'],
                ':id_user' => $data['id_user'],
            ))) {
                $_SESSION['title'] = $data['title'];
                return true;
            } else {
                // ERROR
                // put errors in $session
                $this->errors['pas reussis a modifier le user'];
            }
        }
        return false;
    }

}