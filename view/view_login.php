<!DOCTYPE html>
<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <meta charset="UTF-8">
        <title>Log In</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <body >
       <div  class="h3 text-center">Log In</div>
        <div class=""  >
            <nav class="nav navbar-form navbar-default">
                <ul   class="nav navbar-nav">
                    <li> <a href="main/index" class="nav-link active">Home</a><li>
                    <li> <a href="main/signup"  class="nav-link active">Sign Up</a><li>
                </ul>
            </nav>
        </div>
        <div class="container">	
            <div class="row">
                <h2><strong>Connexion</strong></h2>
                <br/><br/>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <!-- Start form -->
                    <form action="main/login" method="post">
                        <div class="form-group">
                            <label for="username">Pseudo</label>
                            <input type="text" class="form-control"  value="<?= $pseudo ?>" name="pseudo">
                        </div>
                        <div class="form-group">
                            <label for="Password">Mot de passe</label>
                            <input type="password" class="form-control" name="password" value="<?= $password ?>">
                        </div>
                        <div class="form-check">
                            <button class="btn btn-block send-button tx-tfm btn-success" type="submit" 
                                    name="connect" id="connect" value="connect">Connexion</button> 
                        </div>
                    </form>
                    <!-- End form -->
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
