<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <title>Accueil</title>
        <base href="<?= $web_root ?>"/>
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <nav> 
            <?php
            if ($profile->is_member())
                include('menuMember.html');
            if ($profile->is_admin() || $profile->is_manager())
                include('menu.html');
            ?>
        </nav>

        <div class="container">
            <div class="row">
                <div class="box">
                    <div class="circle-effect">
                        <h4 class="rotate"><?= $vignette ?> Locations</h4>
                    </div>
                </div>
            </div>
        </div>
        <p style="position:absolute;top:80px;right:10px;"><strong><?php echo $profile->fullname; ?>'s profile (<?= $profile->role ?>) </strong></p>

        <div class="container">
            <div class="row">
                <div class=" text-left col-lg-5">
                    <h2 style="">Bonjour, <?= $profile->fullname ?>  :)</h2>
                </div>
            </div>
        </div>

        <div class="container" style="margin-top:30px;">
            <table class="table table-striped table-condensed">
                <legend class="text-center"><strong><h1>Mes locations</h1></strong></legend>
                <thead class="thead-dark">
                    <tr>
                        <th>ISBN</th>
                        <th>TITRE</th>
                        <th>AUTEUR.E</th>
                        <th>LOUÉ LE:</th>
                        <th>À REMETTRE LE:</th>
                    </tr>
                </thead>

                <?php foreach ($rentals as $rental): ?>

                    <tr>
                        <td><?= Book::isbn_format_EAN_13($rental->get_book()->isbn) ?></td>
                        <td><?= $rental->get_book()->title ?></td>
                        <td><?= $rental->get_book()->author ?></td>
                        <td><?= date('d/m/Y ', strtotime($rental->rentaldate)) ?></td>

                        <?php
                        $returnDate = date('d/m/Y', strtotime('+' . Configuration::get("one_month") . " month", strtotime($rental->rentaldate)));
                        $isLate = ControllerUser::is_return_late($returnDate);
                        $msg = "";
                        echo "$returnDate";
                        ?>
                        <td <?php if ($isLate): $msg = "(RETARD)"; ?>class="text-danger" <?php endif; ?>>
                            <?= $returnDate . " " . $msg; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            </table>
        </div>
    </body>
</html>
