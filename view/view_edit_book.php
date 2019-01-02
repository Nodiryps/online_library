<!DOCTYPE html>
<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <meta charset="UTF-8">
        <title>edit books!</title>
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
            <div class="title" style="position:absolute;top:20px;right:10px;">
                <?= $profile->fullname; ?>'s profile! (<?= $profile->role ?>) 
            </div>
        </nav>

        <form class="form-horizontal" action="book/edit_book">
            <fieldset>

                <legend class="text-center">EDITION DE <?= strtoupper($book->title) ?> </legend>

                <!-- Text input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="ISBN">ISBN</label>  
                    <div class="col-md-5">
                        <input id="ISBN" name="ISBN" type="text" 
                               class="form-control input-md" value="<?= $book->isbn ?>">
                    </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="textinput">AUTHOR</label>  
                    <div class="col-md-5">
                        <input id="textinput" name="textinput" type="text"  
                               class="form-control input-md" value="<?= $book->author ?>">
                    </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="TITRE">TITRE</label>  
                    <div class="col-md-5">
                        <input id="textinput" name="textinput" type="text" 
                               class="form-control input-md" value="<?= $book->title ?>">
                    </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="textinput">EDITOR</label>  
                    <div class="col-md-5">
                        <input id="textinput" name="textinput" type="text" 
                               class="form-control input-md" value="<?= $book->editor ?>">
                    </div>
                </div>

                <!-- File Button --> 
                <div class="form-group">
                    <label class="col-md-4 control-label" for="picture">CHOISIR UN FICHIER</label>
                    <div class="col-md-4">
                        <input id="picture" name="picture" class="input-file" type="file" accept="image/x-png, image/gif, image/jpeg">
                        <?php if ($book->picture): ?>
                            <img src='upload/<?= $book->picture ?>' width="100" alt="Profile image"><br><br>
                        <?php endif; ?>
                        <button  name="button2id" class="btn btn-warning">
                            <span class="glyphicon glyphicon-">effacer</span>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label" for="idbook"></label>
                    <div class="col-md-8">

                        <button id="idbook" class="btn btn-success" name="valider" type="submit">
                            <span class="glyphicon glyphicon-ok"> Valider</span>
                        </button>
                        </form>

                        <button id="button2id" name="button2id" class="btn btn-warning" type="submit" name="annuller">
                            <span class="glyphicon glyphicon-remove">
                                <a href="book/index" alt="book manager" >Annuler</a>
                            </span>
                        </button>
                    </div>
                </div>
            </fieldset>
        </form>





        <?php
        if ($error !== "")
            echo "<p><span class='errors'>$error</span></p>";
        ?>
    </body>
</html>
