<?php
ob_start(); // session management

require('databaseconnection.php'); 

$tbl_name="members"; // Table name if you wish to use a variable

if ($_POST) {
	$select_sql = "SELECT password FROM members WHERE username=:username and password=:password;";
	$statement = $db->prepare($select_sql);
	$statement->execute($_POST);
	$pass = $statement->fetch();
}

// If returned password matches entered password, valid login

if ($pass['password']==$_POST['password']){
	// Register $myusername and redirect to file "login_success.php"
	session_start();
	$_SESSION['username'] = $_POST['username'];
	header("location:login_success.php");
}
else {
	echo "Wrong Username or Password";
	echo "<pre>$select_sql</pre>";
}
ob_end_flush();
?>



