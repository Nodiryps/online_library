<!DOCTYPE html>
<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <title>Retours</title>
        <meta charset="UTF-8">
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->

        <!--Plugin Fullcalendar-->

        <link href='lib/fullcalendar/packages/core/main.css' rel='stylesheet' />
        <link href='lib/fullcalendar/packages/daygrid/main.css' rel='stylesheet' />

        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-validation-1.19.0/jquery.validate.min.js" type="text/javascript"></script>
        
        <!--Script javascript et Jquery-->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

        <script src='lib/fullcalendar/packages/moment/main.js'></script>
        <script src='lib/fullcalendar/packages/core/main.js'></script>
        <script src='lib/fullcalendar/packages/interaction/main.js'></script>
        <script src='lib/fullcalendar/packages/daygrid/main.js'></script>
    </head>
    <body>
        <script>
            $(function(){
            console.log("methode");
            var calendar = new FullCalendar.Calendar($('#phpAffiche')[0], {
            plugins: [ 'interaction', 'dayGrid', 'timeGrid' ],
                    defaultView: 'dayGridMonth',
                    defaultDate: '2019-05-07',
                    header: {
                    left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: [
                    {
                    title: 'All Day Event',
                            start: '2019-05-01'
                    },
                    {
                    title: 'Long Event',
                            start: '2019-05-07',
                            end: '2019-05-10'
                    },
                    {
                    groupId: '999',
                            title: 'Repeating Event',
                            start: '2019-05-09T16:00:00'
                    },
                    {
                    groupId: '999',
                            title: 'Repeating Event',
                            start: '2019-05-16T16:00:00'
                    },
                    {
                    title: 'Conference',
                            start: '2019-05-11',
                            end: '2019-05-13'
                    },
                    {
                    title: 'Meeting',
                            start: '2019-05-12T10:30:00',
                            end: '2019-05-12T12:30:00'
                    },
                    {
                    title: 'Lunch',
                            start: '2019-05-12T12:00:00'
                    },
                    {
                    title: 'Meeting',
                            start: '2019-05-12T14:30:00'
                    },
                    {
                    title: 'Birthday Party',
                            start: '2019-05-13T07:00:00'
                    },
                    {
                    title: 'Click for Google',
                            url: 'http://google.com/',
                            start: '2019-05-28'
                    }
                    ]
            });
            calendar.render();
            });

        </script>
        <nav> 
<?php
if ($profile->is_member())
    include('menuMember.html');
if ($profile->is_admin() || $profile->is_manager())
    include('menu.html');
?>
        </nav>
        <p style="position:absolute;top:80px;right:10px;"><strong>  <?= $profile->fullname; ?>'s profile! (<?= $profile->role ?>) </strong></p>

        <div class="container col-lg-offset-0 col-md-11">
            <form method="post" action="rental/search_book" class="form-control-static">
                <div class="row align-items-center justify-content-center " >

                    <div class="col-lg-offset-1 col-md-2 pt-3">
                        <div class="form-group ">
                            <input type="text" name="title" placeholder="TITRE" class="form-control" value="<?= $title ?>">
                        </div>
                    </div>
                    <div class="col-md-2 pt-3">
                        <div class="form-group ">
                            <input type="text" name="author" placeholder="AUTHEUR.E" class="form-control" value="<?= $author ?>">
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
                                <option <?php if (!empty($filter) && $filter == "tous") { ?>
                                        selected
<?php }
?> value="tous" >Tous</option>
                                <option 
                                    <?php if (!empty($filter) && $filter == "location") { ?>
                                        selected
                                <?php }
                                ?>value="location">Location</option>
                                <option 
                                    <?php if (!empty($filter) && $filter == "retour") { ?>
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
        <br><br><br><br><br>

        <div class="container table-wrapper-scroll-y " id="phpAffiche">
            <table class="table table-striped table-condensed " >
                <legend class="text-center"><h1>Retours</h1></legend>
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center" scope="col">DATE LOCATION</th>
                        <th class="text-center" scope="col">MEMBRE</th>
                        <th class="text-center" scope="col">LIVRE</th>
                        <th class="text-center" scope="col">DATE RETOUR</th>

                    </tr>
                </thead>
                <div>
<?php foreach ($books as $book): ?>
                        <tr>
                            <td class="text-center"><?= $book->rentaldate ?></td>
                            <td class="text-center"><?= User::get_username_by_id($book->user) ?></td>
                            <td class="text-center"><strong><?= Book::get_title_by_id($book->book) ?></strong> (<?= strtoupper(Book::get_author_by_id($book->book)) ?>)</td>
                            <td class="text-center"><?= $book->returndate ?></td>
    <?php if ($book->returndate == null): ?>
        <?php if ($profile->role == "admin" || $profile->role == "manager"): ?>
                                    <td style="border:none;" bgcolor="white">
                                        <form  method="post" action="rental/update_rental_returndate">
                                            <input type="hidden" name="idbook" value="<?= $book->id ?>">
                                            <button type="submit" name="idsubmit" class="btn btn-success">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </button>
                                        </form>
                                    </td>
            <?php
        endif;
    elseif ($book->returndate !== null):
        ?>
                                <?php if ($profile->role == "admin" || $profile->role == "manager"): ?>
                                    <td style="border:none;" bgcolor="white">
                                        <form  method="post" action="rental/cancel_rental_returndate">
                                            <input type="hidden" name="idcancel" value="<?= $book->id ?>">
                                            <button type="submit" name="idsubmit" class="btn btn-warning">
                                                <span class="glyphicon glyphicon-repeat"></span>
                                            </button>
                                        </form>
                                    </td>
            <?php
        endif;
        if ($profile->role == "admin"):
            ?>
                                    <td style="border:none;" bgcolor="white">
                                        <form  method="post" action="rental/delete_rental">
                                            <input type="hidden" name="delrent" value="<?= $book->id ?>">
                                            <button type="submit" name="idsubmit" class="btn btn-danger ">
                                                <span class="glyphicon glyphicon-trash text-right"></span >
                                            </button>
                                        </form>
                                    </td>
        <?php
        endif;
    endif;
    ?>
                        </tr>
                        <?php endforeach; ?>
            </table>

        </div>
    </div>
</body>
</html>
