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
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"  crossorigin="anonymous"></script>
        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-validation-1.19.0/jquery.validate.min.js" type="text/javascript"></script>
        <script src="js/addUser.js" type="text/javascript"></script>
    </head>
    <body>

        <nav> 
            <?php include('menu.html'); ?>
        </nav>
        <p style="position:absolute;top:80px;right:10px;"> <strong>  <?= $profile->fullname; ?>'s profile! (<?= $profile->role ?>) </strong></p>

        <div class="container row text-center">
            <br><br>
            <div class="form-horizontal text-center col-lg-offset-6 col-lg-5">
                <form action="user/add_user" method="post" class="form-horizontal" id="addUserForm">
                    <table class="table table-dark">
                        <legend><h1>Nouveau membre</h1></legend>
                        <thead class="thead-dark">
                            <tr class="form-group">
                                <td><input id="username" name="username" type="text" value="" class="form-control my-input"  placeholder="username" value="<?= $username ?>"></td>
                            </tr >
                            <tr class="form-group">
                                <td><input id="password" name="password" type="password" value="" class="form-control my-input" id="password"  placeholder="mot de passe" autocomplete="password"></td>
                            </tr>
                            <tr class="form-group">
                                <td><input id="password_confirm" name="password_confirm" type="password" value="" class="form-control my-input" id="password_confirm"  placeholder="confirmer le mot de passe" autocomplete="password_confirm"</td>
                            </tr>
                            <tr class="form-group">
                                <td><input id="fullname" name="fullname" type="text" value="" class="form-control my-input" id="fullname"  placeholder="fullname" value="<?= $fullname ?>"></td>
                            </tr>
                            <tr class="form-group">
                                <td><input id="mail" name="mail" type="text" value="" class="form-control my-input" id="mail"  placeholder="email  " value="<?= $email ?>"></td>
                            </tr>
                            <tr class="form-group">
                                <td><input id="birthdate" name="birthdate" type="date"  class="form-control my-input" id="birthdate"  placeholder="date de naissance"></td>
                            </tr>

                            <?php if ($profile->role == "admin"): ?>
                                <tr class="form-group ">
                                    <td>
                                        <select name="role" class="form-control my-input" id="role">
                                            <option value="admin" >admin</option>
                                            <option value="manager">manager</option>
                                            <option value="member" selected="selected">member</option>
                                        </select>
                                    </td>
                                </tr > 
                            <?php endif; ?>   
                            <?php if ($profile->role == "manager"): ?>
                                <tr class="form-group ">
                                    <td>
                                        <select name="role" class="form-control my-input" id="role">
                                            <option value="member" selected="selected">member</option>
                                        </select>
                                    </td>
                                </tr > 
                            <?php endif; ?>   

                    </table>
                    <button type="submit" class="btn btn-block btn-success">
                        Valider
                    </button>
                </form>
                <br> <a  class="btn btn-block btn-info" href="user/user_list">Retour</a>
            </div>


        </div>
        <br>
        <br><br>
        <div class=' container row col-lg-offset-4 text-danger text-left'>
            <?php if ($errors !== []): ?>
                <p>Erreur(s) à corriger:</p>
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= $e ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

    </body>
    <br><br><br><br>
</html>

