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
            <?php include('menu.html'); ?>
        </nav>
        
            <form class="form-horizontal">
                <fieldset>

                    <legend class="text-center">EDITION DE <?= strtoupper($editbook->title)?> </legend>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="ISBN">ISBN</label>  
                        <div class="col-md-5">
                            <input id="ISBN" name="ISBN" type="text" placeholder="placeholder" class="form-control input-md" value="<?= $editbook->isbn ?>">
                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">AUTHOR</label>  
                        <div class="col-md-5">
                            <input id="textinput" name="textinput" type="text" placeholder="placeholder" class="form-control input-md" value="<?= $editbook->author ?>">
                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="TITRE">TITRE</label>  
                        <div class="col-md-5">
                            <input id="textinput" name="textinput" type="text" placeholder="placeholder" class="form-control input-md" value="<?= $editbook->title ?>">
                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textinput">EDITOR</label>  
                        <div class="col-md-5">
                            <input id="textinput" name="textinput" type="text" placeholder="placeholder" class="form-control input-md" value="<?= $editbook->editor ?>">
                        </div>
                    </div>

                    <!-- File Button --> 
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="picture">CHOISIR UN FICHIER</label>
                        <div class="col-md-4">
                            <input id="picture" name="picture" class="input-file" type="file">
                            <button  name="button2id" class="btn btn-warning"><span class="glyphicon glyphicon-">effacer</span></button>
                        </div>
                    </div>
<!--                    <div class="container text-center">
                        <img src="img/bibli_logo.png" alt="<?= $editbook->title?>" height="42" width="42">
                    </div>-->

                    <!-- Button (Double) -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="idbook"></label>
                        <div class="col-md-8">
                            <form method="post" action="book/index">
                            <button id="idbook" name="idbook" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>  Valider</button>
                            </form>
                            
                            <button id="button2id" name="button2id" class="btn btn-warning"><span class="glyphicon glyphicon-remove"><a href="book/index" alt="book manager" > Annuler</a></span></button>
                        </div>
                    </div>
                </fieldset>
            </form>

        
        <?php
        // put your code here
        ?>
    </body>
</html>
