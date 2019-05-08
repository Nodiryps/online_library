<!DOCTYPE html>
<html lang="fr">
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <meta charset="UTF-8">
        <title>Connexion</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--<link href="css/style.css" rel="stylesheet" type="text/css"/>-->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="js/index.js" type="text/javascript"></script>
        <script src="lib/jquery-validation-1.19.0/jquery.validate.min.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="container" style="width:350px;margin:5% auto;">	
            <div class="row">
                <h1 class="text-center "><strong>Connexion</strong></h1>
                <br>
                <div>
                    <form action="main/login" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="loginForm">
                        <div class="form-group">
                            <input type="text" class="form-control" name="pseudo" value="<?= $pseudo ?>" placeholder="Pseudo" id="pseudo">
                          <p class="errors" id="errPseudo"></p>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" value="<?= $password ?>" placeholder="Mot de passe"  id="password">
                          <p class="errors" id="errPassword"></p>
                        </div>
                        <div class="form-check">
                            <button class="btn btn-block send-button tx-tfm btn-success" type="submit" id="connect"
                                    name="connect" id="connect" value="connect" 
                                    style="margin:auto;width:150px;">Connexion
                            </button> 
                        </div>
                        <a href="main/signup" style="position:absolute;bottom:-30px;left:120px;">Créer un compte</a>
                    </form>
                </div>
            </div>
            <br>
            <div class='text-danger text-left'>
                <?php if ($errors !== []): ?> 
                    <p>Erreur(s) à corriger:</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

        </div>
    </body>
</html>
