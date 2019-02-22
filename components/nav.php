<nav>
    <a href="../controllers/users_controller.php?action=login">login</a>
    <?php if ($_SESSION) ?>
    <a href="../controllers/users_controller.php?action=list">list users</a>
    <a href="../controllers/messages_controller.php?action=list">list messages</a>
</nav>