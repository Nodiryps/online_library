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
                <?= $profile->fullname; ?>'s profile! (<?= $profile->role ?>) 
            </div>
        </nav>
        <div class="container text-center">
            <h1>Gestion de retours</h1>
            <br>
            <br>

            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <form id="convFilter" class="form-horizontal" action="/admin/conversations/filter">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="Membre">Membre</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="membre" id="Membre" placeholder="le username">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="col-sm-4 control-label" for="Livre">Livre</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="Livre" name="title" placeholder="titre">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for="date de location">date de location</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" name="rentdate" id="toDate" placeholder=" 02/10/2015">
                                    </div>
                                </div>
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="button" id="find-all" class="btn btn-primary">Find All</button>
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                            <div class="funkyradio">
                                <div class="funkyradio-default">
                                    <input type="radio" name="radio" id="radio1" />
                                    <label for="radio1">First Option default</label>
                                </div>
                            </div>
                            	<label class="btn btn-success active">
				<input type="checkbox" autocomplete="off" checked>
				<span class="glyphicon glyphicon-ban-circle">en cours</span>
			</label>
                            
                            	<label class="btn btn-warning active">
				<input type="checkbox" autocomplete="off" checked>
				<span class="glyphicon glyphicon-ban-circle">retour</span>
			</label>
                               	<label class="btn btn-danger active">
				<input type="checkbox" autocomplete="off" checked>
				<span class="glyphicon glyphicon-ban-circle">tous</span>
			</label>


                        </form>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
