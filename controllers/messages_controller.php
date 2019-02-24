<?php
require_once('../models/messages_class.php');
//demarrage session
session_start();

// try/catch pour lever les erreurs de connexion
try {

    $action = isset($_GET['action']) ? $_GET['action'] : '';

    $message = new Message();
    switch ($action){
        /*case 'deleted':
            echo $_GET['id'];
            $_SESSION['id'] = $_GET['id'];
            echo $_SESSION['id'];
            if ($message->delete($_GET)){
                header('Location: ../controllers/messages_controller.php?action=list');
                die;
            }
            header('Location: ../views/messages_list.php');
            break;*/
        case 'deleted':
            header("Access-Control-Allow-Headers: *");
            header('Access-Control-Allow-Origin: *');
            header('Content-type: application/json; charset=UTF-8');

            $message_info = json_decode(file_get_contents('php://input'));

            $is_okay = $message->delete($message_info);
            echo json_encode($is_okay);
            break;
        /*case 'list':
            $_SESSION['errors'] = [];
            $messages = $message->findAll();
            $_SESSION['messages'] = $messages;
            header('Location: ../views/messages_list.php');
            break;*/
        case 'list':
            header("Access-Control-Allow-Headers: *");
            header('Access-Control-Allow-Origin: *');
            header('Content-type: application/json; charset=UTF-8');
            $message_info = json_decode(file_get_contents('php://input'));
            $is_okay = $message->findAll($message_info);
            echo json_encode($is_okay);
            break;
        case 'register':
            header("Access-Control-Allow-Headers: *");
            header('Access-Control-Allow-Origin: *');
            header('Content-type: application/json; charset=UTF-8');
            $message_info = json_decode(file_get_contents('php://input'));
            $is_okay = $message->register($message_info);
            echo json_encode($is_okay);
            break;
        default;
            header('Location: ../views/messages_list.php');
            break;
    }
} catch (Exception $e) {
    echo('cacaboudin exception');
    print_r($e);
}
?>