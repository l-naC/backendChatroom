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

    public function findAll()
    {
        $dbh = Connection::get();
        $stmt = $dbh->query("select * from messages where id_user = (SELECT id FROM users WHERE login = '".$_SESSION['login']."')");
        // recupere les users et fout le resultat dans une variable sous forme de tableau de tableaux
        $users = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $users;
    }

    public function delete($data){
        $dbh = Connection::get();
        $sql = "DELETE FROM `messages` WHERE `id`= :id";
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        print_r("DELETE FROM `messages` WHERE `id`= :id");
        if ($sth->execute(array(':id' => $data['id']))){
            return true;
        }
        return false;
    }
}