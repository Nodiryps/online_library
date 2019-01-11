<!DOCTYPE html>
<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <title>Ajouter un membre</title>
        <meta charset="UTF-8">
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!--Optional theme--> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <!--Latest compiled and minified JavaScript--> 
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container">
            <div>
                <h1>Ajouter un utilisateur</h1>
            </div>
            <div class="menu">
                <a href="user/profil">Home</a>
            </div>
            <div class="container">
                Please enter your details to sign up :
                <br><br>
                <div class="form-horizontal">
                <form action="user/add_user" method="post">
                    <table class="table table-dark col-lg-6">
                         <thead class="thead-dark">
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
                </div>
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

