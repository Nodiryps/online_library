<?php
require_once 'functions.php';
$username = '';
$password = '';

if (check_fields(['username', 'password'])) {
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);

    $member = get_user_by_name($username);
    if($member){
        if(check_password($password, $member['password'])){
            log_user($member['id'], $username, $member['role']);
        } else {
            $error = "Wrong password. Please try again.";
        }
    } else {
        $error = "Can't find a user with the user name '$username'. Please sign up.";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Log In</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Log In</div>
        <div class="menu">
            <a href="index.php">Home</a>
            <a href="signup.php">Sign Up</a>
        </div>
        <div class="main">
            <form action="login.php" method="post">
                <table>
                    <tr>
                        <td>User Name:</td>
                        <td><input id="username" name="username" type="text" value="<?php echo $username; ?>"></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input id="password" name="password" type="password" value="<?php echo $password; ?>"></td>
                    </tr>
                </table>
                <input type="submit" value="Log In">
            </form>
            <?php
            if (isset($error))
                echo "<div class='errors'><br><br>$error</div>";
            ?>
        </div>
    </body>
</html>
