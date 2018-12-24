<!DOCTYPE html>

<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <meta charset="UTF-8">
        <title>books Manager!</title>
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
        <div class="container">
            <h1>Detail de <?= $book->title ?></h1>
        </div>
        <h1></h1>
        <div class="container" class="row">
            <table class="table table-dark" class="col-lg-6">
                <thead class="thead-dark">
                <tr>
                    <th>ISBN</th>
                    <th>AUTHOR</th>
                    <th>TITLE</th>
                    <th>EDITOR</th>
                </tr>
                <tr>
                    <td><?= $book->isbn  ?></td>
                    <td><?= $book->author  ?></td>
                    <td><?= $book->title  ?></td>
                    <td><?= $book->editor  ?></td>
                </tr>
                    
            </table>
            <button class="btn btn-info"><a href="book/index" style="color: white ">retour a la liste des livres</a></button>
        </div>
    </body>
</html>
