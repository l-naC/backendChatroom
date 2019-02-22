<?php
require_once('../models/chatrooms_class.php');
//demarrage session
session_start();

// try/catch pour lever les erreurs de connexion
try {

    $action = isset($_GET['action']) ? $_GET['action'] : '';

    $chatroom = new Chatroom();
    switch ($action){
        case 'list':
            $_SESSION['errors'] = [];
            $chatrooms = $chatroom->findAll();
            $_SESSION['chatrooms'] = $chatrooms;
            header('Location: ../views/chatrooms_list.php');
            break;
        case 'register':
            if ($chatroom->save($_POST)){
                $_SESSION['errors'] = [];
                header('Location: ../controllers/chatrooms_controller.php?action=list');
                die;
            }
            $_SESSION['errors'] = $chatroom->errors;
            header('Location: ../views/formChatrooms.php');
            break;
        case 'modified':
            $_SESSION['title'] = $_GET['title'];
            if ($chatroom->modified($_POST)){
                $_SESSION['errors'] = [];
                header('Location: ../controllers/chatrooms_controller.php?action=list');
                die;
            }
            $_SESSION['errors'] = $chatroom->errors;
            header('Location: ../views/modifiedChatrooms.php');
            break;
        case 'messages':
            $_SESSION['errors'] = [];
            $chatrooms = $chatroom->findAllMessages($_GET['title']);
            $_SESSION['chatrooms'] = $chatrooms;
            header('Location: ../views/chatroom_messages_list.php');
            break;
        default;
            header('Location: ../views/chatrooms_list.php');
            break;
    }
} catch (Exception $e) {
    echo('cacaboudin exception');
    print_r($e);
}
?>