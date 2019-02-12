<!DOCTYPE html>
<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <title>retours</title>
        <meta charset="UTF-8">
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body >
        <nav> 
            <?php
            if ($profile->is_member())
                include('menuMember.html');
            if ($profile->is_admin() || $profile->is_manager())
                include('menu.html');
            ?>
            <div class="title" style="position:absolute;top:20px;right:10px;">
                <strong>  <?= $profile->fullname; ?>'s profile! (<?= $profile->role ?>) </strong>
            </div>
        </nav>
        <div class="container  ">
            <form method="post" action="rental/search_book" class="form-control-static">
                <div class="row align-items-center justify-content-center " >

                    <div class="col-md-2 pt-3">
                        <div class="form-group ">
                            <input type="text" name="title" placeholder="TITRE" class="form-control" value="<?= $title ?>">
                        </div>
                    </div>
                    <div class="col-md-2 pt-3">
                        <div class="form-group ">
                            <input type="text" name="author" placeholder="AUTHEUR" class="form-control" value="<?= $author ?>">
                        </div>
                    </div>
                    <div class="col-md-2 pt-3">
                        <div class="form-group">
                            <div class="form-group ">
                                <input type="date" name="date"  class="form-control" value="<?php echo date("d/m/Y"); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 pt-3">
                        <div class="form-group">
                            <select id="inputState" name="filtre" class="form-control"  >
                                
                                <option <?php 
                                  if (!empty($filter) && $filter=="Tous"){?>
                                      selected
                                      
                                  <?php }
                                ?> value="Tous" >Tous</option>
                                <option 
                                    <?php 
                                  if (!empty($filter) && $filter=="location"){?>
                                      selected
                                      
                                 <?php }
                                ?>value="location">location</option>
                                <option 
                                    <?php 
                                  if (!empty($filter) && $filter=="retour"){?>
                                      selected
                                      
                                  <?php }
                                ?>value="retour">Retour</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">FILTRER</button>
                    </div>


                </div>
            </form>

        </div>
        <br>
        <br>
        <div>
            <div class="container table-wrapper-scroll-y">
                <table class="table table-striped table-condensed " >

                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center" scope="col">RENTAL DATE</th>
                            <th class="text-center" scope="col">MEMBER</th>
                            <th class="text-center" scope="col">BOOK</th>
                            <th class="text-center" scope="col">RETURN-DATE</th>
                          
                        </tr>
                    </thead>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td class="text-center"><?= $book->rentaldate ?></td>
                            <td class="text-center"><?= User::get_username_by_id($book->user) ?></td>
                            <td class="text-center"><?= Book::get_title_by_id($book->book) ?> (<?= strtoupper(Book::get_author_by_id($book->book)) ?>)</td>
                            <td class="text-center"><?= $book->returndate ?></td>
                            <?php if ($profile->role == "admin"): ?>

                                <td style="border:none;" bgcolor="white">
                                    <form  method="post" action="rental/delete_rental">
                                        <input type="hidden" name="delrent" value="<?= $book->id ?>">
                                        <button type="submit" name="idsubmit" class="btn btn-danger ">
                                            <span class="glyphicon glyphicon-trash text-right"></span >
                                        </button>
                                    </form>
                                </td>

                                <?php if ($book->returndate == null): ?>
                                    <td style="border:none;" bgcolor="white">
                                        <form  method="post" action="rental/update_rental_returndate">
                                            <input type="hidden" name="idbook" value="<?= $book->id ?>">
                                            <button type="submit" name="idsubmit" class="btn btn-success">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </button>
                                        </form>
                                    </td>
                                <?php else: ?>
                                    <td style="border:none;" bgcolor="white">
                                        <form  method="post" action="rental/cancel_rental_returndate">
                                            <input type="hidden" name="idcancel" value="<?= $book->id ?>">
                                            <button type="submit" name="idsubmit" class="btn btn-warning">
                                                <span class="glyphicon glyphicon-repeat"></span>
                                            </button>
                                        </form>
                                    </td>

                                <?php endif; ?>
                            <?php endif; ?>
                                     <?php if ($profile->role == "manager"): ?>
                            <?php if ($book->returndate == null): ?>
                                <td style="border:none;" bgcolor="white">
                                    <form  method="post" action="rental/update_rental_returndate">
                                        <input type="hidden" name="idbook" value="<?= $book->id ?>">
                                        <button type="submit" name="idsubmit" class="btn btn-success">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </button>
                                    </form>
                                </td>
                            <?php else: ?>
                                <td style="border:none;" bgcolor="white">
                                    <form  method="post" action="rental/cancel_rental_returndate">
                                        <input type="hidden" name="idcancel" value="<?= $book->id ?>">
                                        <button type="submit" name="idsubmit" class="btn btn-warning">
                                            <span class="glyphicon glyphicon-remove-sign"></span>
                                        </button>
                                    </form>
                                </td>

                            <?php endif; ?>
                                   <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </body>
</html>
