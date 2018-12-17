<?php
    require_once("functions.php");
    $username = '';
    $password = '';
    $password_confirm = '';
    $fullname = '';
    $email = '';
    $birthdate = '';
    
    if(check_fields(['username','password','password_confirm','fullname','email','birthdate'])){
        $username = sanitize($_POST['username']);
        $password = sanitize($_POST['password']);
        $password_confirm = sanitize($_POST['password_confirm']);
        $fullname = sanitize($_POST['fullname']);
        $email = sanitize($_POST['email']);
        $birthdate = sanitize($_POST['birthdate']);

        $errors = validate_user(null, $username, $password, $password_confirm, $fullname, $email, $birthdate);

        if(count($errors) === 0) {
            $id = add_user($username, $password, $fullname, $email, $birthdate);
            log_user($id, $username);
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Sign Up</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Sign Up</div>
        <div class="menu">
            <a href="index.php">Home</a>
        </div>
        <div class="main">
            Please enter your details to sign up :
            <br><br>
            <form action="signup.php" method="post">
                <table>
                    <tr>
                        <td>User Name:</td>
                        <td><input id="username" name="username" type="text" value="<?php echo $username; ?>"></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input id="password" name="password" type="password" value="<?php echo $password; ?>"></td>
                    </tr>
                    <tr>
                        <td>Confirm Password:</td>
                        <td><input id="password_confirm" name="password_confirm" type="password" value="<?php echo $password_confirm; ?>"></td>
                    </tr>
                    <tr>
                        <td>Full Name:</td>
                        <td><input id="fullname" name="fullname" type="text" value="<?php echo $fullname; ?>"></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input id="email" name="email" type="email" value="<?php echo $email; ?>"></td>
                    </tr>
                    <tr>
                        <td>Birth Date:</td>
                        <td><input id="birthdate" name="birthdate" type="date" value="<?php echo $birthdate; ?>"></td>
                    </tr>
                </table>
                <input type="submit" value="Sign Up">
            </form>
            <?php 
                if(isset($errors)){
                    echo "<div class='errors'>
                          <p>Please correct the following error(s) :</p>
                          <ul>";
                    foreach($errors as $error){
                        echo "<li>".$error."</li>";
                    }
                    echo '</ul></div>';
                } 
            ?>
        </div>
    </body>
</html>
