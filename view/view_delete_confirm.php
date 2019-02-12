<!DOCTYPE html>
<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <meta charset="UTF-8">
        <title>Confirmation</title>
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

    </head>
    <body>
        <div class="container text-center" style="width:350px;margin:5% auto;">	
            <div class="row">
                <h1><strong>Confirmation</strong></h1>
            </div>
            <br>
            <?php if ($member->role !== "admin" || ($member->role === "admin" && sizeof($tabAllAdmins) > 1)): ?>
                <p>Êtes-vous sûr.e de vouloir supprimer: <strong><?= $member->fullname; ?></strong></p>
                <br>
                    <form method="POST" action="user/delete_user">
                        <input name="conf" type="hidden" value="<?= $member->id ?>">
                        <input style="float:left;" class="col-lg-offset-2 btn btn-danger" name="confirmer" type="submit" value="Supprimer">
                    </form>
                    <form method="POST" action="user/user_list ">
                        <input class=" btn btn-info" name="retour" type="submit" value="Retour">
                    </form>

            <?php else: ?>
                <p> Dis, <?= $utilisateur->fullname; ?>. Il doit rester au moins UN administrateur ! Allez, ZOU!</p>
                <form method="POST" action="user/user_list ">
                    <input name="retour" type="submit" value="retour">
                </form>
            <?php endif; ?>
        </div>
    </body>
</html>

