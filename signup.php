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

