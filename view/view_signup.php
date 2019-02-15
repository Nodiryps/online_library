<!DOCTYPE html>
<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <meta charset="UTF-8">
        <title>Inscription</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <body>

        <div class="container" style="width:350px;margin:5% auto;">
            <div class="row">
            <h1 class="text-center"><strong>Inscription</strong></h1>
            <br>
            <form action="main/signup" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="form-group">
                    <input type="text" name="fullname"  class="form-control my-input" id="fullname" placeholder="Nom complet">
                </div>
                <div class="form-group">
                    <input type="text" name="username"  class="form-control my-input" id="username" placeholder="Pseudo">
                </div>
                <div class="form-group">
                    <input type="email" name="email"  class="form-control my-input" id="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="date" name="birthdate" id="birthdate"  class="form-control my-input" placeholder="Date de naissance">
                </div>
                <div class="form-group">
                    <input type="password" name="password"  class="form-control my-input" id="password" placeholder="Mot(phrase!) de passe">
                </div>
                <div class="form-group">
                    <input type="password" name="password_confirm"  class="form-control my-input" id="password_confirm" placeholder="Répétez votre mot(phrase!) de passe">
                </div>
                <br>
                <div class="text-center">
                    <button type="submit" class=" btn btn-block send-button tx-tfm btn-success" style="margin:auto;width:150px;">
                        Valider
                    </button>
                    <a href="main/index" style="position:absolute;bottom:-30px;left:127px;">Déjà inscrit.e?</a>
                </div>
                
            </form>
        </div>
        <div class="text-center">
            <?php
            if ($errors !== []) {
                echo "<div class='errors'>
                              <p>Erreur(s) à corriger:</p>
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
