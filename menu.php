<div class="menu">
    <a href="profile.php">Home (<?= $logged_username ?>)</a>
    <?php if (!isMember()): ?>
    <a href="users.php">Users</a>
    <?php endif; ?>
    <a href="logout.php">Log Out</a>
</div>