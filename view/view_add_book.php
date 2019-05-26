<!DOCTYPE html>
<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <meta charset="UTF-8">
        <title>Nouveau livre</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"  crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"  crossorigin="anonymous"></script>
        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-validation-1.19.0/jquery.validate.min.js" type="text/javascript"></script>
        <script src="js/newBook.js" type="text/javascript"></script>

    </head>
    <body>
        <nav> 
            <?php include('menu.html'); ?>
        </nav>
        <div class="form-horizontal text-center col-lg-offset-4 col-lg-4">
            <form class="form-horizontal" action="book/add_book" method="post" enctype="multipart/form-data" id="AddBookForm">
                <table>
                    <legend><h1>Nouveau livre</h1></legend>

                    <div class="isbn">
                    <input id="ISBN" name="isbn" type="text" placeholder="isbn(12 chiffres)"   class="form-control input-md " value="<?= $isbn ?>" style="float: left;" > <!--<input style="display:inline-block;" id="isbn2">-->
                    </div>
                    <br>

                    <div class="">
                        <input id="author" name="author" type="text" placeholder="auteur.e" class="form-control input-md"value="<?= $author ?>" >
                    </div>

                    <div class="">
                        <input id="title" name="title" type="text" placeholder="titre" class="form-control input-md" value="<?= $title ?>">
                    </div>

                    <div class="">
                        <input id="editor" name="editor" type="text" placeholder="edition" class="form-control input-md" value="<?= $editor ?>">
                    </div>
                    <div class="">
                        <input id="nbCopie" name="nbCopie" type="number" placeholder="nombre de copies" min="1" class="form-control input-md" value="<?= $nbCopie ?>">
                    </div>

                    <br>

                    <div class="form-group text-left">
                        <label  for="picture">Couverture:</label>

                        <input id="picture" name="picture" class="" type="file" accept="image/x-png, image/gif, image/jpeg">
                        <br>
                        <br>
                        <button  name="button2id" class="btn btn-default">
                            <span>Effacer l'image</span>
                        </button>
                    </div>

                    <div class="form-group">
                        <button id="idbook" type="submit" name="idbook" class="btn btn-success">
                            <span class="glyphicon glyphicon-ok"></span>  Valider
                        </button>



                        <a class="btn btn-success" href="book/index" alt="book manager" >Annuler</a>


                    </div>
                </table>
            </form>
        </div>

        <div class="container ">
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
