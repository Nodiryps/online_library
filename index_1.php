<?php
require_once('functions.php');

if (isLoggedIn()) {
    redirect('profile.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Welcome to EPFC Library!</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Welcome to EPFC Library!</div>
        <div class="menu">
            <a href="login.php">Log In</a>
            <a href="signup.php">Sign Up</a>
        </div>
        <div class="main">
            Please log in or sign up!
        </div>
    </body>
</html>
