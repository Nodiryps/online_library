<!DOCTYPE html>
<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
         <title>retours</title>
        <meta charset="UTF-8">
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
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
        
        <h1>Gestion de retours</h1>
        
    </body>
</html>
