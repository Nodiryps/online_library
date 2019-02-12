<!DOCTYPE html>

<html>
    <head>
        <title>bibliothèque</title>
    </head>
    <body>

        <nav   class="text-right"> 

            <?php
            if ($profile->is_member())
                include('menuMember.html');
            if ($profile->is_admin() || $profile->is_manager())
                include('menu.html');
            ?>
            <div class="text-right" style="position:absolute;top:20px;right:10px;">
                <p> <strong>  <?= $profile->fullname; ?>'s profile! (<?= $profile->role ?>) </strong></p>
            </div>

        </nav>

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
                        <?php if (!empty($book->picture)): ?>
                            <td class="text-center">  
                                <img  id="zoomimg" style="width: 45px;  " src='uploads/<?= $book->picture ?>' width="100" alt="Couverture">

                            </td>
                        <?php else: ?>
                            <td class="text-center">  
                                <img id="zoomimg" style="width: 45px;" src='uploads/images.png' width="100" alt="Couverture">

                            </td>
                        <?php endif; ?>
                        <?php if ($profile->role == "admin"): ?>
                            <td style="border:none;" bgcolor="white">
                                <form  method="post" action="book/edit_book">
                                    <input type="hidden" name="editbook" value="<?= $book->id ?>">
                                    <input type="hidden" name="panierof" value="<?= $actualpanier->id ?>">
                                    <button type="submit" name="idsubmit" class="btn btn-info">
                                        <span class="glyphicon glyphicon-pencil"></span >
                                    </button>
                                </form>
                            </td>

                        <?php else: ?>
                            <td style="border:none;" bgcolor="white">
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
                            <td style="border:none;margin-left:10px;" bgcolor="white">
                                <form  method="post" action="book/delete_book">
                                    <input type="hidden" name="delbook" value="<?= $book->id ?>">
                                    <input type="hidden" name="panierof" value="<?= $actualpanier->id ?>">
                                    <button type="submit" name="idsubmit" class="btn btn-danger">
                                        <span class="glyphicon glyphicon-trash"></span >
                                    </button>
                                </form>
                            </td>
                        <?php endif; ?>
                        <td style="border:none;" bgcolor="white">
                            <form  method="post" action="rental/add_rental_in_basket">
                                <input type="hidden" name="idbook" value="<?= $book->id ?>">
                                <input type="hidden" name="panierof" value="<?= $actualpanier->id ?>">
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
        <?php if ($profile->is_admin()): ?>
            <div class="container text-right">
                <form method="get" action="book/add_book">
                    <button class="btn btn-success">
                        <span>Nouveau livre</span>
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <br>

        <div class="container">
            <table class="table table-striped table-condensed">
                <thead class="thead-dark">
                <legend><h1>Votre panier de locations (<?= sizeof($UserRentals) ?> locations)</h1></legend>
                <tr>
                    <th scope="col">ISNB</th>
                    <th scope="col">TITLE</th>
                    <th scope="col">AUTHOR</th>
                    <th scope="col">EDITOR</th>

                </tr>
                </thead>
                <?php if (!empty($UserRentals)): ?>
                    <?php foreach ($UserRentals as $rent): ?>
                        <tr>
                            <td><?= ControllerBook::isbn_format_EAN_13($rent->isbn) ?></td>
                            <td><?= $rent->title ?></td>
                            <td><?= strtoupper($rent->author) ?></td>
                            <td><?= $rent->editor ?></td>
                            <td style="border:none;" bgcolor="white">
                                <form  method="post" action="book/book_detail">
                                    <input type="hidden" name="idbook" value="<?= $rent->id ?>">
                                    <input type="hidden" name="panierof" value="<?= $actualpanier->id ?>">
                                    <button type="submit" name="idsubmit" class="btn btn-info"><span >apercu</span ></button>
                                </form>
                            </td>
                            <td style="border:none;" bgcolor="white"> 
                                <form  method="post" action="rental/del_one_rental_in_basket">
                                    <input type="hidden" name="delrent" value="<?= $rent->id ?>">
                                    <input type="hidden" name="panierof" value="<?= $actualpanier->id ?>">
                                    <button type="submit"  name="idsubmit" class="btn btn-danger"><span >supprimer du panier</span></button>
                                </form>
                            </td>

                        </tr>

                    <?php endforeach; ?>
                <?php endif; ?>
            </table>


            <?php if ($profile->is_admin() || $profile->is_manager()): ?>
                <form class="form-horizontal" method="post" action="rental/get_basket">
                    <label>le panier est pour: <?= $actualpanier->username ?> </label>
                    <div class="container row">
                        <select id="selectbasic" name="member_rents" class="form-control col-lg-5">
                            <option value="<?= $actualpanier->id ?>"><?= $actualpanier->username ?></option>
                            <?php foreach ($members as $member): ?>

                                <option value="<?= $member->id ?>"><?= $member->username ?></option>

                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="panierof" value="<?= $actualpanier->id ?>">
                        <button class="btn btn-info" type="submit" name="member_selected">
                            <span class=" glyphicon glyphicon-search"></span>
                        </button>
                    </div>
                </form>
                <br>
                <br>
                <form class="form-horizontal " method="post" action="rental/add_rental_for_user_in_basket">
                    <div class="text-right">
                        <input type="hidden" name="panierof" value="<?= $actualpanier->id ?>">
                        <button class="btn btn-success" class="form-group " type="submit" name="test" value="<?php $profile->username ?>"><span class="glyphicon glyphicon-check"> Louer</span></button>
                        <button class="btn btn-danger" class="form-group" type="submit" name="annuler" value="annuler"><pan class="glyphicon glyphicon-remove"> vider</pan></button>
                    </div>
                </form>
            <?php else: ?>
                <form class="form-horizontal " method="post" action="rental/add_rental_for_user_in_basket">
                    <div class="text-right">
                        <input type="hidden" name="solo" value="<?php $profile->id ?>" >
                        <input type="hidden" name="panierof" value="<?= $actualpanier->id ?>">
                        <button class="btn btn-success" class="form-group " type="submit"  ><span class="glyphicon glyphicon-check"> Louer</span></button>
                        <button class="btn btn-danger" class="form-group" type="submit" name="annuler" value="annuler"><pan class="glyphicon glyphicon-remove"> vider</pan></button>
                    </div>

                <?php endif; ?>
            </form>
            <br>
            <br>
        </div>

    </body>
</html>
