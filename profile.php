<?php
require_once "functions.php";

check_login();

$usr = get_user($logged_userid);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Welcome <?php echo $usr['fullname']; ?>!</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Welcome <?php echo $usr['fullname']; ?>!</div>
        <?php include('menu.php'); ?>
        <div class="main">
        </div>
    </body>
</html>
