<?php
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$users = isset($_SESSION['users']) ? $_SESSION['users'] : [];
if (isset($_SESSION['login'])){
}

?>

<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://puteborgne.sexy/_css/normalize.css"/>
    <link rel="stylesheet" href="https://puteborgne.sexy/_css/skeleton.css"/>
    <style>
        fieldset {
            border: 0.25rem solid rgba(225, 225, 225, 0.5);
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
        <h2>Users</h2>
        <table class="u-full-width">
            <thead>
            <tr>
                <th>Login</th>
                <th>Password</th>
                <th>Pseudo</th>
                <th>Deleted</th>
                <th>Modified</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($users as $user) {
                ?>
                <tr>
                    <td><?= $user->login ?></td>
                    <td><?= $user->password ?></td>
                    <td><?= $user->handle ?></td>
                    <td>
                        <a href="../controllers/users_controller.php?action=deleted&login=<?php echo $user->login; ?>">
                            <button id="userDeleted" name="deleted">Deleted</button>
                        </a>
                    </td>
                    <td>
                        <a href="../views/modifiedUsers.php?&login=<?php echo $user->login; ?>"">
                            <button id="userModified" name="modified">Modified</button>
                        </a>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>

   <!-- <div class="row">
        <div class="column">
            $_SESSION
            <pre><?php /*print_r($_SESSION) */?></pre>
        </div>

    </div>

    <div class="row">
        <div class="one-half column">
            $_GET
            <pre><?php /*print_r($_GET) */?></pre>
        </div>
        <div class="one-half column">
            $_POST :
            <pre><?php /*print_r($_POST) */?></pre>
        </div>
    </div>-->

</div>
</body>
</html>