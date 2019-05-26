<!DOCTYPE html>
<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <title>Retours</title>
        <meta charset="UTF-8">
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

        <link href='lib/fullcalendar/packages/core/main.css' rel='stylesheet' />
        <link href='lib/fullcalendar/packages/timeline/main.css' rel='stylesheet' />
        <link href='lib/fullcalendar/packages/resource-timeline/main.css' rel='stylesheet' />
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.structure.min.css" rel="stylesheet" type="text/css"/>

        <script src='lib/fullcalendar/packages/core/main.js'></script>
        <script src='lib/fullcalendar/packages/timeline/main.js'></script>
        <script src='lib/fullcalendar/packages/resource-common/main.js'></script>
        <script src='lib/fullcalendar/packages/resource-timeline/main.js'></script>
        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" type="text/javascript"></script>
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>

    </head>
    <body>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                $('#tabReturn').hide();
                $('#legend').hide();
                $('#btnFiltre').hide();
                $('select').change(function () {
                    $("select option:selected").each(function () {
                        console.log($(this).text());
                    });
                });

                $("#title").change(function () {
                    $.get("rental/getRentalsRessources", function (data) {
                        console.log(JSON.parse(data));
                    });
                });

                var calendarEl = document.getElementById('phpAffiche');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                refetchResourcesOnNavigate: true,
                        plugins: ['interaction', 'resourceTimeline'],
                        timeZone: 'UTC',
                        defaultView: 'resourceTimelineWeek',
                        aspectRatio: 1.5,
                        allDaySlot: false,
                        eventLimit: true,
                        height: "",
                        firstday: 1,
                        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                        header: {
                        left: 'today, prev, next',
                                center: 'title',
                                right: 'resourceTimelineWeek, resourceTimelineMonth, resourceTimelineYear',
                        },
                        views: {
                        year: {
                        slotDuration: {month: 1}
                        },
                                month: {
                                slotDuration: {day: 1},
                                        slotLabelFormat: [
                                        {day: 'numeric'}
                                        ]
                                },
                                week: {
                                slotDuration: {day: 1},
                                        slotLabelFormat: [
                                        {day: 'numeric'}
                                        ]
                                }

                        },
                        editable: false,
                        selectable: true,
                        resourceColumns: [
                        {
                        labelText: 'Users',
                                field: 'user'
                        },
                        {
                        labelText: 'Books',
                                field: 'book'
                        }
                        ],
                        resources: {
                        url: "rental/getRentalsRessources",
                                method: 'POST',
                                extraParams: function () {
                                return {
                                title: $("#title").val(),
                                        author: $("#author").val(),
                                        rentaldate: $("#rentaldate").val(),
                                        rentalId: $("#rentalid").val(),
                                        select: $('select option:selected').each(function () {
                                return  $(this).text();
                                })
                                };
                                }
                        },
                        events: {
                        url: "rental/getRentalsEvents",
                                method: 'POST',
                                extraParams: function () {
                                return {
                                title: $("#title").val(),
                                        author: $("#author").val(),
                                        rentaldate: $("#rentaldate").val(),
                                        rentalId: $("#rentalid").val(),
                                        select: $('select option:selected').each(function () {
                                return  $(this).text();
                                })

                                };
                                }
                        },
                        eventClick: function (data) {
                        var res = data.event.getResources();
                                var colonne = res["0"]._resource.extendedProps;
                                $("#rentalid").text(data.event.id);
                                $("#user").text(colonne.user);
                                $("#bookTitle").text(colonne.book);
                                $("#start").text(data.event.start.toDateString());
                                $("#end").text(colonne.end);
                                $('#confirmDialog').dialog({
                        resizable: false,
                                height: 300,
                                width: 500,
                                modal: true,
                                autoOpen: true
<?php if ($profile->is_admin()): ?>
                            , buttons: {
                            retourner: function () {
                            $.post("Rental/return_date", {rentId: data.event.id}, refetch, "html");
                                    $(this).dialog("close");
                            },
                                    supprimer: function () {
                                    $.post("rental/delete_RentalJS", {rentdel: data.event.id}, refetch, "html");
                                            $(this).dialog("close");
                                    }
                            }
<?php endif; ?>
                        });
                        }
                }
                );

                calendar.render();
                $("#title, #author, #rentaldate, select, #rentalid").on("input", function () {
                    refetch();
                });

                function refetch() {
                    calendar.refetchEvents();
                    calendar.refetchResources();
                }
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
                    <input type="hidden" value="<?= $profile->id ?>" id="user"/>
                    <div class="col-lg-offset-1 col-md-2 pt-3">
                        <div class="form-group ">
                            <input type="text" name="title" placeholder="TITRE" class="form-control" value="<?= $title ?>" id="title">
                        </div>
                    </div>
                    <div class="col-md-2 pt-3">
                        <div class="form-group ">
                            <input type="text" name="author" placeholder="AUTHEUR.E" class="form-control" value="<?= $author ?>" id="author">
                        </div>
                    </div>
                    <div class="col-md-2 pt-3">
                        <div class="form-group">
                            <div class="form-group ">
                                <input type="date" name="rentaldate"  class="form-control" value="<?php echo date("d/m/Y"); ?>" id="rentaldate">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 pt-3">
                        <div class="form-group">
                            <select id="inputState" name="select" class="form-control" >
                                <option  <?php if (!empty($filter) && $filter == "tous") { ?>
                                        selected
                                    <?php }
                                    ?> value="tous" >Tous</option>
                                <option 
                                    <?php if (!empty($filter) && $filter == "location") { ?>
                                        selected
                                    <?php }
                                    ?>value="location" >Location</option>
                                <option 
                                    <?php if (!empty($filter) && $filter == "retour") { ?>
                                        selected
                                    <?php }
                                    ?>value="retour">Retour</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block" id="btnFiltre">FILTRER</button>
                    </div>
                </div>
            </form>
        </div>
        <br><br><br><br><br>

        <div class="container table-wrapper-scroll-y " id="phpAffiche">
            <table class="table table-striped table-condensed " id="tabReturn">
                <legend class="text-center" id="legend"><h1>Retours</h1></legend>
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
                                            <input type="hidden" name="idbook" value="<?= $book->id ?>" id="rentalid">
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
            <div id="confirmDialog" hidden>
                <p hidden>ID BOOK: <strong id="rentalid"></strong></p>
                <p>Membre: <strong id="user"></strong></p>
                <p>Titre: <strong id="bookTitle"></strong></p>
                <p>Date de location: <strong id="start"></strong></p>
                <p>Date de Retour:<strong id="end"></strong></p>
            </div>
        </div>
    </div>
</body>
</html>
