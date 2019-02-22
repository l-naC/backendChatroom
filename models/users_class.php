<?php
require_once('../classes/connection_class.php');

Class User
{

    public $id;
    public $login;
    public $password;
    public $handle;

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

            $stmt = $dbh->prepare("select * from users where id = :id limit 1");
            $stmt->execute(array(
                ':id' => $id
            ));
            // recupere les users et fout le resultat dans une variable sous forme de tableau de tableaux
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
            $user = $stmt->fetch();

            $this->id = $user->id;
            $this->login = $user->login;
            $this->password = $user->password;
            $this->handle = $user->handle;
        }
    }

    public function validate($data)
    {
        $this->errors = [];

        /* required fields */
        if (!isset($data['login'])) {
            $this->errors[] = 'champ login vide';
        }
        if (!isset($data['password'])) {
            $this->errors[] = 'champ password vide';
        }
        /* tests de formats */
        if (isset($data['login'])) {
            if (empty($data['login'])) {
                $this->errors[] = 'champ login vide';
                // si name > 50 chars
            } else if (mb_strlen($data['login']) > 45) {
                $this->errors[] = 'champ login trop long (45max)';
            }
        }

        if (isset($data['password'])) {
            if (empty($data['password'])) {
                $this->errors[] = 'champ password vide';
                // si name > 50 chars
            } else if (mb_strlen($data['password']) < 8) {
                $this->errors[] = 'champ password trop court (8 min)';
            } else if (mb_strlen($data['password']) > 200) {
                $this->errors[] = 'champ password trop long (20 max)';
            }
        }

        if (isset($data['handle'])) {
            if (empty($data['handle'])) {
                $this->errors[] = 'champ pseudo vide';
                // si name > 50 chars
            } else if (mb_strlen($data['handle']) > 45) {
                $this->errors[] = 'champ handle trop long (45max)';
            }
        }

        if (count($this->errors) > 0) {
            return false;
        }
        return true;
    }

    private function loginExists($login = null)
    {
        if (!is_null($login)) {

            $dbh = Connection::get();
            $sql = "select count(id) from users where login = :login";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(
                ':login' => $login
            ));
            if ($sth->fetchColumn() > 0) {
                $this->errors[] = 'login deja pris';
                return true;
            }
        }
        return false;

    }

    public function findAll()
    {
        $dbh = Connection::get();
        $stmt = $dbh->query("select * from users");
        // recupere les users et fout le resultat dans une variable sous forme de tableau de tableaux
        $users = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $users;
    }

    public function findOnly($data)
    {
        $dbh = Connection::get();
        $stmt = $dbh->query("select * from users where login = '".$data."'");
        // recupere les users et fout le resultat dans une variable sous forme de tableau de tableaux
        $users = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $users;
    }

    public function save($data)
    {
        if ($this->validate($data)) {
            if(isset($data['id']) && !empty($data['id'])){
                // update
            }elseif ($this->loginExists($data['login'])){
                return false;
            }
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            /* syntaxe avec preparedStatements */
            $dbh = Connection::get();
            $sql = "insert into users (login, password, handle) values (:login, :password , :handle)";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            if ($sth->execute(array(
                ':login' => $data['login'],
                ':password' => $hashedPassword,
                ':handle' => $data['handle'],
            ))) {
                return true;
            } else {
                // ERROR
                // put errors in $session
                $this->errors['pas reussi a creer le user'];
            }
        }
        return false;
    }

    public function login($data)
    {
        if ($this->validate($data)) {
            $dbh = Connection::get();
            $sql = "select password from users where login = :login limit 1";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(
                ':login' => $data['login']
            ));
            $storedPassword = $sth->fetchColumn();
            if (password_verify($data['password'], $storedPassword)) {
                return true;

            } else {
                // ERROR
                $this->errors[] = 'CASSE TOI !';
            }
        }
        return false;
    }

    public function delete($data){
        $dbh = Connection::get();
        $sql = "DELETE FROM `users` WHERE `login`= :login";
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if ($sth->execute(array(':login' => $data['login']))){
            return true;
        }
        return false;
    }
}