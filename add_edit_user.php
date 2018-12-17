<?php
    require_once("functions.php");

    check_manager_or_admin();

    // ici on fait la distinction entre la création d'un nouveau membre (pas d'id dans l'url)
    // ou l'édition d'un membre existant (on reçoit l'id dans l'url)
    if (check_fields(['id'], $_GET)) {
        $is_new = false;
        $id = sanitize($_GET['id']);
        $usr = get_user($id);
        if (!$usr) {
            abort('Unknown user');
        }
        $username = $usr['username'];
        $fullname = $usr['fullname'];
        $email = $usr['email'];
        $birthdate = $usr['birthdate'];
        $role = $usr['role'];
        $password = $usr['password'];
    } else {
        $is_new = true;
        $id = null;
        $username = '';
        $fullname = '';
        $email = '';
        $birthdate = null;
        $role = 'member';
    }

    if(check_fields(['cancel'])){
        redirect('users.php');
    }

    if(!isAdmin() && check_fields(['role']))
        abort("You may not change the role since you're not an admin.");

    if(check_fields(['save', 'username','fullname','email','birthdate']) && (isManager() || check_fields(['role']))){
        $username = sanitize($_POST['username']);
        $fullname = sanitize($_POST['fullname']);
        $email = sanitize($_POST['email']);
        $birthdate = sanitize($_POST['birthdate']);
        // si c'est un nouveau user, on initialise son mot de passe avec son pseudo (convention)
        if ($is_new)
            $password = $username;

        $errors = validate_user($id, $username, $password, $password, $fullname, $email, $birthdate);

        if (isAdmin()) {
            $role = sanitize($_POST['role']);
            // si j'édite un user existant et que je mets un rôle différent d'admin alors que le rôle courant de ce user
            // est admin, et si c'est le seul admin en base de données, alors je dois déclencher une erreur
            if (!$is_new && $role !== 'admin' && $usr['role'] === 'admin' && count_admins() === 1) {
                $errors[] = "You're the last admin in the system: you must keep your role";
            }
        }

        if(count($errors) === 0) {
            // Si le user dont on a reçu l'id dans l'url est le user connecté et si son rôle ou son username ont changé,
            // mettre à jour la session en reloguant l'utilisateur, sans faire de redirection (4ème paramètre de log_user).
            if (!$is_new && $id === $logged_userid && ($username != $logged_username || $role != $logged_role)) {
                log_user($logged_userid, $username, $role, false);
            }
            if ($is_new)
                add_user($username, $password, $fullname, $email, $birthdate, $role);
            else {
                update_user($id, $username, $fullname, $email, $birthdate, $role);
                // si à cause d'un update du rôle on est devenu un membre, rediriger vers le profile
                if (isMember())
                    redirect('profile.php');
            }
            redirect('users.php');
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?= $is_new ? "Add" : "Edit" ?> User</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title"><?= $is_new ? "Add" : "Edit" ?> User</div>
        <?php include("menu.php"); ?>
        <div class="main">
            Please enter the user details :
            <br><br>
            <form action="" method="post">
                <table>
                    <tr>
                        <td>User Name:</td>
                        <td><input id="username" name="username" type="text" value="<?php echo $username; ?>"></td>
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
                    <tr>
                        <td>Role:</td>
                        <td>
                            <select id="role" name="role" <?= isAdmin() ? '' : 'disabled' ?>>
                                <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>admin</option>
                                <option value="manager" <?= $role === 'manager' ? 'selected' : '' ?>>manager</option>
                                <option value="member" <?= $role === 'member' ? 'selected' : '' ?>>member</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <input type="submit" name="save" value="Save">
                <input type="submit" name="cancel" value="Cancel">
            </form>
            <?php 
                if(isset($errors) && count($errors) > 0){
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
