<html>
    <head>
        <title>Members</title>
        <base href="<?= $web_root ?>"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        
        <div class="menu">
             <?php include('menu.html'); ?>
        </div>
        
         <div class="title"><?= $utilisateur->fullname ?>'s Profile! (<?= $utilisateur->role?>) </div>
           
            <div class="main">
             
                <table >
                    <caption class="main">Liste de membre inscrit</caption>
                    <tr> 
                        <th>Id</th>
                        <th>User</th>
                        <th>Full name</th>
                        <th>email</th>
                        <th>birthdate</th>
                        <th> Role</th>
                        <?php if ($utilisateur->role == "admin" || $utilisateur->role == "manager"): ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                  
                    <?php foreach ($members as $member): ?>
                        <tr>
                            <?php if ($utilisateur->role == "admin"):?>
                             <td> <?= $member->id ?></td>
                             <?php endif; ?>
                            <td> <?= $member->username ?></td>
                            <td> <?= $member->fullname ?></td>
                            <td> <?= $member->email ?></td>
                            <td> <?= $member->birthdate ?></td>
                            <td> <?= $member->role ?></td>
                            <?php if ($utilisateur->role == "admin" || $utilisateur->role == "manager"): ?>
                                <td><form action="user/edit_profile" method="POST">
                                        <input type="hidden"  name="idmember" value="<?= $member->id ?>" >
                                        <input name="modifier" type="submit" value="modifier">
                                    </form>
                                   <?php  if($utilisateur->role=="admin" && $utilisateur->username !=$member->username):?>
                                    <form method="post" action="user/delete_user">
                                        <input type="hidden"  name="iddelete" value="<?= $member->id?>" >
                                        <?php if ($member->username != $utilisateur->username): ?>
                                            <input name="delete" type="submit" value="supprimer">
                                        <?php endif; ?>
                                            <?php endif; ?>
                                    </form>
                                </td>


                            <?php endif; ?>

                        <?php endforeach; ?>

                    </tr>
                </table>
                 <?php if ($utilisateur->role == "admin" || $utilisateur->role == "manager"): ?>
                <form method="post" action="user/add_user">
                    <input type="submit" value="ajouter membre" >
                </form>
                <?php endif; ?>

            </div>
        
    </body>
</html>
