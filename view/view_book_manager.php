<!DOCTYPE html>

<html>
    <head>
        <title>Bibliothèque</title>
        <meta charset="utf-8"/>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <meta charset="UTF-8">
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-validation-1.19.0/jquery.validate.min.js" type="text/javascript"></script>
        <script src="js/book.js" type="text/javascript"></script>
    </head>
    <body>
        <nav class="text-right"> 
            <?php
            if ($profile->is_member())
                include('menuMember.html');
            if ($profile->is_admin() || $profile->is_manager())
                include('menu.html');
            ?>
        </nav>
        <p style="position:absolute;top:80px;right:10px;"><strong><?= $profile->fullname; ?>'s profile (<?= $profile->role ?>)</strong></p>

        <form class="" method="post"  action="book/index/">
            <div class="container" >
                <div class="row col-lg-offset-1">
                    <div id="custom-search-input">
                        <div class="input-group col-md-10">
                            <input type="text" class="  search-query form-control" placeholder="rechercher un livre" name="search" id="search"/>
                            <span class="input-group-btn">
                                <input name="idUser" type="hidden" value="<?= $actualpanier->id ?>" id="user" >
                                <button class="btn btn-info" type="submit" id="btnSearch" >
                                    <span class=" glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <br><br><br>
        </form>

        <div class="container table-wrapper-scroll-y list">
            <table class="table table-striped table-condensed " >
                <legend class="text-center">
                    <h1>Bibliothèque</h1>

                </legend>
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center" scope="col">ISNB</th>
                        <th class="text-center" scope="col">TITRE</th>
                        <th class="text-center" scope="col">AUTEUR.E</th>
                        <th class="text-center" scope="col">EDITION</th>
                        <th class="text-center" scope="col">NBCOPIES</th>
                        <th class="text-center" scope="col">COUVERTURE</th>
                    </tr>
                </thead>
         
                <?php foreach ($books as $book): ?>
                    <?php if ($book->nbCopies_to_display() >= 0): ?>
                <tr >
                            <td class="text-center " ><?= Book::isbn_format_EAN_13($book->isbn) ?></td>
                            <td class="text-center " ><?= $book->title ?></td>
                            <td class="text-center" ><?= strtoupper($book->author) ?></td>
                            <td class="text-center" ><?= $book->editor ?></td>
                            <td class="text-center" ><?= $book->nbCopies_to_display(); ?></td>

                            <td class="text-center" >  
                                <img  id="zoomimg" style="width:45px;height:65px;" 
                                <?php if ($book->picture !== NULL || $book->picture != ""): ?>
                                          src='uploads/<?= $book->picture ?>' width="100" alt="Couverture"
                                      <?php else: ?> 
                                          src='uploads/images.png' width="100" alt="Couverture">
                                      <?php endif; ?>
                            </td>

                            <?php if ($profile->role == "admin"): ?>
                                <td style="border:none;" bgcolor="white" >
                                    <form  method="post" action="book/edit_book">
                                        <input type="hidden" name="editbook" value="<?= $book->id ?>">
                                        <input type="hidden" name="panierof" value="<?= $actualpanier->id ?>">
                                        <button type="submit" name="idsubmit" class="btn btn-info">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                    </form>
                                </td>

                            <?php else: ?>
                                <td style="border:none;" bgcolor="white" >
                                    <form  method="post" action="book/book_detail">
                                        <input type="hidden" name="idbook" value="<?= $book->id ?>">
                                        <input type="hidden" name="panierof" value="<?= $actualpanier->id ?>">
                                        <button type="submit" name="idsubmit" class="btn btn-default">
                                            <span class="glyphicon glyphicon-eye-open"></span>
                                        </button>
                                    </form>
                                </td>
                            <?php endif; ?>
                            <?php if ($profile->is_admin()): ?>
                                <td style="border:none;margin-left:10px;" bgcolor="white" id="list">
                                    <form  method="post" action="book/delete_book">
                                        <input type="hidden" name="delbook" value="<?= $book->id ?>">
                                        <input type="hidden" name="panierof" value="<?= $actualpanier->id ?>">
                                        <button type="submit" name="idsubmit" class="btn btn-danger">
                                            <span class="glyphicon glyphicon-trash"></span >
                                        </button>
                                    </form>
                                </td>
                            <?php endif; ?>
                            <?php if ($book->nbCopies_to_display() > 0): ?>
                                <td style="border:none;" bgcolor="white" id="list">
                                    <form  method="post" action="rental/add_rental_in_basket">
                                        <input type="hidden" name="idbook" value="<?= $book->id ?>">
                                        <input type="hidden" name="panierof" value="<?= $actualpanier->id ?>">
                                        <button type="submit"  name="idsubmit" class="btn btn-success">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                           
                        <?php endif;
                    endif; ?>
                              
