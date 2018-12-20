<!DOCTYPE html>     
<html>
    <head>
        <title><?= $utilisateur->username ?>'s Profile</title>
        <meta charset="UTF-8">
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link href="styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>

        <div class="title">Update Your Profile</div>
        <div class="main">
            Edition du membre : <?= $member->fullname ?>.
            
            <br><br>
            <form action="user/edit_profile" method="post">
                <table>

                    <tr>
                        <td>username:</td>
                        <td><input  name="username" type="text" value="<?= $username ?>"></td>

                    <tr>
                        <td>fullname:</td>
                        <td><input  name="fullname" type="text" value="<?= $fullname ?>"></td>
                    </tr>
                    <tr>
                        <td>email:</td>
                        <td><input  name="email" type="text" value="<?= $email ?>"></td>
                    </tr>
                    <tr>
                        <td>birthdate</td>
                        <td><input  name="birthdate" type="date" value="<?= $birthdate ?>"></td>
                    </tr> 
                    <?php if ($utilisateur->role != "manager"): ?>
                        <tr>
                            <td>Role</td>
                            <td><select name="role" value="<?= $role ?>">
                                    <option value="<?= $role ?>"><?= $role?></option>
                                    <option value="admin">admin</option>
                                    <option value="manager">manager</option>
                                    <option value="member">member</option>

                                </select></td>
                        </tr>

                    <?php endif; ?> 
                    <tr>
                        <td>Password</td>
                        <td><input  name="password" type="password" ></td>
                    </tr>

                    <tr>
                        <td>Confirmation du password</td>
                        <td><input  name="confirm_password" type="password" ></td>
                    </tr>




                </table>
                <input type="hidden" name="idmember" value="<?= $member->id ?>">
                <input type="submit" name="valider" value="Valider">
            </form>

            <br> <a href="user/user_list">Retour a la liste des membres</a>
        </div>
        <?php foreach ($error as $e): ?>
            <div class='errors'><?= $e ?></div>
        <?php endforeach; ?>

    </body>
</html>

