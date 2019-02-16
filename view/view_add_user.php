<!DOCTYPE html>
<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <title>Nouveau membre</title>
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

        <nav   class="text-right"> 

            <?php
            if ($profile->is_member())
                include('menuMember.html');
            if ($profile->is_admin() || $profile->is_manager())
                include('menu.html');
            ?>
            <div class="text-right" style="position:absolute;top:20px;right:10px;">
                <p> <strong>  <?= $profile->fullname; ?>'s profile! (<?= $profile->role ?>) </strong></p>
            </div>

        </nav>
        <div class="container text-center">
            <table>
               
            </table>
        </div>
        <div class="container ">
            <br><br>
            <div class="form-horizontal">
                <form action="user/add_user" method="post" class="form-horizontal">
                    <table class="table table-dark col-lg-offset-0 col-lg-5 ">
                        <legend style="font-size: 2em;" class="text-center"><strong>Ajouter un utilisateur</strong></legend>
                        <thead class="thead-dark">
                            <tr class="form-group">
                                <td><input id="username" name="username" type="text" value="" class="form-control my-input" id="username" placeholder="username" value="<?= $username ?>"></td>
                            </tr >
                            <tr class="form-group">
                                <td><input id="password" name="password" type="password" value="" class="form-control my-input" id="username"  placeholder="mot de passe"></td>
                            </tr>
                            <tr class="form-group">
                                <td><input id="password_confirm" name="password_confirm" type="password" value="" class="form-control my-input" id="username"  placeholder="confirmer le mot de passe"></td>
                            </tr>
                            <tr class="form-group">
                                <td><input id="username" name="fullname" type="text" value="" class="form-control my-input" id="username"  placeholder="fullname" value="<?= $fullname ?>"></td>
                            </tr>
                            <tr class="form-group">
                                <td><input id="username" name="mail" type="text" value="" class="form-control my-input" id="username"  placeholder="email  " value="<?= $email ?>"></td>
                            </tr>
                            <tr class="form-group">
                                <td><input id="username" name="birthdate" type="date"  class="form-control my-input" id="username"  placeholder="date de naissance"></td>
                            </tr>

                            <?php if ($profile->role == "admin"): ?>
                                <tr class="form-group ">

                                    <td><select name="role" class="form-control my-input" id="username">
                                            <option value="admin" >admin</option>
                                            <option value="manager">manager</option>
                                            <option value="member" selected="selected">member</option>
                                        </select></td>
                                </tr > 

                            <?php endif; ?>   
                            <?php if ($profile->role == "manager"): ?>
                                <tr class="form-group ">

                                    <td><select name="role" class="form-control my-input" id="username">

                                            <option value="member" selected="selected">member</option>
                                        </select></td>
                                </tr > 

                            <?php endif; ?>   

                    </table>
                    <button type="submit" class=" btn btn-block send-button tx-tfm btn-success" style="margin:auto;width:150px;">
                        Valider
                    </button>

                </form>
            </div>
        </div>
        <div class="container text-center">
            <br> <a  class="btn btn-info text-right" href="user/user_list">Retour a la liste des membres</a>
        </div>
        <?php
        if (!empty($errors)) {
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

</body>

</html>