<?php endforeach; ?>
                           
            </table>
        </div>
        <br>
<?php if ($profile->is_admin()): ?>
            <div  class="container text-left">
                <form method="post" action="book/add_book">
                    <button type="submit"class="btn btn-success">
                        <span>Nouveau livre</span>
                    </button>
                </form>
            </div>
<?php endif; ?>
        <br><br>
        <!-- Debut de la partie Panier --> 
        <div class="container col-lg-offset-1 col-lg-8" >
            <p class="text text-danger"><?= strtoupper($msg) ?></p>
            <table class="table table-striped table-condensed">
                <thead class="thead-dark">
                <legend><h1>Panier (<?= sizeof($UserRentals) ?>/5 loc.)</h1></legend>
                <tr>
                    <th scope="col">ISNB</th>
                    <th scope="col">TITRE</th>
                    <th scope="col">AUTEUR.E</th>
                    <th scope="col">EDITION</th>
                </tr>
                </thead>
                <?php if (!empty($UserRentals)): ?>
    <?php foreach ($UserRentals as $rent): ?>
                        <tr>
                            <td><?= Book::isbn_format_EAN_13($rent->isbn) ?></td>
                            <td><?= $rent->title ?></td>
                            <td><?= strtoupper($rent->author) ?></td>
                            <td><?= $rent->editor ?></td>
                            <td style="border:none;" bgcolor="white">
                                <form  method="post" action="book/book_detail">
                                    <input type="hidden" name="idbook" value="<?= $rent->id ?>">
                                    <input type="hidden" name="panierof" value="<?= $actualpanier->id ?>">
                                    <button type="submit" name="idsubmit" class="btn btn-default glyphicon glyphicon-eye-open">
                                    </button>
                                </form>
                            </td>
                            <td style="border:none;" bgcolor="white"> 
                                <form  method="post" action="rental/del_one_rental_in_basket">
                                    <input type="hidden" name="delrent" value="<?= $rent->id ?>">
                                    <input type="hidden" name="panierof" value="<?= $actualpanier->id ?>">
                                    <button type="submit"  name="idsubmit" class="btn btn-warning glyphicon glyphicon-minus">
                                    </button>
                                </form>
                            </td>
        <?php if ($profile->is_admin()): ?>
                                <td style="border:none;margin-left:10px;" bgcolor="white">
                                    <form  method="post" action="book/delete_book">
                                        <input type="hidden" name="delbook" value="<?= $rent->id ?>">
                                        <input type="hidden" name="panierof" value="<? = $actualpanier->id ?>">
                                        <button type="submit" name="idsubmit" class="btn btn-danger">
                                            <span class="glyphicon glyphicon-trash"></span >
                                        </button>
                                    </form>
                                </td>
                        <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
<?php endif; ?>
            </table>
        </div>
        <div class="col-lg-2 ">
<?php if ($profile->is_admin() || $profile->is_manager()): ?>
                <br><br><br>

                <label>Panier pour:</label> <?= $actualpanier->username ?>
                <br>
                <form class="form-horizontal" method="post" action="rental/get_basket">
                    <select id="selectbasic" name="member_rents" class="form-control">
                        <option value="<?= $actualpanier->id ?>"><?= $actualpanier->username ?></option>
                        <?php foreach ($members as $member):
                            if ($member->id !== $actualpanier->id):
                                ?>
                                <option value="<?= $member->id ?>"><?= $member->username ?>,<?= $actualpanier->id ?></option>
        <?php endif;
    endforeach;
    ?>
                    </select>

                    <input type="hidden" name="panierof" value="<?= $actualpanier->id ?>">
                    <button class="btn btn-info col-lg-12" type="submit" name="member_selected">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </form>
                <br><br><br><br>
<?php endif;
if ($profile->is_admin() || $profile->is_manager() || $profile->is_member()):
    ?>
    <?php if ($profile->is_member()): ?><br><br><br><br><?php endif; ?>
                <div>
                    <form class="form-horizontal" method="post" 
                          action="rental/rent_books_in_basket">
                        <input type="hidden" name="panierof" value="<?= $actualpanier->id ?>">
                        <button class="btn btn-success" type="submit" value="<?php $profile->username ?>">
                            <span class="glyphicon glyphicon-check"> Louer</span>
                        </button>

                        <button class="col-lg-offset-4 btn btn-danger" type="submit" name="annuler" value="annuler">
                            <span class="glyphicon glyphicon-remove"> Vider</span>
                        </button>
                    </form>
                </div>
                <br><br><br><br>
            </div>
<?php endif; ?>

    </body>

</html>
