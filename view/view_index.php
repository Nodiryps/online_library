<!DOCTYPE html>
<html>
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
        <style>
            input {
                text-align:center;
            }
        </style>
    </head>
    <body>
        <div class="container" style="width:250px;margin:10% auto;">	
            <div class="row">
                <h1 class="text-center"><strong>Connexion</strong></h1>
                <br/>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <form action="main/login" method="post">
                        <div class="form-group text-center">
                            <label for="username">Pseudo</label>
                            <input type="text" class="form-control" name="pseudo" value="<?= $pseudo ?>" >
                        </div>
                        <div class="form-group text-center">
                            <label for="password">Mot de passe</label>
                            <input type="password" class="form-control" name="password" value="<?= $password ?>">
                        </div>
                        <div class="form-check">
                            <button class="btn btn-block send-button tx-tfm btn-success" type="submit" 
                                    name="connect" id="connect" value="connect" 
                                    style="margin:auto;width:150px;">Connexion
                            </button> 
                        </div>
                        <a href="main/signup" style="position:absolute;bottom:-30px;right:91px;">Inscription</a>
                    </form>
                </div>
                <?php if ($errors !== []): ?>
                    <div class='errors'>
                        <p>Erreur(s) à corriger:</p>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>
