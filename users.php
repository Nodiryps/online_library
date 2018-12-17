<?php

    require_once "functions.php";
    
    check_manager_or_admin();
    
    $users = get_users();

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Users</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Users</div>
        <?php include('menu.php'); ?>
        <div class="main">
            <table class="message_list">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Birth Date</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $usr) : ?>
                        <tr>
                        <td><?= $usr['username'] ?></td>
                        <td><?= $usr['fullname'] ?></td>
                        <td><?= $usr['email'] ?></td>
                        <td><?= format_date($usr['birthdate']) ?></td>
                        <td><?= $usr['role'] ?></td>
                        <td>
                            <form class="button" action="add_edit_user.php" method="GET">
                                <input type="hidden" name="id" value="<?= $usr['id'] ?>">
                                <input type="submit" value="Edit">
                            </form>
                            <?php if (isAdmin() && $usr['id'] !== $logged_userid): ?>
                                <form class="button" action="delete_user.php" method="GET">
                                    <input type="hidden" name="id" value="<?= $usr['id'] ?>">
                                    <input type="submit" value="Delete">
                                </form>
                            <?php endif; ?>
                        </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <form class="button" action="add_edit_user.php" method="GET">
                <input type="submit" value="New User">
            </form>
        </div>
    </body>
</html>