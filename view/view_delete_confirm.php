<!DOCTYPE html>
<html>
    <head>
        <title>Confirmation</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php $member->fullname ?>
        <div class="title">Confirmation</div>
        <div class="main">
            <p> Etes-vous sure de vouloir supprimer : <?= $member->fullname; ?> </p>

            <form method="POST" action="../user/delete_user">

                <input name="conf" type="hidden" value="<?= $member->id ?>">
                <input name="confirmer" type="submit" value="confirmer">
            </form>

            <form method="POST" action="user_list ">
                <input name="retour" type="submit" value="retour">
            </form>
        </div>
    </body>
</html>

