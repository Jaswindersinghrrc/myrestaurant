<?php
ob_start();
session_start();

require_once 'PhpRbac/autoload.php';

use PhpRbac\Rbac;

$rbac = new Rbac();

$role_id = $rbac->Roles->returnId('admin');

if (!isset($g_page)) {
    $g_page = '';
}
?>

<ul id="menu">
    <li><a href="index.php" <?php echo ($g_page == 'index') ? "class='active'" : ''; ?>>Home</a></li>
    <li><a href="create.php" <?php echo ($g_page == 'create') ? "class='active'" : ''; ?>>New Post</a></li>

    <?php if (isset($_SESSION['username'])) { ?>
        <li><a href="logout.php" <?php echo ($g_page == 'logout') ? "class='active'" : ''; ?>>Logout</a></li>
    <?php } else { ?>
        <li><a href="main_login.php" <?php echo ($g_page == 'main_login') ? "class='active'" : ''; ?>>Login</a></li>
        <li><a href="register.php" <?php echo ($g_page == 'register') ? "class='active'" : ''; ?>>Register</a></li>
    <?php } ?>

    <?php if (isset($_SESSION['username']) && $rbac->Users->hasRole($role_id, $_SESSION['userid'])) { ?>
        <li><a href="admin.php" <?php echo ($g_page == 'admin') ? "class='active'" : ''; ?>>Admin</a></li>
    <?php } ?>
</ul> <!-- END div id="menu" -->