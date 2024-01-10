<?php 
// Put this code in first line of web page. 
session_start();
session_destroy();

echo " Loging out ".$_SESSION['username'];

echo " You will redirect to home page!"


?>

<?php header("refresh:1; url=index.php"); ?>
