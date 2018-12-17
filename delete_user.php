<?php
require_once("functions.php");

check_admin();

if (check_fields(['id'], $_GET)) {
    $id = sanitize($_GET['id']);
    if ($id === $logged_userid)
        abort("You may not delete yourself!");
    $usr = get_user($id);
    if (!$usr) {
        abort('Unknown user');
    }
}
else {
    redirect('users.php');
}

if (isset($_POST['confirm'])) {
    delete_user($id);
    redirect('users.php');
}
elseif (isset($_POST['cancel'])) {
    redirect('users.php');
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Confirm Deletion</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Confirm Deletion</div>
        <?php include("menu.php"); ?>
        <div class="main">
            <p> You are about to delete the user '<?= $usr['fullname'] ?>'.<br>If this is correct, please confirm.</p>
            <form method="post">
                <input type="submit" name="confirm" value="Confirm">
                <input type="submit" name="cancel" value="Cancel">
            </form>
        </div>
    </body>
</html>
