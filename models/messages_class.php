<?php
require_once('../classes/connection_class.php');

class Message
{
    public $id;
    public $content;
    public $id_user;
    public $id_chatroom;

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

            $stmt = $dbh->prepare("select * from messages where id = :id limit 1");
            $stmt->execute(array(
                ':id' => $id
            ));
            // recupere les users et fout le resultat dans une variable sous forme de tableau de tableaux
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Message');
            $message = $stmt->fetch();

            $this->id = $message->id;
            $this->content = $message->content;
            $this->id_user = $message->id_user;
            $this->id_chatroom = $message->id_chatroom;
        }
    }

    public function findAll($data)
    {
        $dbh = Connection::get();
        //$stmt = $dbh->query("select * from messages where id_user = (SELECT id FROM users WHERE login = '".$_SESSION['login']."')");
        $stmt = $dbh->query("select * from messages where id_user = '".$data->id_user."'");
        // recupere les users et fout le resultat dans une variable sous forme de tableau de tableaux
        $users = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $users;
    }

    public function delete($data){
        $dbh = Connection::get();
        if (isset($data->id)) {
            $sql = "DELETE FROM `messages` WHERE `id`= :id";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            if ($sth->execute(array(':id' => $data->id))){
                return 'success';
            }
        }
    }

    public function register($data)
    {
        $dbh = Connection::get();
        if (isset($data->id_chatroom) && isset($data->content) && isset($data->id_user)) {

            $sql = "insert into messages(content, id_user, id_chatroom, handle_user) values (:content, :id_user, :id_chatroom, :handle_user)";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            if ($sth->execute(array(
                ':content' => $data->content,
                ':id_user' => $data->id_user,
                ':id_chatroom' => $data->id_chatroom,
                ':handle_user' => $data->handle_user
            ))) {
                return 'success';
            } else {
                // ERROR
                // put errors in $session
                $this->errors[] = 'Impossible d\'envoyer le message';
                return $this->errors;
            }
        }
    }
}