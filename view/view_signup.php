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
        <style>
            input {
                text-align:center;
            }
        </style>
    </head>
    <body>

        <!--        <div  class="navbar navbar-default">
                    <nav>
                        <ul  class="nav navbar-nav">
                            <li> <a href="main/index" class="glyphicon glyphicon-arrow-left"></a></li>
                        </ul>
                    </nav>
                </div>-->
        <form action="book/index" method="post">
        <div class="container text-center" style="width:350px;margin:5% auto;">
            <!--            <div class="row ">
                            <div class="col-md-10">
                                <div class="myform form  ">-->
            <div class="btn btn-default " style="position:absolute;top:5%;right:auto;">
                <a href="main/index" class="glyphicon glyphicon-arrow-left"></a>
            </div>
            <div class="text-center"><h1>Inscription</h1></div>
            <br>
            </form>
            <form action="main/signup" method="post" >
                <div class="form-group">
                    <label for="fullname">Nom complet</label>
                    <input type="text" name="fullname"  class="form-control my-input" id="fullname">
                </div>
                <div class="form-group">
                    <label for="username">Pseudo</label>
                    <input type="text" name="username"  class="form-control my-input" id="username">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email"  class="form-control my-input" id="email">
                </div>
                <div class="form-group">
                    <label for="birthdate">Date de naissance</label>
                    <input type="date" name="birthdate" id="birthdate"  class="form-control my-input">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password"  class="form-control my-input" id="password">
                </div>
                <div class="form-group">
                    <label for="password_confirm">Confirmation</label>
                    <input type="password" name="password_confirm"  class="form-control my-input" id="password_confirm">
                </div>
                <div class="text-center">
                    <button type="submit" class=" btn btn-block send-button tx-tfm btn-success"
                            style="margin:auto;width:150px;">
                        Valider
                    </button>
                </div>
        </div>
        <!--    </div>
        </div>
        </div>-->
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