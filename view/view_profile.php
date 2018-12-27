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
            <?php 
                if($profile->role === "member")
                    include('menuMember.html'); 
                if($profile->role === "admin" || $profile->role === "manager")
                    include('menu.html'); 
                ?>
        </nav>
          <div class="title"><?php echo $profile->username; ?>'s Profile! (<?= $profile->role ?>) </div>
        <div class="main">
            <h1>Bienvenue <?= $profile->fullname?></h1>
        </div>
          <div class="container">
              <table class="table table-striped table-condensed">
                  <legend class="main">Vos locations</legend>
                  <thead class="thead-dark">
                      <tr>
                          <th>Livres</th>
                          <th>Loué le:</th>
                          <th>À rendre avant le:</th>
                      </tr>
                  </thead>
                  <?php foreach($rentals as $rental): ?>
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
