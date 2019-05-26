<!DOCTYPE html>     
<html>
    <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
    <title>Modif profile</title>
    <base href="<?= $web_root ?>"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
    <script src="lib/jquery-validation-1.19.0/jquery.validate.min.js" type="text/javascript"></script>
    <script src="js/editUser.js" type="text/javascript"></script>

    <style>
        legend, td{text-align: center;}
    </style>
    <body>
        <nav   class="text-right"> 
            <?php include('menu.html'); ?>
        </nav>
        <p style="position:absolute;top:80px;right:10px;"><strong><?php echo $utilisateur->fullname; ?>'s profile (<?= $utilisateur->role ?>) </strong></p>

        <div class="container text-center" >
            <br><br>
            <form action="user/edit_profile" method="post" class=" form-horizontal col-lg-12 col-lg-offset-3 col-lg-5" id="editform">
                <table class="table-dark ">
                    <legend><h1>Modificiation du profile de: <?= $member->username ?></h1></legend>
                    <tr>
                        <td>Pseudo:</td>
                        <td><input id="username"  name="username" type="text" value="<?= $member->username ?>"  class="form-control my-input"></td>
                    </tr>
                    <tr>
                        <td>Nom complet:</td>
                        <td><input id="fullname" name="fullname" type="text" value="<?= $member->fullname ?>"  class="form-control my-input"></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input id="email" name="email" type="text" value="<?= $member->email ?>"  class="form-control my-input"></td>
                    </tr>
                    <tr>
                        <td>Date de naissance:</td> <!-- max="<? strtotime(substr(User::today_one_int(), 0, 4)); ?>" -->
                        <td><input  name="birthdate" type="date" value="<?= $member->birthdate ?>" class="form-control my-input"></td>
                    </tr> 
                    <?php if ($utilisateur->is_admin()): ?>
                        <tr>
                            <td>Role:</td>
                            <td>
                                <select id="selectbasic" name="role" class="form-control form-control my-input">
                                    <option value="<?= $member->role ?>"  ><?= $member->role ?></option>
                                    <?php
                                    foreach ($tabRoles as $rol) {
                                        if ($member->role !== $rol) {
                                            echo "<option value='$rol'>$rol</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php if ($utilisateur->id === $member->id): ?>
                        <tr>
                            <td>Mot de passe:</td>
                            <td><input id="password" name="password" type="password"  class="form-control my-input"></td>
                        </tr>
                        <tr>
                            <td>Confirmation du mdp:</td>
                            <td><input id="confirm_password" name="confirm_password" type="password"  class="form-control my-input"></td>
                        </tr>
                    <?php endif; ?>
                </table>
                <br><br>
                <input type="hidden" name="idmember" value="<?= $member->id ?>">
                <button class="btn btn-success col-lg-offset-3 col-lg-5" type="submit" name="valider" value="Valider">
                    <span>Valider</span>
                </button>
                <br><br>
                <a class="btn btn-info col-lg-offset-3 col-lg-5" href="user/user_list" class="row ">Retour</a>

            </form>

            <div class='text-danger text-left'>
                <?php if ($error !== []): ?>
                    <p>Erreur(s) à corriger:</p>
                    <ul>
                        <?php foreach ($error as $e): ?>
                            <li><?= $e ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>



    </body>
</html>

