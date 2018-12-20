<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $profile->username; ?>'s Profile!</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title"><?php echo $profile->username; ?>'s Profile! (<?= $profile->role ?>) </div>
        <?php include('menu.html'); ?>
        <div class="main">
            <h1>Bienvenue <?= $profile->fullname?></h1>
        </div>
        <?php  ?>
    </body>
</html>
