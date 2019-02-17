<!DOCTYPE html>
<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <meta charset="UTF-8">
        <title>Modif livre</title>
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
        <p style="position:absolute;top:80px;right:10px;"><strong> <?= $profile->fullname; ?>'s profile! (<?= $profile->role ?>) </strong></p>

        <table><legend class="text-center"><?= strtoupper($book->title) ?> </legend></table>
        <div class="container row">

            <div class="col-lg-offset-2 col-lg-5">
                <form class="text-right" action="book/edit_book" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-lg-5 control-label" for="ISBN">ISBN</label>  
                            <div class="col-lg-7">
                                <input id="ISBN" name="isbn" type="text" 
                                       class="form-control input-md" value="<?= ControllerBook::isbn_format_EAN_13($book->isbn) ?>">
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-lg-5 control-label" for="textinput">AUTEUR.E</label>  
                            <div class="col-lg-7">
                                <input id="textinput" name="author" type="text"  
                                       class="form-control input-md" value="<?= $book->author ?>">
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-lg-5 control-label" for="TITRE">TITRE</label>  
                            <div class="col-lg-7">
                                <input id="textinput" name="title" type="text" 
                                       class="form-control input-md" value="<?= $book->title ?>">
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-lg-5 control-label" for="textinput">EDITION</label>  
                            <div class="col-lg-7">
                                <input id="textinput" name="editor" type="text" 
                                       class="form-control input-md" value="<?= $book->editor ?>">
                            </div>
                        </div>

                        <!-- File Button --> 
                        <div class="form-group">
                            <label class="col-lg-5 control-label" for="picture">COUVERTURE:</label>
                            <div class="col-lg-7">
                                <input id="picture" name="picture" class="input-file" type="file" accept="image/x-png, image/gif, image/jpeg">
                                <br><br>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-5 control-label" for="idbook"></label>
                            <div class="col-md-8">
                                <input type="hidden" name="idbook" value="<?= $book->id ?>">
                                <button id="idbook" class="btn btn-success"  type="submit">
                                    <span>Valider</span>
                                </button>
                                <input type="hidden" name="cancel" value="<?= $book->id ?>">
                                <button id="cancel"  class="btn btn-warning"  type="submit">
                                    <span>Annuler</span>
                                </button>
                                <!--<a href="book/index" alt="book manager" class="btn btn-warning">Annuler</a>-->
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>

            <div class="col-lg-4">
                <?php if ($book->picture !== NULL): ?>
                    <img src='uploads/<?= $book->picture ?>' width="250" alt="Book image">
                    <br><br>
                <?php else: ?>
                    <img src='uploads/images.png' width="250" alt="Book image">
                    <br><br>
                <?php endif; ?>
                <form action="book/edit_book" method="post">
                    <input type="hidden" name="delimageH" value="<?= $book->id ?>">
                    <button  name="delimage" class="btn btn-sm btn-warning" type="submit">
                        <span>effacer l'image</span>
                    </button>
                </form>
            </div>
        </div>
        <div class='text-danger'>
            <?php if ($errors !== []): ?>
                <p>Erreur(s) à corriger:</p>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </body>
</html>
