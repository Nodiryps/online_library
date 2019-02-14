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
            <p>Êtes-vous sûr.e de vouloir supprimer: 
                <br>
                <?php if (isset($_POST["iddelete"])): ?>
                    "<strong><?= $member->fullname; ?></strong>"
                <?php elseif (isset($_POST["delbook"])): ?>
                    <strong><?= $book->title; ?></strong> de <strong><?= $book->author; ?></strong>
                <?php endif; ?>
            </p>
            <br>
            <form method="POST"
                <?php if (isset($_POST["iddelete"])): ?> action="user/delete_user"
                <?php elseif (isset($_POST["delbook"])): ?> action="book/delete_book"
                <?php endif; ?>>
                <input name="conf" type="hidden" 
                    <?php if (isset($_POST["iddelete"])): ?> value="<?= $member->id ?>"
                    <?php elseif (isset($_POST["delbook"])): ?> value="<?= $book->id ?>"
                    <?php endif; ?>>
                <input style="float:left;" class="col-lg-offset-2 btn btn-danger" name="confirmer" type="submit" value="Supprimer">
            </form>
            <form method="POST" 
                <?php if(isset($_POST["iddelete"])):?> action="user/user_list "
                <?php elseif (isset($_POST["delbook"])): ?> action="book/index"
                <?php endif; ?>>
                <input class="btn btn-info" name="retour" type="submit" value="Retour">
            </form>
        </div>
    </body>
</html>

