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
         <div class="title"><?php echo $profile->username; ?>'s Profile! (<?= $profile->role ?>) </div>
        <nav> 
            <?php include('menu.html'); ?>
        </nav>
        <div id="titre" class="container" class="row" >
            <h1  >Location des livres</h1>
        </div >
        <div id="search_bar "class="container" class="row" >
            <nav class="navbar navbar-light bg-light"  class="col-lg-12" >
                <form class="form-inline" method="post"  action="book/index">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" value="rechercher">Search</button>
                </form>
            </nav>
        </div>

        <div class="container">
            <table class="table table-striped table-condensed">
                <thead class="thead-dark">
                    <tr >
                        <th scope="col">ISNB</th>
                        <th scope="col">TITLE</th>
                        <th scope="col">AUTHOR</th>
                        <th scope="col">EDITOR</th>
                        <th scope="col">PICTURE</th>
                        <th scope="col">ACTION</th>
                    </tr>
                </thead>

                <?php foreach ($books as $book): ?>
                    <tr >
                        <td><?= $book->isbn ?></td>
                        <td><?= $book->title ?></td>
                        <td><?= $book->author ?></td>
                        <td><?= $book->editor ?></td>
                        <td><?= $book->picture ?></td>
                        <td>
                            <form  method="post" action="book/book_detail">
                                <input type="hidden" name="idbook" value="<?= $book->id ?>">
                                <button type="submit" name="idsubmit" class="btn btn-info"><span >apercu</span ></button>
                            </form>
                        </td>
                        <td> 
                            <form  method="post" action="book/add_rental">
                                <input type="hidden" name="idbook" value="<?= $book->id ?>">
                                <button type="submit"  name="idsubmit" class="btn btn-info"><span>panier</span></button>
                            </form>
                        </td>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <br>
        <br>

        <div id="titre" class="container" class="row" >
            <h1>Votre panier de location</h1>
        </div >
        <div class="container">
            
            <table class="table table-dark">
                <thead class="thead-dark"></thead>
               <tr >
                        <th scope="col">ISNB</th>
                        <th scope="col">TITLE</th>
                        <th scope="col">AUTHOR</th>
                        <th scope="col">EDITOR</th>
                        <th scope="col">PICTURE</th>
                        <th scope="col">ACTION</th>
                    </tr>
            </table>
        </div>
    </body>
</html>
