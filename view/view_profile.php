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
            <div class="title " style="position:absolute;top:20px;right:10px;">
                <strong><?php echo $profile->fullname; ?>'s profile! (<?= $profile->role ?>) </strong>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <div class=" text-left text-uppercase col-lg-5">
                    <h2 style="">Bonjour, <?= $profile->fullname ?>  :)</h2>
                </div>
            </div>
        </div>

        <div class="container" style="margin-top:30px;">
            <table class="table table-striped table-condensed">
                <legend class="text-center"><strong><h1>Mes locations</h1></strong></legend>
                <thead class="thead-dark">
                    <tr>
                        <th>Livres</th>
                        <th>titre</th>
                        <th>Louer le:</th>
                         <th>À remettre avant le:</th>
                    </tr>
                </thead>
                
                <?php foreach ($rentals as $rental): ?>

                    <tr>
                        <td><?= ControllerBook::isbn_format_EAN_13($rental->get_book()->isbn) ?></td>
                        <td><?= $rental->get_book()->title ?>   (<?= $rental->get_book()->author ?>)</td>
                        <td><?= date('d-m-Y ', strtotime($rental->rentaldate)) ?></td>
                      
                        <?php if (ControllerUser::gestion_date(date('d/m/Y', strtotime('+1 month', strtotime($rental->rentaldate))))): ?>
                            <td style="color: red"><?= date('d/m/Y', strtotime('+1 month', strtotime($rental->rentaldate))); ?></td>
                        <?php else : ?>
                            <td><?= date('d/m/Y', strtotime('+1 month', strtotime($rental->rentaldate))); ?></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php
//                    $middle = strtotime($rental->rentaldate); 
//                    echo date("d-m-Y H:i:s");
//                   if( $middle < strtotime(date("d-m-Y H:i:s") )){
//                    echo  "je suis vrai";
//                   }
//                    
                    ?>



                </tr>

            </table>

        </div>
    </body>
</html>
