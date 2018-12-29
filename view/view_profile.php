<!DOCTYPE html>
<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <title>home</title>
        <meta charset="UTF-8">
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
         <!--Optional theme--> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
         <!--Latest compiled and minified JavaScript--> 
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <body>

        <nav> 
            <?php
            if ($profile->is_member())
                include('menuMember.html');
            if ($profile->is_admin() || $profile->is_manager())
                include('menu.html');
            ?>
            <div class="title" style="position:absolute;top:20px;right:10px;">
                <?php echo $profile->fullname; ?>'s profile! (<?= $profile->role ?>) 
            </div>
        </nav>

        <div class="main">
            <h2 style="margin-left:15px;">Bonjour, <?= $profile->fullname ?> :)</h2>
        </div>
        
        <div class="container" style="margin-top:60px;">
            <table class="table table-striped table-condensed">
                <legend class="text-center"><h1>Mes locations</h1></legend>
                <thead class="thead-dark">
                    <tr>
                        <th>Livres</th>
                        <th>Loué le:</th>
                        <th>À rendre avant le:</th>
                    </tr>
                </thead>
                <?php foreach ($rentals as $rental): ?>
                    <tr>
                        <td><?= $rental->get_book()->title ?></td>
                        <td><?= $rental->rentaldate ?></td>
                        <td><?= $rental->returndate ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </body>
</html>
