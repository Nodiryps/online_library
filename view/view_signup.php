<!DOCTYPE html>
<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <meta charset="UTF-8">
        <title>SignUp</title>
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
        
        <div  class="navbar navbar-default">
            <nav>
                <ul  class="nav navbar-nav">
                    <li> <a href="main/index">Home</a></li>
                </ul>
            </nav>
        </div>
        <div class="container ">
            <h2> Please enter your details to sign up :</h2>
            <br><br>
            <form action="main/signup" method="post" class="form-horizontal ">
                  <legend>Sign up</legend>
                  <table>
                    <tr>
                    <div class="form-group text-center">
                          
                        <td><label class="col-md-4 control-label" for="passwordinput">Username: </label></td>
                        <td><input id="username" name="username" type="text" value="<?= $username; ?>" class="form-control"></td>
                    </div>
                    </tr>
                    <tr>
                        <td><label class="col-md-4 control-label" for="passwordinput">Password:</label></td>
                        <td><input id="password" name="password" type="password" value="<?= $password; ?>" class="form-control"></td>
                    </tr>
                    <tr>
                        
                        <td> <label class="col-md-5 control-label" for="passwordinput">Confirm Password:</label></td>
                        <td><input id="password_confirm" name="password_confirm" type="password" value="<?= $password_confirm; ?>" class="form-control"></td>
                    </tr>
                    <tr>
                        
                         <td> <label class="col-md-5 control-label" for="passwordinput">Fullname</label></td>
                        <td><input id="fullname" name="fullname" type="text" value="<?= $fullname; ?>" class="form-control"></td>
                    </tr>
                    <tr>
                    
                         <td> <label class="col-md-5 control-label" for="passwordinput">Email:</label></td>
                        <td><input id="email" name="email" type="email" value="<?= $email; ?>" class="form-control"></td>
                    </tr>
                    <tr>
                        
                         <td> <label class="col-md-5 control-label" for="passwordinput">Birthdate:</label></td>
                        <td><input id="birthdate" name="birthdate" type="date" value="<?= $birthdate; ?>" class="form-control"></td>
                    </tr>
                </table>
                  <br>
                  <br>
                  <div class="text-center">
                <button type="submit" value="Sign Up" class="btn btn-success "><span>SignUp</span></button>
                  </div>
            </form>
            <?php 
                if(isset($errors)){
                    echo "<div class='errors'>
                          <p>Please correct the following error(s) :</p>
                          <ul>";
                    foreach($errors as $error){
                        echo "<li>".$error."</li>";
                    }
                    echo '</ul></div>';
                } 
            ?>
        </div>
    </body>
</html>