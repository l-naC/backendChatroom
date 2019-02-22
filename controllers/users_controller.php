<?php
require_once('../models/users_class.php');
//demarrage session
session_start();

// try/catch pour lever les erreurs de connexion
try {


    $action = isset($_GET['action']) ? $_GET['action'] : '';

    $user = new User();
    switch ($action){
        case 'login':
            if ($user->login($_POST)){
                $_SESSION['errors'] = [];
                $_SESSION['login'] = $_POST['login'];
                $users = $user->findOnly($_SESSION['login']);
                header('Location: ../controllers/users_controller.php?action=list');
                die;
            }
            // put errors in $session
            $_SESSION['errors'] = $user->errors;
            header('Location: ../views/connection.php');
            break;

        case 'list':
            $_SESSION['errors'] = [];
            $users = $user->findOnly($_SESSION['login']);
            $_SESSION['users'] = $users;
            header('Location: ../views/users_list.php');
            break;
        case 'jsonlist':
            $users = $user->findAll();
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            header('Content-type: application/json; charset=UTF-8');
            echo json_encode($users, true);
            break;

        case 'register':
            if ($user->save($_POST)){
                $_SESSION['errors'] = [];
                header('Location: ../controllers/users_controller.php?action=list');
                die;
            }
            $_SESSION['errors'] = $user->errors;
            header('Location: ../views/formUsers.php');
            break;
        case 'deleted':
            if ($user->deleted($_GET)){
                header('Location: ../views/connection.php');
                die;
            }
            header('Location: ../views/users_list.php');
            break;
        case 'modified':
            if ($user->modified($_POST)){
                $_SESSION['errors'] = [];
                header('Location: ../controllers/users_controller.php?action=list');
                die;
            }
            $_SESSION['errors'] = $user->errors;
            header('Location: ../views/modifiedUsers.php');
            break;
        default:
            header('Location: ../views/connection.php');
            break;
    }
} catch (Exception $e) {
    echo('cacaboudin exception');
    print_r($e);
}
?>