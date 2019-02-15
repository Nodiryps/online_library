<!DOCTYPE html>     
<html>
    <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
    <title>Modif profile</title>
    <base href="<?= $web_root ?>"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <style>
        legend, td{text-align: center;}
    </style>
    <body>
        <nav   class="text-right"> 
            <?php include('menuMember.html'); ?>
        </nav>
        <p style="position:absolute;top:80px;right:10px;"><strong><?php echo $utilisateur->fullname; ?>'s profile (<?= $utilisateur->role ?>) </strong></p>

        <table class="container text-center ">
            <legend>Update Your Profile</legend>
        </table>>
        <div class="container row text-center" >
            <strong>Edition du membre : <?= $member->fullname ?>.</strong>

            <br><br>

            <form action="user/edit_profile" method="post" class=" form-horizontal col-lg-12 ">
                <table class="table-dark ">

                 
                   <div class=" form-group">
                       <lab
                    <div class="col-md-5">
                        <input id="ISBN" name="username" type="text" class="form-control input-md" value="<?= $member->username ?>" >
                    </div>
                </div>
                    <tr>
                        <td>fullname:</td>
                        <td><input  name="fullname" type="text" value="<?= $member->fullname ?>"  class="form-control my-input"></td>
                    </tr>
                    <tr>
                        <td>email:</td>
                        <td><input  name="email" type="text" value="<?= $member->email ?>"  class="form-control my-input"></td>
                    </tr>
                    <tr>
                        <td>birthdate</td>
                        <td><input  name="birthdate" type="date" value="<?= $member->birthdate ?>"  class="form-control my-input"></td>
                    </tr> 
                    <?php if (!$utilisateur->is_manager()): ?>
                        <tr>
                            <td>Role</td>
                            <td>
                                <select id="selectbasic" name="role" class="form-control form-control my-input">
                                    <option value="<?= $utilisateur->role ?>"  ><?= $utilisateur->role ?></option>
                                    <?php foreach ($members as $m): ?>
                                        <?php if ($m->role !== $utilisateur->role): ?>
                                            <option value="<?= $m->role ?>"><?= $m->role ?></option>

                                            <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </select>
                            </td>
                        </tr>

                    <?php endif; ?> 
                    <tr>
                        <td>Password</td>
                        <td><input  name="password" type="password"  class="form-control my-input"></td>
                    </tr>

                    <tr>
                        <td>Confirmation du password</td>
                        <td><input  name="confirm_password" type="password"  class="form-control my-input"></td>
                    </tr>
                    <?php //endif;   ?>




                </table >
                <br><br>
                <div class="container row ">
                    <input type="hidden" name="idmember" value="<?= $member->id ?>">
                    <button class="btn btn-success col-lg-offset-3 col-lg-5" type="submit" name="valider" value="Valider">
                        <span>Valider</span>
                    </button>
                    <br> <a href="user/user_list" class="row ">Retour a la liste des membres</a>
            </form>

            

        </div>
    </div>
    <div class="container text text-danger"  >
        <?php foreach ($error as $e): ?>
            <div class='errors'><?= $e ?></div>
        <?php endforeach; ?>
    </div>


</body>
</html>

