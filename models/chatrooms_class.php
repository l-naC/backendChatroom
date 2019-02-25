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

    public function findAll($data)
    {
        $dbh = Connection::get();
        //$stmt = $dbh->query("select * from chatrooms where id_user = (SELECT id FROM users WHERE login = '".$_SESSION['login']."')");
        $stmt = $dbh->query("select * from chatrooms where id_user = '".$data->id_user."'");
        // recupere les users et fout le resultat dans une variable sous forme de tableau de tableaux
        $chatrooms = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $chatrooms;
    }

    public function find($data)
    {
        $dbh = Connection::get();
        //$stmt = $dbh->query("select * from chatrooms where id_user = (SELECT id FROM users WHERE login = '".$_SESSION['login']."')");
        $stmt = $dbh->query("select * from chatrooms where id_user != '".$data->id_user."'");
        // recupere les users et fout le resultat dans une variable sous forme de tableau de tableaux
        $chatrooms = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $chatrooms;
    }

    public function validate($data)
    {
        $this->errors = [];

        /* required fields */
        if (!isset($data->title)) {
            $this->errors[] = 'champ title vide';
        }
        /* tests de formats */
        if (isset($data->title)) {
            if (empty($data->title)) {
                $this->errors[] = 'champ title vide';
                // si name > 50 chars
            } else if (mb_strlen($data->title) > 45) {
                $this->errors[] = 'champ title trop long (45max)';
            }
        }

        if (count($this->errors) > 0) {
            return false;
        }
        return true;
    }

    public function modified($data)
    {
        if ($this->validate($data)) {
            if (isset($data->title)) {
                $dbh = Connection::get();
                $sql = "select count(id) from chatrooms where title = :title";
                $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $sth->execute(array(
                    ':name' => $data->title
                ));
                if ($sth->fetchColumn() > 0) {
                    return $errors[] = ['Ce nom de chatroom a déjé été pris pas un utilisateur !'];
                }
            }
            /* syntaxe avec preparedStatements */
            $dbh = Connection::get();
            //$sql = "UPDATE chatrooms SET title = :title, modified = :modified WHERE title = '".$_SESSION['title']."'";
            $sql = "UPDATE chatrooms SET title = :title, modified = :modified WHERE id = '".$data->id."'";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            if ($sth->execute(array(
                ':title' => $data->title,
                ':modified' => date("Y-m-d H:i:s")
            ))) {
                $_SESSION['title'] = $data->title;
                return 'success';
            } else {
                // ERROR
                // put errors in $session
                $this->errors[] = 'pas reussi a modifier la chatroom';
                return $this->errors;
            }
        }
    }

    public function save($data)
    {
        if ($this->validate($data)) {
            /* syntaxe avec preparedStatements */
            $dbh = Connection::get();
            /*$stmt = $dbh->query("SELECT * FROM users WHERE login = '".$_SESSION['login']."'");
            // recupere les users et fout le resultat dans une variable sous forme de tableau de tableaux
            $users = $stmt->fetch();*/
            $sql = "insert into chatrooms(title, id_user) values (:title, :id_user)";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            if ($sth->execute(array(
                ':title' => $data->title,
                ':id_user' => $data->id_user
            ))) {
                return 'success';
            } else {
                // ERROR
                // put errors in $session
                $this->errors[] = 'pas reussi a creer la chatroom';
                return $this->errors;
            }
        }
    }

    public function findAllMessages($data)
    {
        $dbh = Connection::get();
        //$sql_user="(SELECT id FROM users WHERE login = '".$_SESSION['login']."')";
        //$sql_chatroom="(SELECT id FROM chatrooms WHERE title = '".$data."')";
        //$stmt = $dbh->query("select * from messages where id_user = $sql_user AND id_chatroom = $sql_chatroom");
        //$stmt = $dbh->query("select * from messages where id_user = $sql_user AND id_chatroom = $data->id");
        $stmt = $dbh->query("select * from messages where id_chatroom = '".$data->id."'");
        // recupere les users et fout le resultat dans une variable sous forme de tableau de tableaux
        $messages = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $messages;
    }

}