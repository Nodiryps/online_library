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
    
        <div class="container text-center">
            <div class="row ">
                <div class="col-md-10">
                    <div class="myform form  ">
                        <legend> <div class="text-left"><h2> Please enter your details to sign up </h2></div></legend>
                        <form action="main/signup" method="post" >
                            <div class="form-group">
                                <input type="text" name="username"  class="form-control my-input" id="name" placeholder="username">
                            </div>
                            <div class="form-group">
                                <input type="password" name="password"  class="form-control my-input" id="name" placeholder="password">
                            </div>
                            <div class="form-group">
                                <input type="password" name="password_confirm"  class="form-control my-input" id="name" placeholder="confirm password">
                            </div>
                            <div class="form-group">
                                <input type="text" name="fullname"  class="form-control my-input" id="name" placeholder="confirm password">
                            </div>

                            <div class="form-group">
                                <input type="email" name="email"  class="form-control my-input" id="email" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <input type="date"  name="birthdate" id="phone"  class="form-control my-input" placeholder="birthdate">
                            </div>
                            <div class="text-center ">
                                <button type="submit" class=" btn btn-block send-button tx-tfm btn-success">Create an account</button>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
                        <div class="text-center">
        <?php
        if (isset($errors)) {
            echo "<div class='errors'>
                          <p>Please correct the following error(s) :</p>
                          <ul>";
            foreach ($errors as $error) {
                echo "<li>" . $error . "</li>";
            }
            echo '</ul></div>';
        }
        ?>
    </div>
</body>
</html>