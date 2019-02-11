<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <title>membres</title>
        <base href="<?= $web_root ?>"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <style>
            legend, td{text-align: center;}
        </style>
    </head>
    <body>

        <nav class="menu">
            <?php include('menu.html'); ?>
            <div class="title" style="position:absolute;top:20px;right:10px;">
                <strong>  <?= $utilisateur->fullname ?>'s profile! (<?= $utilisateur->role ?>) </strong>
            </div>
        </nav>

        <div class="container" style="margin-top:110px;">

            <table class="table table-striped table-condensed">
                <legend><strong><h1>Membres</h1></strong></legend>
                <thead class="thead-dark">
                    <tr> 
                        <?php if ($utilisateur->role == "admin"): ?>
                            <th>ID</th>
                        <?php endif; ?>

                        <th class="text-center">PSEUDO</th>
                        <th class="text-center">NOM</th>
                        <th class="text-center">EMAIL</th>
                        <th class="text-center">DATE DE NAISSANCE</th>
                        <th class="text-center">ROLE</th>
                    </tr>
                </thead>

                <?php foreach ($members as $member): ?>
                    <tr>
                        <?php if ($utilisateur->role == "admin"): ?>
                            <td class="text-center"> <?= $member->id ?></td>
                        <?php endif; ?>
                        <td> <?= $member->username ?></td>
                        <td> <?= $member->fullname ?></td>
                        <td> <?= $member->email ?></td>
                        <td> <?= $member->birthdate ?></td>
                        <td> <?= $member->role ?></td>
                        <?php if ($utilisateur->role == "admin" || $utilisateur->role == "manager"): ?>
                            <td style="border:none;" bgcolor="white">
                                <form action="user/edit_profile" method="POST">
                                    <input type="hidden" name="idmembers" value="<?= $member->id ?>" >
                                    <button class="btn btn-info" name="modifier" type="submit" value="modifier"  >
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </button>
                                </form>
                            </td>
                            <?php if ($utilisateur->role == "admin" && $utilisateur->username != $member->username): ?>
                                <td style="border:none;" bgcolor="white">
                                    <form method="post" action="user/delete_user">
                                        <input type="hidden"  name="iddelete" value="<?= $member->id ?>" >
                                        <?php if ($member->username != $utilisateur->username): ?>
                                            <button class="btn btn-danger" name="delete" type="submit" value="supprimer">
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </form>
                            </td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
            </table>
            <?php if ($utilisateur->role == "admin" || $utilisateur->role == "manager"): ?>
                <form method="post" action="user/add_user">
                    <button class="btn btn-success" name="addMember" type="submit" value="ajouter membre">
                        <span class="glyphicon glyphicon-plus"></span> Ajouter membre
                    </button>
                </form>
            <?php endif; ?>

        </div>

    </body>
</html>
