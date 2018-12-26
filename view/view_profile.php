<!DOCTYPE html>
<html>
    <head>
        <link style="width:100%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <title><?php echo $profile->username; ?>'s Profile!</title>
        <meta charset="UTF-8">
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
      
        <nav> 
            <?php include('menu.html'); ?>
        </nav>
          <div class="title"><?php echo $profile->username; ?>'s Profile! (<?= $profile->role ?>) </div>
        <div class="main">
            <h1>Bienvenue <?= $profile->fullname?></h1>
        </div>
        <?php  ?>
    </body>
</html>
