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
                <strong> <?= $profile->fullname; ?>'s profile! (<?= $profile->role ?>) </strong>
            </div>
        </nav>

        <form class="form-horizontal" action="book/edit_book" method="post">
            <fieldset>

                <legend class="text-center">EDITION DE <?= strtoupper($book->title) ?> </legend>

                <!-- Text input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="ISBN">ISBN</label>  
                    <div class="col-md-5">
                        <input id="ISBN" name="isbn" type="text" 
                               class="form-control input-md" value="<?= ControllerBook::isbn_format_EAN_13($book->isbn) ?>">
                    </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="textinput">AUTHOR</label>  
                    <div class="col-md-5">
                        <input id="textinput" name="author" type="text"  
                               class="form-control input-md" value="<?= $book->author ?>">
                    </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="TITRE">TITRE</label>  
                    <div class="col-md-5">
                        <input id="textinput" name="title" type="text" 
                               class="form-control input-md" value="<?= $book->title ?>">
                    </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="textinput">EDITOR</label>  
                    <div class="col-md-5">
                        <input id="textinput" name="editor" type="text" 
                               class="form-control input-md" value="<?= $book->editor ?>">
                    </div>
                </div>
                
                <!-- File Button --> 
                <div class="form-group">
                    <label class="col-md-4 control-label" for="picture">CHOISIR UN FICHIER</label>
                    <div class="col-md-4">
                        <input id="picture" name="picture" class="input-file" type="file" accept="image/x-png, image/gif, image/jpeg">
                        <br><br>
                          <?php if(!empty($book->picture)): ?>
                            <img src='uploads/<?= $book->picture ?>' width="100" alt="Book image">
                            <br><br>
                       <?php else:?>
                            <img src='uploads/images.png' width="100" alt="Book image">
                            <br><br>
                            <?php endif;?>
                        <button  name="delimage" class="btn btn-warning">
                            <span class="glyphicon glyphicon-remove"> effacer</span>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label" for="idbook"></label>
                    <div class="col-md-8">
                        <input type="hidden" name="idbook" value="<?= $id ?>">
                        <button id="idbook" class="btn btn-success"  type="submit">
                            <span class="glyphicon glyphicon-ok">Valide</span>
                        </button>
                        
                        <button id="button2id" name="button2id" class="btn btn-warning" type="submit">
                            <span class="glyphicon glyphicon-remove">
                                <a href="book/index" alt="book manager" >Annuler</a>
                            </span>
                        </button>
                    </div>
                </div>
            </fieldset>
        </form>
        <?php if ($errors !== []): ?>
            <div class='errors'>
                <p>Erreur(s) à corriger:</p>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif (strlen($success) != 0): ?>
            <p><span class='success'><?= $success ?></span></p>
            <?php endif; ?>
    </body>
</html>
