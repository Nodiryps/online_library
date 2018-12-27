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
    <body class="container " >
        <div  class=" container h3">Log In</div>
        <div class=" container nav navbar-nav"  >
            <nav class="navbar-form navbar-default">
                <ul   class="nav navbar-nav">
                    <li> <a href="main/index" class="nav-link active">Home</a><li>
                    <li> <a href="main/signup"  class="nav-link active">Sign Up</a><li>
                </ul>
            </nav>
        </div>
        <div class="container">
            <form action="main/login" method="post" class="form-group text-center">
                <table>
                    <tr>
                        <td><label class="col-md-4 control-label" for="passwordinput">Pseudo: </label></td>
                        <td><input id="pseudo" name="pseudo" type="text" value="<?= $pseudo ?>"></td>
                    </tr>
                    <tr>
                        <td><label class="col-md-4 control-label" for="passwordinput">MdP: </label></td>
                        <td><input id="password" name="password" type="password" value="<?= $password ?>"></td>
                    </tr>
                </table>
                <button type="submit" class="btn btn-succes"><span >Login</span></button>
            </form>
            <?php if (count($errors) != 0): ?>
                <div class='errors'>
                    <p>Please correct the following error(s) :</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>
