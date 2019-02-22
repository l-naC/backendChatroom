<?php
require_once('../classes/connection_class.php');
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
if (!isset($_SESSION['login'])){
}
$_SESSION['title'] = $_GET['title'];
$dbh = Connection::get();
$stmt = $dbh->prepare("select * from chatrooms where title = :title limit 1");
$stmt->execute(array(
    ':title' => $_SESSION['title']
));
$chatrooms = $stmt->fetchAll(PDO::FETCH_CLASS);

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
        <h1>formulaire modifier chatroom</h1>
        <ul class="errors">
            <?php
            foreach( $errors as $error) {
                echo("<li>". $error . "</li>");
            }
            ?>
        </ul>
        <?php
        foreach( $chatrooms as $chatroom) {
        ?>
        <form method="post" action="../controllers/chatrooms_controller.php?action=modified&title=<?php echo $chatroom->title; ?>"" id="usersForm">
            <fieldset>
                <legend>chatroom</legend>
                <label for="userLogin">Title</label>
                <input type="text" id="chatroomTitle" name="title" value="<?php echo $chatroom->title; ?>"/>
                <!--<label for="userPassword">Password (password is encode so you can modified password or not)</label>
                <input type="text" id="userPassword" name="password" value=""/>-->
                <label for="userFirstname">Id user</label>
                <input type="text" id="chatroomIdUser" name="id_user" value="<?php echo $chatroom->id_user; ?>"/>
            </fieldset>
            <input type="submit" value="Envoyer" class="button-primary">
        </form>
        <?php } ?>
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
