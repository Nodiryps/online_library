<!DOCTYPE html>

<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <meta charset="UTF-8">
        <title>bibliothèque</title>
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
        

        <nav style="position:fixed;z-index:9000;top:0px;width:100%; margin-bottom: 1px; "> 
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
<?php echo $test; ?>
        <form class="" method="post"  action="book/index">
            <div class="container" style="margin-top:100px;margin-bottom:-30px;">
                <div class="row">
                    <div id="custom-search-input">
                        <div class="input-group col-md-12">
                            <input type="text" class="  search-query form-control" placeholder="rechercher un livre" name="search"/>
                            <span class="input-group-btn">
                                <button class="btn btn-info" type="submit" value="rechercher">
                                    <span class=" glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                  
                </div>
            </div>
            <br><br><br>
        </form>

        <div class="container table-wrapper-scroll-y">
            <table class="table table-striped table-condensed " >
                <legend class="text-center"><h1>Bibliothèque</h1></legend>
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center" scope="col">ISNB</th>
                        <th class="text-center" scope="col">TITRE</th>
                        <th class="text-center" scope="col">AUTEUR.E</th>
                        <th class="text-center" scope="col">EDITION</th>
                        <th class="text-center" scope="col">COUVERTURE</th>
                    </tr>
                </thead>

                <?php foreach ($books as $book): ?>
                    <tr>
                        <td class="text-center"><?= ControllerBook::isbn_format_EAN_13($book->isbn) ?></td>
                        <td class="text-center"><?= $book->title ?></td>
                        <td class="text-center"><?= strtoupper($book->author) ?></td>
                        <td class="text-center"><?= $book->editor ?></td>
                        <td class="text-center"><?= $book->picture ?></td>
                        <?php if ($profile->role == "admin"): ?>
                            <td style="border:none;" bgcolor="white">
                                <form  method="post" action="book/edit_book">
                                    <input type="hidden" name="editbook" value="<?= $book->id ?>">
                                    <button type="submit" name="idsubmit" class="btn btn-info">
                                        <span class="glyphicon glyphicon-pencil"></span >
                                    </button>
                                </form>
                            </td>

                        <?php else: ?>
                            <td style="border:none;" bgcolor="white">
                                <form  method="post" action="book/book_detail">
                                    <input type="hidden" name="idbook" value="<?= $book->id ?>">
                                    <button type="submit" name="idsubmit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-eye-open"></span>
                                    </button>
                                </form>
                            </td>
                        <?php endif; ?>
                        <?php if ($profile->is_admin()): ?>
                            <td style="border:none;margin-left:10px;" bgcolor="white">
                                <form  method="post" action="book/delete_book">
                                    <input type="hidden" name="delbook" value="<?= $book->id ?>">
                                    <button type="submit" name="idsubmit" class="btn btn-danger">
                                        <span class="glyphicon glyphicon-trash"></span >
                                    </button>
                                </form>
                            </td>
                        <?php endif; ?>
                        <td style="border:none;" bgcolor="white">
                            <form  method="post" action="rental/add_rental">
                                <input type="hidden" name="idbook" value="<?= $book->id ?>">
                                <button type="submit"  name="idsubmit" class="btn btn-success">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

               

            </table>
             <p style="color: red;"><?= strtoupper($msg) ?></p>
        </div>
        <div class="container text-right">
            <form method="get" action="book/create_book">
                <button type="submit"  name="createBook" class="btn btn-success">
                    <span>Nouveau livre</span>
                </button>
            </form>
        </div>
        
        <br>

        <div class="container">
            <table class="table table-striped table-condensed">
                <thead class="thead-dark">
                <legend><h1>Votre panier de locations (<?= sizeof($UserRentals)?> livres)</h1></legend>
                <tr>
                    <th scope="col">ISNB</th>
                    <th scope="col">TITLE</th>
                    <th scope="col">AUTHOR</th>
                    <th scope="col">EDITOR</th>
                    <th scope="col">PICTURE</th>
                </tr>
                </thead>
                <?php if (!empty($UserRentals)): ?>
                    <?php foreach ($UserRentals as $rent): ?>
                        <tr>
                            <td><?= ControllerBook::isbn_format_EAN_13($rent->isbn) ?></td>
                            <td><?= $rent->title ?></td>
                            <td><?= strtoupper($rent->author) ?></td>
                            <td><?= $rent->editor ?></td>
                            <td><?= $rent->picture ?></td>
                            <td style="border:none;" bgcolor="white">
                                <form  method="post" action="book/book_detail">
                                    <input type="hidden" name="idbook" value="<?= $rent->id ?>">
                                    <button type="submit" name="idsubmit" class="btn btn-info"><span >apercu</span ></button>
                                </form>
                            </td>
                            <td style="border:none;" bgcolor="white"> 
                                <form  method="post" action="rental/del_one_rent">
                                    <input type="hidden" name="delrent" value="<?= $rent->id ?>">
                                    <button type="submit"  name="idsubmit" class="btn btn-danger"><span >supprimer du panier</span></button>
                                </form>
                            </td>

                        </tr>

                    <?php endforeach; ?>
                <?php endif; ?>
            </table>

            <form class="form-horizontal " method="post" action="rental/add_rental_for_user">
                <?php if ($profile->is_admin() || $profile->is_manager()): ?>

                    <label>le panier est pour: </label>

                    <select id="selectbasic" name="member_rent" class="form-control">
                        <option><?= $profile->username ?></option>
                        <?php foreach ($members as $member): ?>

                            <option><?= $member->username ?></option>

                        <?php endforeach; ?>
                    </select>
                    <br>
                    <br>
                    <div class="text-right">
                        <button class="btn btn-success" class="form-group " type="submit" name="test" value="<?php $profile->username ?>"><span class="glyphicon glyphicon-check"> Louer</span></button>
                        <button class="btn btn-danger" class="form-group" type="submit" name="annuler" value="annuler"><pan class="glyphicon glyphicon-remove"> vider</pan></button>
                    </div>
                </form>
            <?php else: ?>
                <form  class="form-horizontal " method="post" action="rental/add_rental_for_user">
                    <div class="text-right">
                        <input type="hidden" name="member_rents" value="<?php $profile->username ?>" >
                        <button class="btn btn-success" class="form-group " type="submit" name="test" ><span class="glyphicon glyphicon-check"> Louer</span></button>
                        <button class="btn btn-danger" class="form-group" type="submit" name="annuler" value="annuler"><pan class="glyphicon glyphicon-remove"> vider</pan></button>
                    </div>
                </form>
            <?php endif; ?>
            <br>
            <br>
        </div>
    </body>
</html>
