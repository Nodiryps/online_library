<!DOCTYPE html>
<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <meta charset="UTF-8">
        <title>Nouveau livre</title>
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
            <?php include('menu.html'); ?>
        </nav>
        <div class="form-horizontal text-center col-lg-offset-4 col-lg-4">
            <form class="form-horizontal" action="book/add_book" method="post" enctype="multipart/form-data">
                <table>
                    <legend><h1>Nouveau livre</h1></legend>

                    <div class="">
                        <input id="ISBN" name="isbn" type="text" placeholder="isbn(13 chiffres)" class="form-control input-md"  >
                    </div>

                    <div class="">
                        <input id="textinput" name="author" type="text" placeholder="auteur.e" class="form-control input-md" >
                    </div>

                    <div class="">
                        <input id="textinput" name="title" type="text" placeholder="titre" class="form-control input-md">
                    </div>

                    <div class="">
                        <input id="textinput" name="editor" type="text" placeholder="edition" class="form-control input-md">
                    </div>
                    
                    <br>
                    
                    <div class="form-group text-left">
                        <label  for="picture">Couverture:</label>
                        <input id="picture" name="picture" class="" type="file" accept="image/x-png, image/gif, image/jpeg">
                        <button  name="button2id" class="btn btn-default">
                            <span>Effacer</span>
                        </button>
                    </div>
                    
                    <div class="form-group">
                        <button id="idbook" type="submit" name="idbook" class="btn btn-success">
                            <span class="glyphicon glyphicon-ok"></span>  Valider
                        </button>

                        <button id="button2id" name="button2id" class="btn btn-warning">
                            <span  class="glyphicon glyphicon-remove">
                                <a  href="book/index" alt="book manager" ></a>
                            </span> Annuler
                        </button>
                    </div>
                </table>
            </form>
        </div>

        <div class="container text-uppercase">
            <?php if (!empty($errors)): ?>
                <ul>
                    <?php foreach ($errors as $test): ?>
                        <li style="color: red"><p><?= $test ?></p></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
            </ul>
        </div>

    </body>
</html>
