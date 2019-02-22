<?php
require_once('../models/messages_class.php');
//demarrage session
session_start();

// try/catch pour lever les erreurs de connexion
try {

    $action = isset($_GET['action']) ? $_GET['action'] : '';

    $message = new Message();
    switch ($action){
        case 'deleted':
            echo $_GET['id'];
            $_SESSION['id'] = $_GET['id'];
            echo $_SESSION['id'];
            if ($message->delete($_GET)){
                header('Location: ../controllers/messages_controller.php?action=list');
                die;
            }
            header('Location: ../views/messages_list.php');
            break;
        case 'list':
            $_SESSION['errors'] = [];
            $messages = $message->findAll();
            $_SESSION['messages'] = $messages;
            header('Location: ../views/messages_list.php');
            break;
        default;
            //requete qui doit retourner des resultats
            $stmt = $dbh->query("select * from messages");
            $messages = $stmt->fetchAll(PDO::FETCH_CLASS);
            $_SESSION['messages'] = $messages;
            header('Location: ../views/messages_list.php');
            break;
    }
} catch (Exception $e) {
    echo('cacaboudin exception');
    print_r($e);
}
?>