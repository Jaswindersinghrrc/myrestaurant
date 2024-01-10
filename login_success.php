<?php
// Check if session is not registered, redirect back to main page. 
// Put this code in first line of web page. 
session_start();

if(!isset($_SESSION['username'])){
  header("location:main_login.php");
}

require 'config.php';
  require 'database.php';
  $g_title = BLOG_NAME . ' - Login Success';
  $g_page = 'main_login';
  require 'header.php';
  require 'menu.php';

?>

<div id="all_blogs">
Login Successful <?= $_SESSION['username'] ?>
</div>

<?php
  require 'footer.php';
?>


