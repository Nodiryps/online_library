<!DOCTYPE html>

<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <meta charset="UTF-8">
        <title>Détails</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <!-- Latest compiled and minified JavaScript -->
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
        </nav>
        <p style="position:absolute;top:80px;right:10px;"> <strong>  <?= $profile->fullname; ?>'s profile! (<?= $profile->role ?>) </strong></p>

        <div class="container text-capitalize">
            <h1 class="text-center "> <?= $book->title ?></h1>
        </div>
        <br><br>
        <div class="col-lg-offset-3 container" >
            <div class="col-lg-4">
                <img  id="zoomimg" style="float:left;width:300px;height:450px;box-shadow:5px 5px 15px 0px rgba(36,36,36,0.42);" 
                                  <?php if ($book->picture !== NULL || $book->picture != ""): ?>
                                  src='uploads/<?= $book->picture ?>' alt="Couverture"
                                  <?php else: ?> 
                                  src='uploads/images.png' alt="Couverture">
                                  <?php endif; ?>
            </div>
        </div>
         <div class="col-lg-3 list-group">
                    <table style="border-collapse:separate;border-spacing:15px;">
                        <tr>
                            <th>ISBN:</th> 
                            <td><?= Book::isbn_format_EAN_13($book->isbn) ?></td>
                        </tr>
                        <tr>
                            <th>AUTHOR:</th> 
                            <td><?= $book->author ?></td>
                        </tr>
                        <tr>
                            <th>TITLE:</th>
                            <td><?= $book->title ?></td>
                        </tr>
                        <tr>
                            <th>EDITOR:</th>
                            <td><?= $book->editor ?></td>
                        </tr>
                    </table>
                    <form method="get" action="book/index">
                        <button  class="text-center btn btn-info btn-block btn-huge"><span   class="glyphicon glyphicon-arrow-left">                 </span></button>
                    </form>
                </div>

    </body>
</html>
