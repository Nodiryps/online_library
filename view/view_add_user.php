<!DOCTYPE html>
<html>
    <head>
        <title>ajouter membre</title>
        <meta charset="UTF-8">
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <link href="css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Ajouter un utilisateur</div>
        <div class="menu">
            <a href="user/profil">Home</a>
        </div>
        <div class="main">
            Please enter your details to sign up :
            <br><br>
            <form action="user/add_user" method="post">
                <table>
                    <tr>
                        <td>username:</td>
                        <td><input id="username" name="username" type="text" value=""></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input id="password" name="password" type="password" value=""></td>
                    </tr>
                    <tr>
                        <td>Confirm Password:</td>
                        <td><input id="password_confirm" name="password_confirm" type="password" value=""></td>
                    </tr>
                    <tr>
                        <td>fullname:</td>
                        <td><input id="username" name="fullname" type="text" value=""></td>
                    </tr>
                    <tr>
                        <td>email:</td>
                        <td><input id="username" name="mail" type="text" value=""></td>
                    </tr>
                    <tr>
                        <td>birthdate</td>
                        <td><input id="username" name="birthdate" type="date" ></td>
                    </tr>

                    <?php if ($utilisateur->role == "admin"): ?>
                        <tr>
                            <td>Role</td>
                            <td><select name="role">
                                    <option value="admin" >admin</option>
                                    <option value="manager">manager</option>
                                    <option value="member" selected="selected">member</option>

                                </select></td>
                        </tr> 

                    <?php endif; ?>   
                    <?php if ($utilisateur->role == "manager"): ?>
                        <tr>
                            <td>Role</td>
                            <td><select name="role">
                                    <option value="member">member</option>
                                </select></td>
                        </tr>
                    <?php endif; ?>
                </table>
                <input type="submit" value="ajouter">
            </form>
            <?php
            if (isset($errors)) {
                echo "<div class='errors'>
                          <br><br><p>Veuillez corriger les erreurs suivantes :</p>
                          <ul>";
                foreach ($errors as $error) {
                    echo "<li>" . $error . "</li>";
                }
                echo '</ul></div>';
            }
            ?>
        </div>
         <br> <a href="user/user_list">Retour a la liste des membres</a>

    </body>
    
</html>

