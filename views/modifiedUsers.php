<?php
require_once('../classes/connection_class.php');
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
if (!isset($_SESSION['login'])){
    $_SESSION['login'] = $_GET['login'];
}
$dbh = Connection::get();
$stmt = $dbh->prepare("select * from users where login = :login limit 1");
$stmt->execute(array(
    ':login' => $_SESSION['login']
));
$users = $stmt->fetchAll(PDO::FETCH_CLASS);
?>

<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://puteborgne.sexy/_css/normalize.css" />
    <link rel="stylesheet" href="https://puteborgne.sexy/_css/skeleton.css" />
    <style>
        fieldset {
            border: 0.25rem solid rgba(225,225,225,0.5);
            border-radius: 4px;
            padding: 1rem 2rem;
        }
        .errors {
            color: #ff5555;
        }
    </style>
</head>

<body>
<?php require_once('../components/nav.php') ?>
<div class="container">
    <div class="row">
        <h1>formulaire modifier user</h1>
        <ul class="errors">
            <?php
            foreach( $errors as $error) {
                echo("<li>". $error . "</li>");
            }
            ?>
        </ul>
        <form method="post" action="../controllers/users_controller.php?action=modified"" id="usersForm">
            <fieldset>
                <?php
                foreach( $users as $user) {
                ?>
                <legend>user</legend>
                <label for="userLogin">Login</label>
                <input type="text" id="userLogin" name="login" value="<?php echo $user->login; ?>"/>
                <!--<label for="userPassword">Password (password is encode so you can modified password or not)</label>
                <input type="text" id="userPassword" name="password" value=""/>-->
                <label for="userFirstname">Handle</label>
                <input type="text" id="userHandle" name="handle" value="<?php echo $user->handle; ?>"/>
                <?php } ?>
            </fieldset>
            <input type="submit" value="Envoyer" class="button-primary">
        </form>
    </div>
    <div class="row">
        <div class="column">
            $_SESSION
            <pre><?php print_r($_SESSION) ?></pre>
        </div>
    </div>
</div>
</body>
</html>
